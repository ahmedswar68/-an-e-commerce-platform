<?php

namespace Tests\Feature\PaymentMethods;

use App\Models\PaymentMethod;
use App\User;
use Tests\TestCase;

class PaymentMethodIndexTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('GET', 'api/payment-methods')
      ->assertStatus(401);
  }

  /** @test */
  public function it_returns_a_collection_of_payment_methods()
  {
    $user = create(User::class);
    $payment = create(PaymentMethod::class, [
      'user_id' => $user->id
    ]);

    $this->jsonAs($user, 'GET', 'api/payment-methods')
      ->assertJsonFragment(
        ['id' => $payment->id]
      );
  }
}
