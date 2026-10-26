<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertDontSee('NPM');
        $response->assertDontSee('NIM');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_student_can_register_with_nim(): void
    {
        $response = $this->post('/register', [
            'name' => 'Fulan Mahasiswa',
            'email' => 'fulan@radenintan.ac.id',
            'nim' => '2271020999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'fulan@radenintan.ac.id',
            'nim' => '2271020999',
            'role' => 'mahasiswa',
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_password_minimum_length_validation_displays_indonesian_message(): void
    {
        $response = $this->post('/register', [
            'name' => 'Fulan Mahasiswa',
            'email' => 'fulan_pendek@radenintan.ac.id',
            'nim' => '2271020001',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'Kata sandi minimal harus 8 karakter.',
        ]);
        $this->assertGuest();
    }
}

