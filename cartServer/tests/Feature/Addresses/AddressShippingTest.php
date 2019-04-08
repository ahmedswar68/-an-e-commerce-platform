<?php

namespace Tests\Feature\Addresses;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddressShippingTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('GET', 'api/addresses/1/shipping')
      ->assertStatus(401);
  }
}
