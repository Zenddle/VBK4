<?php
namespace App\Services;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\DB;

class LoanService
{
    public const LIMIT = 3;
    public const DAYS  = 7;

    /** Student requests a book -> pending loan. */
    public function request(User $user, Book $book): Loan
    {
        return DB::transaction(function () use ($user, $book) {
            // lock the user row so two parallel requests can't bypass the limit
            User::whereKey($user->id)->lockForUpdate()->first();

            $active = Loan::where('user_id', $user->id)->active()->get();

            if ($active->count() >= self::LIMIT) {
                throw new DomainException('You already have ' . self::LIMIT . ' active loans.');
            }
            if ($active->contains('book_id', $book->id)) {
                throw new DomainException('You already requested or borrowed this book.');
            }

            $fresh = Book::whereKey($book->id)->lockForUpdate()->firstOrFail();
            if ($fresh->available_copies < 1) {
                throw new DomainException('This book is not available.');
            }

            return Loan::create([
                'user_id'      => $user->id,
                'book_id'      => $book->id,
                'status'       => 'pending',
                'requested_at' => now(),
            ]);
        });
    }

    /** Admin approves: sets due date, takes one copy (row lock handles the "last copy" race). */
    public function approve(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();
            if ($loan->status !== 'pending') {
                throw new DomainException('Only pending requests can be approved.');
            }

            $book = Book::whereKey($loan->book_id)->lockForUpdate()->firstOrFail();
            if ($book->available_copies < 1) {
                throw new DomainException('No copies left to lend.');
            }

            $book->decrement('available_copies');
            $loan->update([
                'status'      => 'approved',
                'approved_at' => now(),
                'due_at'      => now()->addDays(self::DAYS),
            ]);
            return $loan;
        });
    }

    public function decline(Loan $loan, ?string $reason = null): Loan
    {
        if ($loan->status !== 'pending') {
            throw new DomainException('Only pending requests can be declined.');
        }
        $loan->update(['status' => 'declined', 'decline_reason' => $reason]);
        return $loan;
    }

    /** Admin marks returned: copy goes back on the shelf. */
    public function markReturned(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {
            $loan = Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();
            if ($loan->status !== 'approved') {
                throw new DomainException('Only approved loans can be returned.');
            }
            Book::whereKey($loan->book_id)->increment('available_copies');
            $loan->update(['status' => 'returned', 'returned_at' => now()]);
            return $loan;
        });
    }
}
