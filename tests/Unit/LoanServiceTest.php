<?php
namespace Tests\Unit;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Services\LoanService;
use DomainException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $nim): User
    {
        return User::create(['name' => $nim, 'nim' => $nim, 'password' => 'secret123', 'role' => 'student']);
    }

    private function book(int $copies = 1): Book
    {
        $c = Category::firstOrCreate(['name' => 'Test']);
        return Book::create(['category_id' => $c->id, 'title' => 'B' . rand(), 'year' => 2020,
            'total_copies' => $copies, 'available_copies' => $copies]);
    }

    public function test_last_copy_can_only_be_approved_once(): void
    {
        $service = new LoanService();
        $book = $this->book(1);

        $l1 = $service->request($this->user('1'), $book);
        $l2 = $service->request($this->user('2'), $book); // both can request (pending)

        $service->approve($l1);
        $this->expectException(DomainException::class);
        $service->approve($l2); // no copy left
    }

    public function test_due_date_is_seven_days_after_approval(): void
    {
        $service = new LoanService();
        $loan = $service->request($this->user('1'), $this->book());
        $loan = $service->approve($loan);

        $this->assertSame(LoanService::DAYS, (int) round($loan->approved_at->diffInDays($loan->due_at)));
    }

    public function test_cannot_return_pending_loan(): void
    {
        $service = new LoanService();
        $loan = $service->request($this->user('1'), $this->book());

        $this->expectException(DomainException::class);
        $service->markReturned($loan);
    }
}
