<?php

namespace Tests\Feature\Auth;

use App\User;
use Tests\TestCase;

class MeTest extends TestCase
{
  /** @test */
  public function it_fails_if_user_is_not_authenticated()
  {
    $this->json('GET', 'api/auth/me')
      ->assertStatus(401);
  }

  /** @test */
  public function it_returns_user_details()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'GET', 'api/auth/me')
      ->assertJsonFragment(['email' => $user->email]);
  }
}
