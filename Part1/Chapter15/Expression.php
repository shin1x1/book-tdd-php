<?php
declare(strict_types=1);

namespace Acme\Part1\Chapter15;

interface Expression
{
    /**
     * @param Expression $addend
     * @return Expression
     */
    public function plus(Expression $addend): Expression;

    /**
     * @param Bank $bank
     * @param string $to
     * @return Money
     */
    public function reduce(Bank $bank, string $to): Money;
}