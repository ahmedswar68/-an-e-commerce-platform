<?php

namespace Tests\Unit\Products;

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
}
