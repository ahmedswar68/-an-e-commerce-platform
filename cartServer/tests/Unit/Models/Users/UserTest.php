<?php

namespace Tests\Unit\Models\Users;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
  /** @test */
  public function it_hashes_the_password_when_creating()
  {
    $user = create(User::class, ['password' => 'cats']);
    $this->assertNotEquals($user->password, 'cats');
  }
}
