<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\User;
use Tests\TestCase;

class OrderIndexTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('GET', 'api/orders')
      ->assertStatus(401);
  }

  /** @test */
  public function it_returns_a_collection_of_orders()
  {
    $user = create(User::class);
    $order = create(Order::class, ['user_id' => $user->id]);
    $this->jsonAS($user, 'GET', 'api/orders')
      ->assertJsonFragment([
        'id' => $order->id
      ]);
  }

  /** @test */
  public function it_orders_by_the_latest_first()
  {
    $user = create(User::class);
    $order = create(Order::class, ['user_id' => $user->id]);
    $anotherOrder = create(Order::class, [
      'user_id' => $user->id,
      'created_at' => now()->subDay()
    ]);
    $this->jsonAS($user, 'GET', 'api/orders')
      ->assertSeeInOrder([
        $order->created_at->toDateTimeString(),
        $anotherOrder->created_at->toDateTimeString(),
      ]);
  }
  /** @test */
  public function it_has_pagination()
  {
    $user = create(User::class);
    $this->jsonAS($user, 'GET', 'api/orders')
      ->assertJsonStructure([
        'links','meta'
      ]);
  }
}
