<?php
declare(strict_types=1);

namespace Acme\Part1\Chapter14;

class Sum implements Expression
{
    /** @var  Money */
    public $augend;

    /** @var  Money */
    public $addend;

    /**
     * @param Money $augend
     * @param Money $addend
     */
    public function __construct(Money $augend, Money $addend)
    {
        $this->augend = $augend;
        $this->addend = $addend;
    }

    /**
     * @param Bank $bank
     * @param string $to
     * @return Money
     */
    public function reduce(Bank $bank, string $to): Money
    {
        $amount = $this->augend->amount() + $this->addend->amount();

        return new Money($amount, $to);
    }
}