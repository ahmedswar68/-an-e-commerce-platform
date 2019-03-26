<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class LoginTest extends TestCase
{

  /** @test */
  public function it_requires_a_valid_email()
  {
    $this->json('POST', 'api/auth/login')
      ->assertJsonValidationErrors(['email']);
  }

  /** @test */
  public function it_requires_a_password()
  {
    $this->json('POST', 'api/auth/login')
      ->assertJsonValidationErrors(['password']);
  }

  /** @test */
  public function it_returns_an_error_if_users_enters_invalid_login_data()
  {
    $user = create(User::class);
    $this->json('POST', 'api/auth/login', ['email' => $user->email, 'password' => 'dummy'])
      ->assertJsonValidationErrors(['email']);
  }

  /** @test */
  public function it_returns_a_token_if_users_enters_valid_login_data()
  {
    $user = create(User::class, ['password' => 'dummy']);
    $this->json('POST', 'api/auth/login', ['email' => $user->email, 'password' => 'dummy'])
      ->assertJsonStructure(
        [
          'meta' => [
            'token'
          ]
        ]
      );
  }

  /** @test */
  public function it_returns_a_user_if_users_enters_valid_login_data()
  {
    $user = create(User::class, ['password' => 'dummy']);
    $this->json('POST', 'api/auth/login', ['email' => $user->email, 'password' => 'dummy'])
      ->assertJsonFragment([
        'email' => $user->email
      ]);
  }
}
