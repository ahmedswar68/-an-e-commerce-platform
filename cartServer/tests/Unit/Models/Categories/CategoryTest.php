<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;


class CategoryTest extends TestCase
{
  /** @test */
  public function it_has_many_children()
  {
    $category = create('App\Models\Category');
    $category->children()->save(
      create('App\Models\Category')
    );
    $this->assertInstanceOf(Category::class, $category->children()->first());
  }

  /** @test */
  public function it_can_fetch_only_parents()
  {
    $category = create('App\Models\Category');
    $category->children()->save(
      create('App\Models\Category')
    );
    $this->assertEquals(1, Category::parents()->count());
  }

  /** @test */
  public function it_is_orderable_by_a_numbered_order()
  {
    create('App\Models\Category', [
      'order' => 2
    ]);
    $anotherCategory = create('App\Models\Category', [
      'order' => 1
    ]);

    $this->assertEquals($anotherCategory->name, Category::order()->first()->name);
  }

  /** @test */
  public function it_has_many_products()
  {
    $product = create(Product::class);
    $product->categories()->save(
      create(Category::class)
    );
    $this->assertInstanceOf(Category::class, $product->categories->first());
  }
}
