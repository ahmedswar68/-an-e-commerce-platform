<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class OrderCreated
{
  public $order;
  use Dispatchable, SerializesModels;

  /**
   * OrderCreated constructor.
   * @param Order $order
   */
  public function __construct(Order $order)
  {
    $this->order = $order;
  }

}
