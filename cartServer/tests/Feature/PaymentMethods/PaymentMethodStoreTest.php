<?php

namespace Tests\Feature\PaymentMethods;

use App\User;
use Tests\TestCase;

class PaymentMethodStoreTest extends TestCase
{
  /** @test */
  public function it_fails_if_unauthenticated()
  {
    $this->json('POST', 'api/payment-methods')
      ->assertStatus(401);
  }

  /** @test */
  public function it_requires_a_token()
  {
    $user = create(User::class);

    $this->jsonAs($user, 'POST', 'api/payment-methods')
      ->assertJsonValidationErrors(['token']);
  }

  /** @test */
  public function it_can_successfully_add_a_card()
  {
    $user = create(User::class);

    $this->jsonAs($user, 'POST', 'api/payment-methods', [
      'token' => 'tok_visa'
    ]);
    $this->assertDatabaseHas('payment_methods', [
      'user_id' => $user->id,
      'card_type' => 'Visa',
      'last_four' => '4242',
    ]);
  }

  /** @test */
  public function it_returns_the_created_card()
  {
    $user = create(User::class);

    $this->jsonAs($user, 'POST', 'api/payment-methods', [
      'token' => 'tok_visa'
    ])->assertJsonFragment([
      'card_type' => 'Visa',
    ]);
  }
  /** @test */
  public function it_sets_the_created_card_as_a_default()
  {
    $user = create(User::class);

    $response=$this->jsonAs($user, 'POST', 'api/payment-methods', [
      'token' => 'tok_visa'
    ]);
    $this->assertDatabaseHas('payment_methods', [
    'id' =>  json_decode($response->getContent())->data->id,
    'default' => true
  ]);
  }
}
