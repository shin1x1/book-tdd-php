<?php
declare(strict_types=1);

namespace Acme\Part1\Chapter2;

class Dollar
{
    /** @var  int */
    public $amount;

    /**
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @param int $multiplier
     * @return Dollar
     */
    public function times(int $multiplier): Dollar
    {
        return new Dollar($this->amount * $multiplier);
    }
}