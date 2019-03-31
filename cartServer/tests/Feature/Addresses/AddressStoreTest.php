<?php

namespace Tests\Feature\Addresses;

use App\Models\Country;
use App\User;
use Tests\TestCase;

class AddressStoreTest extends TestCase
{

  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('POST', 'api/addresses')
      ->assertStatus(401);
  }

  /** @test */
  public function it_requires_a_name()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses')
      ->assertJsonValidationErrors(['name']);
  }

  /** @test */
  public function it_requires_an_address_1()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses')
      ->assertJsonValidationErrors(['address_1']);
  }

  /** @test */
  public function it_requires_a_city()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses')
      ->assertJsonValidationErrors(['city']);
  }

  /** @test */
  public function it_requires_a_postal_code()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses')
      ->assertJsonValidationErrors(['postal_code']);
  }

  /** @test */
  public function it_requires_a_country()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses')
      ->assertJsonValidationErrors(['country_id']);
  }

  /** @test */
  public function it_requires_a_valid_country()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses', ['country_id' => 1])
      ->assertJsonValidationErrors(['country_id']);
  }

  /** @test */
  public function it_stores_an_address()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/addresses', $payload = [
      'name' => 'test',
      'address_1' => 'test',
      'city' => 'test',
      'postal_code' => 125468,
      'country_id' => create(Country::class)->id,
    ]);
    $this->assertDatabaseHas('addresses', array_merge($payload, ['user_id' => $user->id]));
  }

  /** @test */
  public function it_returns_an_address_when_created()
  {
    $user = create(User::class);
    $response = $this->jsonAs($user, 'POST', 'api/addresses', $payload = [
      'name' => 'test',
      'address_1' => 'test',
      'city' => 'test',
      'postal_code' => 125468,
      'country_id' => create(Country::class)->id,
    ]);
    $response->assertJsonFragment(['id' => json_decode($response->getContent())->data->id]);
  }
}
