<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  const  PENDING = 'pending';
  const  PROCESSING = 'processing';
  const  FAILED = 'failed';
  const  COMPLETED = 'completed';

  protected $guarded = [];

  public static function boot()
  {
    parent::boot();
    static::creating(function ($order) {
      $order->status = self::PENDING;
    });
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function address()
  {
    return $this->belongsTo(Address::class);
  }

  public function shippingMethod()
  {
    return $this->belongsTo(ShippingMethod::class);
  }
  public function products()
  {
    return $this->belongsToMany(ProductVariation::class,'product_variation_order')
      ->withPivot(['quantity'])
      ->withTimestamps();
  }
}
