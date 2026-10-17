<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'nim' => '2111050099',
                'total_sks' => 110,
                'ipk' => 3.50,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertSame('2111050099', $user->nim);
        $this->assertEquals(110, $user->total_sks);
        $this->assertEquals(3.50, $user->ipk);
        $this->assertNull($user->email_verified_at);
    }

    public function test_avatar_can_be_uploaded(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User with Avatar',
                'email' => $user->email,
                'nim' => '2111050088',
                'total_sks' => 110,
                'ipk' => 3.50,
                'avatar' => $file,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertNotNull($user->avatar);
        $this->assertSame('2111050088', $user->nim);
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
                'nim' => $user->nim,
                'total_sks' => 110,
                'ipk' => 3.50,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_mahasiswa_cannot_update_profile_with_ipk_below_2_75(): void
    {
        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'ipk' => 3.00,
            'total_sks' => 110,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Mahasiswa Test',
                'email' => $user->email,
                'nim' => '2111050099',
                'total_sks' => 110,
                'ipk' => 2.50, // di bawah 2.75
            ]);

        $response->assertSessionHasErrors('ipk');
        $user->refresh();
        $this->assertEquals(3.00, $user->ipk); // Data tidak berubah
    }

    public function test_mahasiswa_can_update_profile_with_ipk_2_75(): void
    {
        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'ipk' => 3.00,
            'total_sks' => 110,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Mahasiswa Test',
                'email' => $user->email,
                'nim' => '2111050099',
                'total_sks' => 110,
                'ipk' => 2.75, // pas 2.75
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');
        $user->refresh();
        $this->assertEquals(2.75, $user->ipk);
    }

    public function test_mahasiswa_cannot_update_profile_with_sks_below_110(): void
    {
        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'ipk' => 3.00,
            'total_sks' => 120,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Mahasiswa Test SKS',
                'email' => $user->email,
                'nim' => '2111050099',
                'total_sks' => 105, // di bawah 110
                'ipk' => 3.00,
            ]);

        $response->assertSessionHasErrors('total_sks');
        $user->refresh();
        $this->assertEquals(120, $user->total_sks); // Data tidak berubah
    }

    public function test_mahasiswa_can_update_profile_with_sks_110(): void
    {
        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'ipk' => 3.00,
            'total_sks' => 120,
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Mahasiswa Test SKS',
                'email' => $user->email,
                'nim' => '2111050099',
                'total_sks' => 110, // pas 110
                'ipk' => 3.00,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/profile');
        $user->refresh();
        $this->assertEquals(110, $user->total_sks);
    }
}
