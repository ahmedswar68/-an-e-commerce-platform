<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
  /** @test */
  public function it_requires_the_name()
  {
    $this->json('POST', 'api/auth/register')
      ->assertJsonValidationErrors(['name']);
  }

  /** @test */
  public function it_requires_a_valid_email()
  {
    $this->json('POST', 'api/auth/register', ['email' => 'test'])
      ->assertJsonValidationErrors(['email']);
  }

  /** @test */
  public function it_requires_a_unique_email()
  {
    $user = create(User::class);
    $this->json('POST', 'api/auth/register', ['email' => $user->email])
      ->assertJsonValidationErrors(['email']);
  }

  /** @test */
  public function it_registers_the_user()
  {
    $this->json('POST', 'api/auth/register',
      [
        'email' => $email = 'ahmed@gmail.com',
        'name' => 'Swar',
        'password' => 'swar'
      ]);
    $this->assertDatabaseHas('users', ['email' => $email]);
  }

  /** @test */
  public function it_returns_a_user_on_registration()
  {
    $this->json('POST', 'api/auth/register',
      [
        'email' => $email = 'ahmed@gmail.com',
        'name' => 'Swar',
        'password' => 'swar'
      ])->assertJsonFragment(['email' => $email]);
  }
}
