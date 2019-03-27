<?php

namespace Tests\Unit\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
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

}