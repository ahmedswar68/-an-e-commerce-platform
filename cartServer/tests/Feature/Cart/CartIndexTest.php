<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}
