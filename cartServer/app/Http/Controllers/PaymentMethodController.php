<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentMethodResource;
use Illuminate\Http\Request;


class PaymentMethodController extends Controller
{

  public function __construct()
  {
    $this->middleware(['auth:api']);
  }

  public function index(Request $request)
  {
    return PaymentMethodResource::collection($request->user()->paymentMethods);
  }
}
