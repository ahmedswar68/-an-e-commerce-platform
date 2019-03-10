<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductShowTest extends TestCase
{
  /** @test */
  public function it_fails_when_cannot_load_a_product()
  {
    $this->json('GET', 'api/products/xxx')
      ->assertStatus(404);
  }

  /** @test */
  public function it_shows_product()
  {
    $product = create(Product::class);
    $this->json('GET', 'api/products/' . $product->slug)
      ->assertJsonFragment(['id' => $product->id]);
  }
}
