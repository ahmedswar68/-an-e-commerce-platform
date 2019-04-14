<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\PaymentMethod::class, function (Faker $faker) {
  return [
    'card_type' => 'Visa',
    'last_four' => '1234',
    'provider_id' => str_random(10)
  ];
});
