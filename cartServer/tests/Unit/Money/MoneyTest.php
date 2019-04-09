<?php

namespace Tests\Unit\Money;

use App\Cart\Money;
use Tests\TestCase;
use Money\Money as BaseMoney;

class MoneyTest extends TestCase
{
  /** @test */
  public function it_can_get_the_raw_amount()
  {
    $money = new Money(1000);
    $this->assertEquals($money->amount(), 1000);
  }

  /** @test */
  public function it_can_get_the_formatted_amount()
  {
    $money = new Money(1000);
    $this->assertEquals($money->formatted(), 'EGP10.00');
  }

  /** @test */
  public function it_can_add_up()
  {
    $money = new Money(1000);
    $money->add(new Money(1000));
    $this->assertEquals($money->amount(), 2000);
  }

  /** @test */
  public function it_can_return_the_underlying_instance()
  {
    $money = new Money(1000);
    $this->assertInstanceOf(BaseMoney::class, $money->instance());
  }
}
