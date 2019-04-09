<?php

namespace Tests\Feature\Addresses;

use App\Models\Address;
use App\Models\Country;
use App\Models\ShippingMethod;
use App\User;
use Tests\TestCase;

class AddressShippingTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('GET', 'api/addresses/1/shipping')
      ->assertStatus(401);
  }

  /** @test */
  public function it_fails_if_address_cant_be_found()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'GET', 'api/addresses/1/shipping')
      ->assertStatus(404);
  }

  /** @test */
  public function it_fails_if_address_does_not_belong_to_user()
  {
    $user = create(User::class);
    $address = create(Address::class);
    $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping")
      ->assertStatus(403);
  }

  /** @test */
  public function it_shows_shipping_methods_for_the_given_address()
  {
    $user = create(User::class);
    $address = create(Address::class, [
      'user_id' => $user->id,
      'country_id' => ($country = create(Country::class))->id,
    ]);
    $country->shippingMethods()->save(
      $shipping = create(ShippingMethod::class)
    );
    $this->jsonAs($user, 'GET', "api/addresses/{$address->id}/shipping")
      ->assertJsonFragment([
        'id' => $shipping->id
      ]);
  }
}
