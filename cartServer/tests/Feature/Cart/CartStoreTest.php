<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\User;
use Tests\TestCase;
class CartStoreTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('POST', 'api/cart')
      ->assertStatus(401);
  }

  /** @test */
  public function it_requires_products()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart')
      ->assertJsonValidationErrors(['products']);
  }

  /** @test */
  public function it_requires_products_to_be_an_array()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart', ['products' => 1])
      ->assertJsonValidationErrors(['products']);
  }

  /** @test */
  public function it_requires_products_to_have_an_id()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart',
      [
        'products' => [
          ['quantity' => 1]
        ]
      ])
      ->assertJsonValidationErrors(['products.0.id']);
  }

  /** @test */
  public function it_requires_products_to_exists()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart',
      [
        'products' => [
          ['id' => 1, 'quantity' => 1]
        ]
      ])
      ->assertJsonValidationErrors(['products.0.id']);
  }

  /** @test */
  public function it_requires_products_quantity_to_be_numeric()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart',
      [
        'products' => [
          ['id' => 1, 'quantity' => 'one']
        ]
      ])
      ->assertJsonValidationErrors(['products.0.quantity']);
  }

  /** @test */
  public function it_requires_products_quantity_to_be_at_least_one()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart',
      [
        'products' => [
          ['id' => 1, 'quantity' => 0]
        ]
      ])
      ->assertJsonValidationErrors(['products.0.quantity']);
  }

  /** @test */
  public function it_can_add_products_to_user_cart()
  {
    $variation = create(ProductVariation::class);
    $user = create(User::class);
    $this->jsonAs($user, 'POST', 'api/cart',
      [
        'products' => [
          ['id' => $variation->id, 'quantity' => 2]
        ]
      ]);
    $this->assertDatabaseHas('cart_user', [
      'product_variation_id' => $variation->id,
      'quantity' => 2
    ]);
  }
}
