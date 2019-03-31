<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $countries = [
      'Egypt' => 'EG'
    ];
    collect($countries)->each(function ($code,$name){
      \App\Models\Country::create([
        'code'=>$code,
        'name'=>$name,
      ]);
    });
  }
}
