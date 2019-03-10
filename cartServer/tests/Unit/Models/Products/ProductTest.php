<?php

namespace Tests\Unit\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use Tests\TestCase;


class ProductTest extends TestCase
{
  /** @test */
  public function it_uses_slug_for_route_key_name()
  {
    $product = new Product();
    $this->assertEquals($product->getRouteKeyName(), 'slug');
  }

  /** @test */
  public function it_has_many_categories()
  {
    $category = create(Category::class);
    $category->products()->save(
      create(Product::class)
    );
    $this->assertInstanceOf(Product::class, $category->products->first());
  }

  /** @test */
  public function it_has_many_variations()
  {
    $product = create(Product::class);

    $product->variations()->save(
      create(ProductVariation::class, ['product_id' => $product->id])
    );
    $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
  }
}