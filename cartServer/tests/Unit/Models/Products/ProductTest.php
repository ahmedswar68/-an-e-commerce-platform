<?php

namespace Tests\Unit\Products;

use App\Models\Product;
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
    $category = create('App\Models\Category');
    $category->products()->save(
      create('App\Models\Product')
    );
    $this->assertInstanceOf(Product::class, $category->products->first());
  }
}