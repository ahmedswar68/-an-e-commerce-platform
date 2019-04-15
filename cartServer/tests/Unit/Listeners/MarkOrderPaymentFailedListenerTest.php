<?php

namespace Tests\Unit\Listeners;

use App\Events\Order\OrderPaymentFailed;
use App\Listeners\Order\MarkOrderPaymentFailed;
use App\Models\Order;
use App\User;
use Tests\TestCase;

class MarkOrderPaymentFailedListenerTest extends TestCase
{
  public function test_it_marks_order_as_payment_failed()
  {
    $event = new OrderPaymentFailed(
      $order = create(Order::class,[
        'user_id' => factory(User::class)->create()
      ])
    );

    $listener = new MarkOrderPaymentFailed();

    $listener->handle($event);

    $this->assertEquals($order->fresh()->status, Order::FAILED);
  }
}
