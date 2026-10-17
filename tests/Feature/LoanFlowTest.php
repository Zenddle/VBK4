<?php
namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_approve_return_flow(): void
    {
        $student = User::create(['name' => 'A', 'nim' => '1', 'password' => 'secret123', 'role' => 'student']);
        $admin   = User::create(['name' => 'Ad', 'username' => 'admin', 'password' => 'admin12345', 'role' => 'admin']);
        $cat  = Category::create(['name' => 'Test']);
        $book = Book::create(['category_id' => $cat->id, 'title' => 'B', 'year' => 2020, 'total_copies' => 1, 'available_copies' => 1]);

        // student requests -> pending, copies untouched
        $this->actingAs($student)->post('/loans', ['book_id' => $book->id])->assertRedirect(route('student.loans'));
        $loan = Loan::first();
        $this->assertSame('pending', $loan->status);
        $this->assertSame(1, $book->fresh()->available_copies);

        // admin approves -> due in 7 days, copy taken
        $this->actingAs($admin)->post("/admin/loans/{$loan->id}/approve")->assertSessionHas('success');
        $loan->refresh();
        $this->assertSame('approved', $loan->status);
        $this->assertSame(7, (int) round(now()->diffInDays($loan->due_at, false)));
        $this->assertSame(0, $book->fresh()->available_copies);

        // admin marks returned -> copy back
        $this->post("/admin/loans/{$loan->id}/return")->assertSessionHas('success');
        $this->assertSame('returned', $loan->fresh()->status);
        $this->assertNotNull($loan->fresh()->returned_at);
        $this->assertSame(1, $book->fresh()->available_copies);
    }

    public function test_decline_stores_reason(): void
    {
        $student = User::create(['name' => 'A', 'nim' => '1', 'password' => 'secret123', 'role' => 'student']);
        $admin   = User::create(['name' => 'Ad', 'username' => 'admin', 'password' => 'admin12345', 'role' => 'admin']);
        $cat  = Category::create(['name' => 'Test']);
        $book = Book::create(['category_id' => $cat->id, 'title' => 'B', 'year' => 2020, 'total_copies' => 1, 'available_copies' => 1]);
        $loan = Loan::create(['user_id' => $student->id, 'book_id' => $book->id, 'status' => 'pending', 'requested_at' => now()]);

        $this->actingAs($admin)->post("/admin/loans/{$loan->id}/decline", ['reason' => 'Reserved'])->assertSessionHas('success');
        $this->assertSame('declined', $loan->fresh()->status);
        $this->assertSame('Reserved', $loan->fresh()->decline_reason);
    }
}
