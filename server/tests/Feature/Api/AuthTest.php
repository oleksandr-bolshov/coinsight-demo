<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Domain\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

final class AuthTest extends ApiTestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        $user = factory(User::class)->make();

        $this
            ->apiPost('auth/register', [
                'email' => $user->email,
                'username' => $user->username,
                'password' => 'password',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
            'username' => $user->username,
        ]);
    }

    public function test_login()
    {
        $this
            ->apiPost('auth/login', [
                'username' => $this->user->username,
                'password' => 'password',
            ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'refresh_token',
                ],
            ]);
    }

    public function test_me()
    {
        $this
            ->apiGet('auth/me')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'email' => $this->user->email,
                    'username' => $this->user->username,
                ],
            ]);
    }
}
