<?php

namespace Tests\Unit\Products;

use App\Cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use Tests\TestCase;

class ProductVariationTest extends TestCase
{

  /** @test */
  public function it_has_one_variation_type()
  {
    $variation = create(ProductVariation::class);

    $this->assertInstanceOf(ProductVariationType::class, $variation->type);
  }

  /** @test */
  public function it_belongs_to_a_product()
  {
    $variation = create(ProductVariation::class);

    $this->assertInstanceOf(Product::class, $variation->product);
  }

  /** @test */
  public function it_returns_a_money_instance_of_a_price()
  {
    $variation = create(ProductVariation::class);
    $this->assertInstanceOf(Money::class, $variation->price);
  }

  /** @test */
  public function it_returns_a_formatted_price()
  {
    $variation = create(ProductVariation::class, ['price' => 1000]);
    $this->assertEquals($variation->formattedPrice, 'EGP10.00');
  }

  /** @test */
  public function it_returns_a_product_price_if_price_is_null()
  {
    $product = create(Product::class, ['price' => 1000]);
    $variation = create(ProductVariation::class, ['price' => null, 'product_id' => $product->id]);
    $this->assertEquals($product->price->amount(), $variation->price->amount());
  }

  /** @test */
  public function it_check_if_variation_price_is_different_to_the_product()
  {
    $product = create(Product::class, ['price' => 1000]);
    $variation = create(ProductVariation::class, ['price' => 2000, 'product_id' => $product->id]);
    $this->assertTrue($variation->priceVaries());
  }

  /** @test */
  public function it_has_many_stocks()
  {
    $variation = create(ProductVariation::class);
    $variation->stocks()->save(
      make(Stock::class)
    );
    $this->assertInstanceOf(Stock::class, $variation->stocks->first());
  }

  /** @test */
  public function it_has_stock_information()
  {
    $variation = create(ProductVariation::class);
    $stock = make(Stock::class);

    $variation->stocks()->save($stock);
    $this->assertInstanceOf(ProductVariation::class, $variation->stock->first());
  }

  /** @test */
  public function it_has_stock_count_pivot_within_stock_information()
  {
    $variation = create(ProductVariation::class);
    $variation->stocks()->save(
      make(Stock::class, ['quantity' => $quantity = 5])
    );
    $this->assertEquals($variation->stock->first()->pivot->stock, $quantity);
  }
}
