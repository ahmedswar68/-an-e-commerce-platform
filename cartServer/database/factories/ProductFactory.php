<?php

use Faker\Generator as Faker;
use App\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
  $name = $faker->unique()->name;
  return [
    'name' => $name,
    'slug' => str_slug($name),
    'description' => $faker->sentence(10),
    'price' => 1500
  ];
});
