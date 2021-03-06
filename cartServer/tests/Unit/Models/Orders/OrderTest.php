<?php

namespace Tests\Unit\Models\Orders;

use App\Cart\Money;
use App\Models\Address;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Transaction;
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
  public function it_belongs_to_a_payment_method()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);
    $this->assertInstanceOf(PaymentMethod::class, $order->paymentMethod);
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

  /** @test */
  public function it_returns_a_money_instance_onto_the_subtotal()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);

    $this->assertInstanceOf(Money::class, $order->subtotal);
  }

  /** @test */
  public function it_returns_a_money_instance_onto_the_total()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)
    ]);

    $this->assertInstanceOf(Money::class, $order->total());
  }

  /** @test */
  public function it_adds_shipping_onto_the_total()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class),
      'shipping_method_id' => create(ShippingMethod::class, ['price' => 1000]),
    ]);

    $this->assertEquals($order->total()->amount(), 2000);
  }

  public function test_it_has_many_transactions()
  {
    $order = create(Order::class, [
      'user_id' => create(User::class)->id
    ]);

    create(Transaction::class, [
      'order_id' => $order->id
    ]);

    $this->assertInstanceOf(Transaction::class, $order->transactions->first());
  }
}
