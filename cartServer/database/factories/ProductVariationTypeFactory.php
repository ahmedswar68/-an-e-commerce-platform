<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\ProductVariationType::class, function (Faker $faker) {
  $name = $faker->unique()->name;
  return [
    'name' => $name,
  ];
});
