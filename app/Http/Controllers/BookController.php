<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Services\LoanService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::with('category')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%{$s}%")
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$s}%"))))
            ->when($request->category, fn ($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('books.index', [
            'books'      => $books,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Book $book)
    {
        $user = auth()->user();
        $reason = null; // why borrow is disabled

        if ($user->activeLoanCount() >= LoanService::LIMIT) {
            $reason = 'Loan limit reached (3 active).';
        } elseif ($book->available_copies < 1) {
            $reason = 'No copies available.';
        } elseif ($user->loans()->where('book_id', $book->id)->active()->exists()) {
            $reason = 'You already requested or borrowed this book.';
        }

        $wishlisted = $user->wishlist()->where('books.id', $book->id)->exists();

        return view('books.show', compact('book', 'reason', 'wishlisted'));
    }
}
