<?php
declare(strict_types=1);

namespace Acme\Part1\Chapter12;

class Money implements Expression
{
    /** @var  int */
    protected $amount;

    /** @var  string */
    protected $currency;

    /**
     * @param int $amount
     * @param string $currency
     */
    public function __construct(int $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function equals(Money $money): bool
    {
        return $this->amount === $money->amount
            && $this->currency === $money->currency;
    }

    /**
     * @param int $amount
     * @return Money
     */
    public static function dollar(int $amount): Money
    {
        return new Money($amount, 'USD');
    }

    /**
     * @param int $amount
     * @return Money
     */
    public static function franc(int $amount): Money
    {
        return new Money($amount, 'CHF');
    }

    /**
     * @param int $multiplier
     * @return Money
     */
    public function times(int $multiplier): Money
    {
        return new Money($this->amount * $multiplier, $this->currency);
    }

    /**
     * @return string
     */
    public function currency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->amount . ' ' . $this->currency;
    }

    /**
     * @param Money $dollar
     * @return Expression
     */
    public function plus(Money $dollar): Expression
    {
        return new Money($this->amount + $dollar->amount, $this->currency);
    }
}