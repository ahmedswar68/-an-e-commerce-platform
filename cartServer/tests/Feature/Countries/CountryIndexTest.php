<?php

namespace Tests\Feature\Countries;

use App\Models\Country;
use Tests\TestCase;

class CountryIndexTest extends TestCase
{
  /** @test */
  public function it_returns_countries()
  {
    $country = create(Country::class);
    $this->json('GET', 'api/countries')
      ->assertJsonFragment([
        'id' => $country->id
      ]);
  }
}
