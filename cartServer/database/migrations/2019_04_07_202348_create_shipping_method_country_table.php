<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingMethodCountryTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shipping_method_country', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('country_id')->unsigned()->index()->default('');
      $table->integer('shipping_method_id')->unsigned()->index()->default('');

      $table->foreign('country_id')->references('id')->on('countries');
      $table->foreign('shipping_method_id')->references('id')->on('shipping_methods');

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('country_shipping_method');
  }
}
