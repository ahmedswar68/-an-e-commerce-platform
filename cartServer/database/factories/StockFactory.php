<?php

use Faker\Generator as Faker;
use App\Models\Stock;

$factory->define(\App\Models\Stock::class, function (Faker $faker) {
  return [
    'quantity'=>1
  ];
});
