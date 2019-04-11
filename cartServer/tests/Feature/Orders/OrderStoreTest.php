<?php

namespace Tests\Feature\Orders;

use App\Events\Order\OrderCreated;
use App\Models\Address;
use App\Models\Country;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OrderStoreTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('POST', 'api/orders')
      ->assertStatus(401);
  }

  /** @test */
  public function it_requires_an_address_id()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    $this->jsonAs($user, 'POST', 'api/orders')
      ->assertJsonValidationErrors(['address_id']);
  }

  /** @test */
  public function it_requires_an_address_id_that_exists()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => 1
    ])
      ->assertJsonValidationErrors(['address_id']);
  }

  /** @test */
  public function it_requires_an_address_id_that_belongs_to_authenticated_user()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    $address = create(Address::class, ['user_id' => create(User::class)->id]);
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id
    ])
      ->assertJsonValidationErrors(['address_id']);
  }

  /** @test */
  public function it_requires_a_shipping_method_id()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    $this->jsonAs($user, 'POST', 'api/orders')
      ->assertJsonValidationErrors(['shipping_method_id']);
  }

  /** @test */
  public function it_requires_a_shipping_method_id_that_exists()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => 1
    ])
      ->assertJsonValidationErrors(['shipping_method_id']);
  }

  /** @test */
  public function it_requires_a_valid_shipping_method_for_the_given_address()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    $country = create(Country::class);

    $address = create(Address::class, [
      'user_id' => $user->id,
      'country_id' => $country->id,
    ]);

    $shipping = create(ShippingMethod::class);

    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ])->assertJsonValidationErrors(['shipping_method_id']);
  }

  /** @test */
  public function it_can_create_an_order()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    list($address, $shipping) = $this->orderDependencies($user);
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ]);
    $this->assertDatabaseHas('orders', [
      'user_id' => $user->id,
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ]);
  }

  /** @test */
  public function it_attaches_products_to_order()
  {
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    list($address, $shipping) = $this->orderDependencies($user);
    $response = $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ]);
    $this->assertDatabaseHas('product_variation_order', [
      'product_variation_id' => $product->id,
      'order_id' => json_decode($response->getContent())->data->id
    ]);
  }

  /** @test */
  public function it_fails_to_to_create_order_if_cart_is_empty()
  {
    $user = create(User::class);
    $user->cart()->sync([
        ($product = $this->productWithStock())->id => ['quantity' => 0]
      ]
    );
    list($address, $shipping) = $this->orderDependencies($user);
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ])->assertStatus(400);

  }

  /** @test */
  public function it_fires_an_order_created_event()
  {
    Event::fake();
    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    list($address, $shipping) = $this->orderDependencies($user);
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ]);
    Event::assertDispatched(OrderCreated::class);
//    $this->assertDatabaseHas('product_variation_order', [
//      'product_variation_id' => $product->id
//    ]);
  }

  /** @test */
  public function it_empties_the_cart_when_ordering()
  {

    $user = create(User::class);
    $user->cart()->sync(
      $product = $this->productWithStock()
    );
    list($address, $shipping) = $this->orderDependencies($user);
    $this->jsonAs($user, 'POST', 'api/orders', [
      'address_id' => $address->id,
      'shipping_method_id' => $shipping->id
    ]);
    $this->assertEmpty($user->cart);
  }

  protected function productWithStock()
  {
    $product = create(ProductVariation::class);
    create(Stock::class, [
      'product_variation_id' => $product->id
    ]);
    return $product;
  }

  protected function orderDependencies(User $user)
  {
    $address = create(Address::class, [
      'user_id' => $user->id,
    ]);

    $shipping = create(ShippingMethod::class);
    $shipping->countries()->attach($address->country);
    return [$address, $shipping];
  }
}
