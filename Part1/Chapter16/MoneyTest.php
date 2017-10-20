<?php
declare(strict_types=1);

namespace Acme\Part1\Chapter16;

use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    /** @var  Expression */
    private $fiveBucks;

    /** @var  Expression */
    private $tenFrans;

    /** @var  Bank */
    private $bank;

    protected function setUp()
    {
        parent::setUp();

        $this->fiveBucks = Money::dollar(5);
        $this->tenFrans = Money::franc(10);

        $this->bank = new Bank();
        $this->bank->addRate('CHF', 'USD', 2);
    }


    public function testMultiplication()
    {
        $five = Money::dollar(5);

        $this->assertTrue(Money::dollar(10)->equals($five->times(2)));
        $this->assertTrue(Money::dollar(15)->equals($five->times(3)));
    }

    public function testEquality()
    {
        $this->assertTrue(Money::dollar(5)->equals(Money::dollar(5)));
        $this->assertFalse(Money::dollar(5)->equals(Money::dollar(6)));

        $this->assertFalse(Money::franc(5)->equals(Money::dollar(5)));
    }

    public function testCurrency()
    {
        $this->assertSame('USD', Money::dollar(1)->currency());
        $this->assertSame('CHF', Money::franc(1)->currency());

    }

    public function testSimpleAddition()
    {
        $five = Money::dollar(5);
        $sum = $five->plus($five);
        $bank = new Bank();
        $reduced = $bank->reduce($sum, 'USD');

        $this->assertTrue(Money::dollar(10)->equals($reduced));
    }

    public function testPlusReturnsSum()
    {
        $five = Money::dollar(5);
        $result = $five->plus($five);
        /** @var Sum $sum */
        $sum = $result;

        $this->assertTrue($five->equals($sum->augend));
        $this->assertTrue($five->equals($sum->addend));
    }

    public function testReduceSum()
    {
        $sum = new Sum(Money::dollar(3), Money::dollar(4));
        $bank = new Bank();
        $result = $bank->reduce($sum, 'USD');

        $this->assertTrue(Money::dollar(7)->equals($result));
    }

    public function testReduceMoney()
    {
        $bank = new Bank();
        $result = $bank->reduce(Money::dollar(1), 'USD');

        $this->assertTrue(Money::dollar(1)->equals($result));
    }

    public function testReduceMoneyDifferentCurrency()
    {
        $result = $this->bank->reduce(Money::franc(2), 'USD');

        $this->assertTrue(Money::dollar(1)->equals($result));
    }

    public function testIdentityRate()
    {
        $this->assertSame(1, (new Bank())->rate('USD', 'USD'));
    }

    public function testMixedAddition()
    {
        $result = $this->bank->reduce($this->fiveBucks->plus($this->tenFrans), 'USD');

        $this->assertTrue(Money::dollar(10)->equals($result));
    }

    public function testSumPlusMoney()
    {
        $sum = (new Sum($this->fiveBucks, $this->tenFrans))->plus($this->fiveBucks);
        $result = $this->bank->reduce($sum, 'USD');

        $this->assertTrue(Money::dollar(15)->equals($result));
    }

    public function testSumTimes()
    {
        $sum = (new Sum($this->fiveBucks, $this->tenFrans))->times(2);
        $result = $this->bank->reduce($sum, 'USD');

        $this->assertTrue(Money::dollar(20)->equals($result));
    }
}