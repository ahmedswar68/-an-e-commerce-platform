<?php

namespace Tests\Unit\Listeners;

use App\Cart\Cart;
use App\Listeners\Order\EmptyCart;
use App\Models\ProductVariation;
use App\User;
use Tests\TestCase;

class EmptyCartListenerTest extends TestCase
{
  public function test_it_should_clear_the_cart()
  {
    $cart = new Cart(
      $user = create(User::class)
    );

    $user->cart()->attach(
      $product = create(ProductVariation::class)
    );

    $listener = new EmptyCart($cart);

    $listener->handle();

    $this->assertEmpty($user->cart);
  }
}
