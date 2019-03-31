<?php

namespace Tests\Unit\Models\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\User;
use Tests\TestCase;

class AddressTest extends TestCase
{
  /** @test */
  public function it_has_one_country()
  {
    $address = create(Address::class, [
      'user_id' => create(User::class)->id
    ]);
    $this->assertInstanceOf(Country::class, $address->country);
  }

  /** @test */
  public function it_belongs_to_a_user()
  {
    $address = create(Address::class, [
      'user_id' => create(User::class)->id
    ]);
    $this->assertInstanceOf(User::class, $address->user);
  }

  /** @test */
  public function it_sets_old_addresses_to_not_default_when_created()
  {
    $user = create(User::class);
    $old_address = create(Address::class, [
      'default' => true,
      'user_id' => $user->id
    ]);
    create(Address::class, [
      'default' => true,
      'user_id' => $user->id
    ]);
    $this->assertFalse($old_address->fresh()->default);
  }
}
