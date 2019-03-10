<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
  /** @test */
  public function it_returns_a_collection_of_categories()
  {
    $categories = create(Category::class, [], 2);
    $response = $this->json('GET', 'api/categories');
    $categories->each(function ($category) use ($response) {
      $response->assertJsonFragment([
        'slug' => $category->slug
      ]);
    });
    $response->assertStatus(200);
  }

  /** @test */
  public function it_returns_only_parent_categories()
  {
    $category = create(Category::class);
    $category->children()->save(create(Category::class));
    $this->json('GET', 'api/categories')
      ->assertJsonCount(1, 'data');
  }

  /** @test */
  public function it_returns_categories_ordered_by_their_given_order()
  {
    $category = create(Category::class, ['order' => 2]);
    $anotherCategory = create(Category::class, ['order' => 1]);
    $this->json('GET', 'api/categories')
      ->assertSeeInOrder([
        $anotherCategory->slug, $category->slug
      ]);
  }
}
