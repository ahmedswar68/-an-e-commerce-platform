<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductFilteringTest extends TestCase
{
  /** @test */
  public function it_can_filter_by_category()
  {
    $product = create(Product::class);
    $product->categories()->save(
      $category = create(Category::class)
    );
    create(Product::class);
    $this->json('GET', 'api/products?category=' . $category->slug)
      ->assertJsonCount(1, 'data');
  }

  /** @test */
  public function it_shows_product()
  {
    $product = create(Product::class);
    $this->json('GET', 'api/products/' . $product->slug)
      ->assertJsonFragment(['id' => $product->id]);
  }
}
