<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaid
{
  use Dispatchable, SerializesModels;

  /**
   * [$order description]
   * @var [type]
   */
  public $order;

  /**
   * OrderPaid constructor.
   * @param Order $order
   */
  public function __construct(Order $order)
  {
    $this->order = $order;
  }
}
