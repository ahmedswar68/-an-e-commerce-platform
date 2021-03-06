<?php

namespace Tests\Unit\Products;

use App\Cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
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


  public function it_has_many_variations()
  {
    $product = create(Product::class);

    $product->variations()->save(
      create(ProductVariation::class, ['product_id' => $product->id])
    );
    $this->assertInstanceOf(ProductVariation::class, $product->variations->first());
  }

  /** @test */
  public function it_returns_a_money_instance_of_a_price()
  {
    $product = create(Product::class);
    $this->assertInstanceOf(Money::class, $product->price);
  }

  /** @test */
  public function it_returns_a_formatted_price()
  {
    $product = create(Product::class, ['price' => 1000]);
    $this->assertEquals($product->formattedPrice, 'EGP10.00');
  }

  /** @test */
  public function it_can_check_if_its_in_stock()
  {
    $product = create(Product::class);

    $product->variations()->save(
      $variation = create(ProductVariation::class)
    );
    $variation->stocks()->save(
      make(Stock::class)
    );
    $this->assertTrue($product->inStock());
  }
  /** @test */
  public function it_can_get_stock_count()
  {
    $product = create(Product::class);

    $product->variations()->save(
      $variation = create(ProductVariation::class)
    );
    $variation->stocks()->save(
      make(Stock::class, ['quantity' => $quantity = 5])
    );
    $this->assertEquals($product->stockCount(), $quantity);
  }
}