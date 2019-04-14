<?php

namespace Tests\Unit\Models\PaymentMethods;

use App\Models\PaymentMethod;
use App\User;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{

  /** @test */
  public function it_belongs_to_a_user()
  {
    $payment_method = create(PaymentMethod::class, [
      'user_id' => create(User::class)->id
    ]);
    $this->assertInstanceOf(User::class, $payment_method->user);
  }
  /** @test */
  public function it_sets_old_addresses_to_not_default_when_created()
  {
    $user = create(User::class);
    $old_payment_method = create(PaymentMethod::class, [
      'default' => true,
      'user_id' => $user->id
    ]);
    create(PaymentMethod::class, [
      'default' => true,
      'user_id' => $user->id
    ]);
    $this->assertFalse($old_payment_method->fresh()->default);
  }
}
