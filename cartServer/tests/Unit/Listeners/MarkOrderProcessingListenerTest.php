<?php

namespace Tests\Unit\Listeners;

use App\Events\Order\OrderPaid;
use App\Listeners\Order\MarkOrderProcessing;
use App\Models\Order;
use App\User;
use Tests\TestCase;

class MarkOrderProcessingListenerTest extends TestCase
{
  public function test_it_marks_order_as_processing()
  {
    $event = new OrderPaid(
      $order = create(Order::class, [
        'user_id' => create(User::class)
      ])
    );

    $listener = new MarkOrderProcessing();

    $listener->handle($event);

    $this->assertEquals($order->fresh()->status, Order::PROCESSING);
  }
}
