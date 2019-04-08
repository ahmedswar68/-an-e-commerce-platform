<?php

use Faker\Generator as Faker;
use App\Models\ShippingMethod;

$factory->define(ShippingMethod::class, function (Faker $faker) {
  return [
    'name' => $faker->name,
    'price' => 1200,
  ];
});
