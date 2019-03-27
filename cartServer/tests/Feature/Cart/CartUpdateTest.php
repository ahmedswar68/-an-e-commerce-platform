<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\User;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('PATCH', 'api/cart/1')
      ->assertStatus(401);
  }

  /** @test */
  public function it_fails_if_product_cannot_be_found()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'PATCH', 'api/cart/1')
      ->assertStatus(404);
  }

  /** @test */
  public function it_requires_a_quantity()
  {
    $user = create(User::class);
    $variation = create(ProductVariation::class);
    $this->jsonAs($user, 'PATCH', 'api/cart/' . $variation->id)
      ->assertJsonValidationErrors(['quantity']);
  }

  /** @test */
  public function it_requires_a_numeric_quantity()
  {
    $user = create(User::class);
    $variation = create(ProductVariation::class);
    $this->jsonAs($user, 'PATCH', 'api/cart/' . $variation->id, [
      'quantity' => 'one'
    ])
      ->assertJsonValidationErrors(['quantity']);
  }

  /** @test */
  public function it_requires_quantity_of_one_or_more()
  {
    $user = create(User::class);
    $variation = create(ProductVariation::class);
    $this->jsonAs($user, 'PATCH', 'api/cart/' . $variation->id, [
      'quantity' => 0
    ])
      ->assertJsonValidationErrors(['quantity']);
  }

  /** @test */
  public function it_updates_the_quantity_of_a_product()
  {
    $user = create(User::class);
    $user->cart()->attach(
      $variation = create(ProductVariation::class), [
        'quantity' => 1
      ]
    );

    $this->jsonAs($user, 'PATCH', 'api/cart/' . $variation->id, [
      'quantity' => $quantity = 5
    ]);
    $this->assertDatabaseHas('cart_user', [
      'product_variation_id' => $variation->id,
      'quantity' => $quantity
    ]);
  }

}
