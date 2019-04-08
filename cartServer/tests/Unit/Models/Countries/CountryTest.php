<?php

namespace Tests\Unit\Models\Countries;

use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;

class CountryTest extends TestCase
{
  /** @test */
  public function it_has_many_shippingMethods()
  {
    $country = create(Country::class);
    $country->shippingMethods()->attach(
      create(ShippingMethod::class)
    );
    $this->assertInstanceOf(ShippingMethod::class, $country->shippingMethods->first());
  }
}
