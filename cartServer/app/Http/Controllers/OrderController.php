<?php

namespace App\Http\Controllers;

use App\Cart\Cart;
use App\Http\Requests\Orders\OrderStoreRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  protected $cart;

  public function __construct()
  {
    $this->middleware(['auth:api']);
  }

  public function store(OrderStoreRequest $request, Cart $cart)
  {
    $order = $this->createOrder($request, $cart);
    $products = $cart->products()->keyBy('id')->map(function ($product) {
      return [
        'quantity' => $product->pivot->quantity
      ];
    })->toArray();
    $order->products()->sync($products);
  }

  protected function createOrder(Request $request, Cart $cart)
  {

    return $request->user()->orders()->create(
      array_merge($request->only(['address_id', 'shipping_method_id']), [
        'subtotal' => $cart->subtotal()->amount()
      ])
    );
  }
}
