<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressIndexTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('GET', 'api/addresses')
      ->assertStatus(401);
  }

  /** @test */
  public function it_shows_addresses()
  {
    $user = create(User::class);
    $address = create(Address::class, [
      'user_id' => $user->id
    ]);

    $this->jsonAs($user, 'GET', 'api/addresses')
      ->assertJsonFragment(
        ['id' => $address->id]
      );
  }
}
