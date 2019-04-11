<?php

namespace Tests\Unit\Models\Orders;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
  /** @test */
  public function it_has_a_default_status_of_pending()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);
    $this->assertEquals($order->status, Order::PENDING);
  }

  /** @test */
  public function it_belongs_to_a_user()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);
    $this->assertInstanceOf(User::class, $order->user);
  }

  /** @test */
  public function it_belongs_to_an_address()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);
    $this->assertInstanceOf(Address::class, $order->address);
  }

  /** @test */
  public function it_belongs_to_a_shipping_method()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);
    $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
  }

  /** @test */
  public function it_has_many_products()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);
    $order->products()->attach(
      create(ProductVariation::class)
    );
    $this->assertInstanceOf(ProductVariation::class, $order->products->first());
  }
}
