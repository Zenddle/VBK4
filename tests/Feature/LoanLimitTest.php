<?php
namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanLimitTest extends TestCase
{
    use RefreshDatabase;

    private function makeBooks(int $n)
    {
        $cat = Category::create(['name' => 'Test']);
        return collect(range(1, $n))->map(fn ($i) => Book::create([
            'category_id' => $cat->id, 'title' => "Book $i", 'year' => 2020,
            'total_copies' => 5, 'available_copies' => 5,
        ]));
    }

    public function test_fourth_active_loan_is_rejected(): void
    {
        $student = User::create(['name' => 'A', 'nim' => '1', 'password' => 'secret123', 'role' => 'student']);
        $books = $this->makeBooks(4);

        foreach ($books->take(3) as $b) {
            $this->actingAs($student)->post('/loans', ['book_id' => $b->id])->assertSessionMissing('error');
        }
        $this->post('/loans', ['book_id' => $books[3]->id])->assertSessionHas('error');
        $this->assertSame(3, $student->activeLoanCount());
    }

    public function test_duplicate_request_is_rejected(): void
    {
        $student = User::create(['name' => 'A', 'nim' => '1', 'password' => 'secret123', 'role' => 'student']);
        $book = $this->makeBooks(1)->first();

        $this->actingAs($student)->post('/loans', ['book_id' => $book->id]);
        $this->post('/loans', ['book_id' => $book->id])->assertSessionHas('error');
        $this->assertSame(1, $student->activeLoanCount());
    }

    public function test_unavailable_book_cannot_be_requested(): void
    {
        $student = User::create(['name' => 'A', 'nim' => '1', 'password' => 'secret123', 'role' => 'student']);
        $book = $this->makeBooks(1)->first();
        $book->update(['available_copies' => 0]);

        $this->actingAs($student)->post('/loans', ['book_id' => $book->id])->assertSessionHas('error');
    }
}
