<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Cart\Money;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\User;
use Tests\TestCase;

class CartTest extends TestCase
{
  /** @test */
  public function it_can_add_products_to_the_cart()
  {
    $cart = new Cart($user = create(User::class));
    $variation = create(ProductVariation::class);
    $cart->add([
      ['id' => $variation->id, 'quantity' => 1]]);
    $this->assertCount(1, $user->fresh()->cart);
  }

  /** @test */
  public function it_increments_quantity_when_adding_more_products()
  {
    $variation = create(ProductVariation::class);
    $cart = new Cart($user = create(User::class));
    $cart->add([
      ['id' => $variation->id, 'quantity' => 1]]);
    $cart = new Cart($user->fresh());
    $cart->add([
      ['id' => $variation->id, 'quantity' => 1]]);
    $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
  }

  /** @test */
  public function it_can_update_quantities_in_the_cart()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class), [
      'quantity' => 1
    ]);

    $cart->update($variation->id, 2);
    $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 2);
  }

  /** @test */
  public function it_can_delete_products_from_the_cart()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class), [
      'quantity' => 1
    ]);

    $cart->delete($variation->id);
    $this->assertCount(0, $user->fresh()->cart);
  }

  /** @test */
  public function it_can_empty_the_cart()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class));

    $cart->empty();
    $this->assertCount(0, $user->fresh()->cart);
  }

  /** @test */
  public function it_can_check_if_the_cart_is_empty_of_quantity()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class), ['quantity' => 0]);

    $this->assertTrue($cart->isEmpty());
  }

  /** @test */
  public function it_returns_a_money_instance_for_subtotal()
  {
    $cart = new Cart($user = create(User::class));
    $this->assertInstanceOf(Money::class, $cart->subtotal());
  }

  /** @test */
  public function it_returns_a_money_instance_for_total()
  {
    $cart = new Cart($user = create(User::class));
    $this->assertInstanceOf(Money::class, $cart->total());
  }

  /** @test */
  public function it_returns_the_correct_value_of_subtotal()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class, ['price' => 1000]), ['quantity' => 2]);

    $this->assertEquals($cart->subtotal()->amount(), 2000);
  }

  /** @test */
  public function it_syncs_the_cart_to_update_quantities()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class), ['quantity' => 2]);
    $cart->sync();

    $this->assertEquals($user->fresh()->cart->first()->pivot->quantity, 0);
  }

  /** @test */
  public function it_can_check_if_the_cart_has_changed_after_syncing()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class), ['quantity' => 2]);
    $cart->sync();

    $this->assertTrue($cart->hasChanged());
  }

  /** @test */
  public function it_doesnot_change_the_cart()
  {
    $cart = new Cart($user = create(User::class));

    $cart->sync();

    $this->assertFalse($cart->hasChanged());
  }

  /** @test */
  public function it_can_return_the_correct_total_without_shipping()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach(
      $variation = create(ProductVariation::class, ['price' => 1000]),
      ['quantity' => 2]
    );

    $this->assertEquals($cart->total()->amount(), 2000);
  }
  /** @test */
  public function it_can_return_the_correct_total_with_shipping()
  {
    $cart = new Cart($user = create(User::class));

    $shipping=create(ShippingMethod::class,['price'=>1000]);

    $user->cart()->attach(
      $variation = create(ProductVariation::class, ['price' => 1000]),
      ['quantity' => 2]
    );

    $cart->withShipping($shipping->id);
    $this->assertEquals($cart->total()->amount(), 3000);
  }
}
