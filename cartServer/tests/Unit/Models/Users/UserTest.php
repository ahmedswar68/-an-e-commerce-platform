<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\PaymentMethod;
use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
  /** @test */
  public function it_hashes_the_password_when_creating()
  {
    $user = create(User::class, ['password' => 'cats']);
    $this->assertNotEquals($user->password, 'cats');
  }

  /** @test */
  public function it_has_many_cart_products()
  {
    $user = create(User::class);
    $user->cart()->attach(create(ProductVariation::class));
    $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
  }

  /** @test */
  public function it_has_a_quantity_for_each_cart_product()
  {
    $user = create(User::class);
    $user->cart()->attach(create(ProductVariation::class, ['quantity' => $quantity = 5]));
    $this->assertEquals($user->cart->first(), $quantity);
  }

  /** @test */
  public function it_has_many_addresses()
  {
    $user = create(User::class);
    $user->addresses()->save(make(Address::class));
    $this->assertInstanceOf(Address::class, $user->addresses->first());
  }

  /** @test */
  public function it_has_many_orders()
  {
    $user = create(User::class);
    create(Order::class, ['user_id' => $user->id]);
    $this->assertInstanceOf(Order::class, $user->orders->first());
  }
  /** @test */
  public function it_has_many_payment_methods()
  {
    $user = create(User::class);
    create(PaymentMethod::class, ['user_id' => $user->id]);
    $this->assertInstanceOf(PaymentMethod::class, $user->paymentMethods->first());
  }
}
