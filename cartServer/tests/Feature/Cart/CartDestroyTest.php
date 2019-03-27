<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\User;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('DELETE', 'api/cart/1')
      ->assertStatus(401);
  }

  /** @test */
  public function it_fails_if_product_cannot_be_found()
  {
    $user = create(User::class);
    $this->jsonAs($user, 'DELETE', 'api/cart/1')
      ->assertStatus(404);
  }

  /** @test */
  public function it_can_delete_an_item_from_the_cart()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $variation = create(ProductVariation::class)
    );
    $this->jsonAs($user, 'DELETE', 'api/cart/' . $variation->id);
    $this->assertDatabaseMissing('cart_user', [
      'product_variation_id' => $variation->id
    ]);
  }
}
