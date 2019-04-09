<?php

namespace Tests\Feature\Cart;

use App\Cart\Cart;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\User;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('GET', 'api/cart')
      ->assertStatus(401);
  }

  /** @test */
  public function it_shows_products_in_the_user_cart()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $variation = create(ProductVariation::class)
    );
    $this->jsonAs($user, 'GET', 'api/cart')
      ->assertJsonFragment(
        ['id' => $variation->id]
      );
  }

  /** @test */
  public function it_shows_if_the_cart_is_empty()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'GET', 'api/cart')
      ->assertJsonFragment(
        ['empty' => true]
      );
  }

  /** @test */
  public function it_shows_a_formatted_subtotal()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'GET', 'api/cart')
      ->assertJsonFragment(
        ['subtotal' => 'EGP0.00']
      );
  }

  /** @test */
  public function it_shows_a_formatted_total()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'GET', 'api/cart')
      ->assertJsonFragment(
        ['total' => 'EGP0.00']
      );
  }


  /** @test */
  public function it_syncs_the_cart()
  {
    $cart = new Cart($user = create(User::class));
    $user->cart()->attach($variation = create(ProductVariation::class), ['quantity' => 2]);
    $this->jsonAs($user, 'GET', 'api/cart')
      ->assertJsonFragment(
        ['changed' => true]
      );
  }

  /** @test */
  public function it_shows_a_formatted_total_with_shipping()
  {
    $user = create(User::class);
    $shipping = create(ShippingMethod::class, ['price' => 1000]);
    $this->jsonAs($user, 'GET', "api/cart?shipping_method_id={$shipping->id}")
      ->assertJsonFragment(
        ['total' => 'EGP10.00']
      );
  }
}
