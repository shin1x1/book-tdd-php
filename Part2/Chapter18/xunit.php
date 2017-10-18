<?php
declare(strict_types=1);

namespace Acme\Chapter18;

class TestCase
{
    /** @var  string */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function run(): void
    {
        call_user_func([$this, $this->name]);
    }
}

class WasRun extends TestCase
{
    /** @var  bool */
    private $wasRun;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->wasRun = false;
    }

    public function testMethod(): void
    {
        $this->wasRun = true;
    }

    public function wasRun(): bool
    {
        return $this->wasRun;
    }
}

class TestCaseTest extends TestCase
{
    public function testRunning(): void
    {
        $test = new WasRun('testMethod');
        assert(!$test->wasRun());
        $test->run();
        assert($test->wasRun());
    }
}

ini_set('assert.active', '1');
ini_set('assert.exception', '1');

(new TestCaseTest('testRunning'))->run();
