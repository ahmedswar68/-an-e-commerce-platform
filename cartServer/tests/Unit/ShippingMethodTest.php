<?php

namespace Tests\Unit;

use App\Models\Country;
use App\Models\ShippingMethod;
use Tests\TestCase;


class ShippingMethodTest extends TestCase
{
  /** @test */
  public function it_belongs_to_many_countries()
  {
    $shipping = create(ShippingMethod::class);
    $shipping->countries()->attach(
      create(Country::class)
    );
    $this->assertInstanceOf(Country::class, $shipping->countries->first());
  }
  /** @test */
  public function it_returns_a_money_instance_for_the_price()
  {
    $shipping = create(ShippingMethod::class);
    $this->assertInstanceOf(\App\Cart\Money::class, $shipping->price);
  }
}
