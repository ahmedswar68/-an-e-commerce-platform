<?php

namespace Tests\Unit\Models\Users;

use App\Models\Address;
use App\Models\ProductVariation;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
  /** @test */
  public function it_hashes_the_password_when_creating()
  {
    $user = create(User::class, ['password' => 'cats']);
    $this->assertNotEquals($user->password, 'cats');
  }

  /** @test */
  public function it_has_many_cart_products()
  {
    $user = create(User::class);
    $user->cart()->attach(create(ProductVariation::class));
    $this->assertInstanceOf(ProductVariation::class, $user->cart->first());
  }

  /** @test */
  public function it_has_a_quantity_for_each_cart_product()
  {
    $user = create(User::class);
    $user->cart()->attach(create(ProductVariation::class, ['quantity' => $quantity = 5]));
    $this->assertEquals($user->cart->first(), $quantity);
  }

  /** @test */
  public function it_has_many_addresses()
  {
    $user = create(User::class);
    $user->addresses()->save(make(Address::class));
    $this->assertInstanceOf(Address::class, $user->addresses->first());
  }
}
