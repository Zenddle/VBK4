<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function student(): User
    {
        return User::create(['name' => 'Alice', 'nim' => '2024001', 'password' => 'secret123', 'role' => 'student']);
    }

    private function admin(): User
    {
        return User::create(['name' => 'Boss', 'username' => 'admin', 'password' => 'admin12345', 'role' => 'admin']);
    }

    public function test_student_can_login_with_nim(): void
    {
        $this->student();
        $this->post('/login', ['type' => 'student', 'identifier' => '2024001', 'password' => 'secret123'])
            ->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticated();
    }

    public function test_admin_can_login_with_username(): void
    {
        $this->admin();
        $this->post('/login', ['type' => 'admin', 'identifier' => 'admin', 'password' => 'admin12345'])
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_wrong_password_fails(): void
    {
        $this->student();
        $this->post('/login', ['type' => 'student', 'identifier' => '2024001', 'password' => 'nope'])
            ->assertSessionHasErrors('identifier');
        $this->assertGuest();
    }

    public function test_student_cannot_use_admin_tab(): void
    {
        $this->student();
        $this->post('/login', ['type' => 'admin', 'identifier' => '2024001', 'password' => 'secret123'])
            ->assertSessionHasErrors('identifier');
    }

    public function test_student_cannot_access_admin_routes(): void
    {
        $this->actingAs($this->student())->get('/admin')->assertForbidden();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/admin')->assertRedirect(route('login'));
    }
}
