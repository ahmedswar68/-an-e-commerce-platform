<?php

namespace App\cart;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money as BaseMoney;
use NumberFormatter;

class Money
{
  protected $money;

  public function __construct($value)
  {
    $this->money = new BaseMoney($value, new Currency('EGP'));
  }

  public function amount()
  {
    return $this->money->getAmount();
  }

  public function formatted()
  {
    $formatter = new IntlMoneyFormatter(
      new NumberFormatter('en_GB', NumberFormatter::CURRENCY),
      new ISOCurrencies()
    );
    return $formatter->format($this->money);
  }
}