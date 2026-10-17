<?php
namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OverdueTest extends TestCase
{
    use RefreshDatabase;

    private function loan(array $attrs): Loan
    {
        $u = User::create(['name' => 'A', 'nim' => (string) rand(1000, 9999), 'password' => 'secret123', 'role' => 'student']);
        $c = Category::firstOrCreate(['name' => 'Test']);
        $b = Book::create(['category_id' => $c->id, 'title' => 'B', 'year' => 2020, 'total_copies' => 1, 'available_copies' => 0]);
        return Loan::create(array_merge(['user_id' => $u->id, 'book_id' => $b->id], $attrs));
    }

    public function test_approved_past_due_is_overdue(): void
    {
        $loan = $this->loan(['status' => 'approved', 'approved_at' => now()->subDays(10), 'due_at' => now()->subDays(3)]);
        $this->assertTrue($loan->is_overdue);
        $this->assertSame('overdue', $loan->display_status);
    }

    public function test_approved_not_yet_due_is_not_overdue(): void
    {
        $loan = $this->loan(['status' => 'approved', 'approved_at' => now(), 'due_at' => now()->addDays(5)]);
        $this->assertFalse($loan->is_overdue);
    }

    public function test_returned_loan_is_never_overdue(): void
    {
        $loan = $this->loan(['status' => 'returned', 'due_at' => now()->subDays(3), 'returned_at' => now()]);
        $this->assertFalse($loan->is_overdue);
    }

    public function test_admin_dashboard_groups_overdue_student(): void
    {
        $admin = User::create(['name' => 'Ad', 'username' => 'admin', 'password' => 'admin12345', 'role' => 'admin']);
        $this->loan(['status' => 'approved', 'approved_at' => now()->subDays(10), 'due_at' => now()->subDays(3)]);

        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Overdue');
    }
}
