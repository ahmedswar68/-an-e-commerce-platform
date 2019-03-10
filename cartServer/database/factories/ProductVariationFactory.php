<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\ProductVariation::class, function (Faker $faker) {
  $name = $faker->unique()->name;
  return [
    'name' => $name,
    'product_variation_type_id' => factory(\App\Models\ProductVariationType::class)->create()->id,
    'product_id' => factory(\App\Models\Product::class)->create()->id
  ];
});
