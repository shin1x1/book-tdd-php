<?php
declare(strict_types=1);

namespace Acme\Part1\Chapter6;

class Money
{
    /** @var  int */
    protected $amount;

    /**
     * @param Money $money
     * @return bool
     */
    public function equals(Money $money): bool
    {
        return $this->amount === $money->amount;
    }
}