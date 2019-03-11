<?php

namespace Tests\Unit\Products;

use App\cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
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
}
