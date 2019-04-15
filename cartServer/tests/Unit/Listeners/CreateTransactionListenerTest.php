<?php

namespace Tests\Unit\Listeners;

use App\Events\Order\OrderPaid;
use App\Listeners\Order\CreateTransaction;
use App\Models\Order;
use App\User;
use Tests\TestCase;

class CreateTransactionListenerTest extends TestCase
{
  public function test_it_creates_a_transaction()
  {
    $event = new OrderPaid(
      $order = factory(Order::class)->create([
        'user_id' => factory(User::class)->create()
      ])
    );

    $listener = new CreateTransaction();

    $listener->handle($event);

    $this->assertDatabaseHas('transactions', [
      'order_id' => $order->id,
      'total' => $order->total()->amount()
    ]);
  }
}
