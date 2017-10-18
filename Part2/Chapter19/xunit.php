<?php
declare(strict_types=1);

namespace Acme\Chapter19;

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

    protected function setUp(): void
    {
        // nop
    }

    public function run(): void
    {
        $this->setUp();
        call_user_func([$this, $this->name]);
    }
}

class WasRun extends TestCase
{
    /** @var  bool */
    private $wasRun;

    /** @var  bool */
    private $wasSetup;

    protected function setUp(): void
    {
        $this->wasRun = false;
        $this->wasSetup = true;
    }

    public function testMethod(): void
    {
        $this->wasRun = true;
    }

    public function wasRun(): bool
    {
        return $this->wasRun;
    }

    public function wasSetup(): bool
    {
        return $this->wasSetup;
    }
}

class TestCaseTest extends TestCase
{
    /** @var  WasRun */
    private $test;

    protected function setup(): void
    {
        $this->test = new WasRun('testMethod');
    }

    public function testRunning(): void
    {
        $this->test->run();
        assert($this->test->wasRun());
    }

    public function testSetup(): void
    {
        $this->test->run();
        assert($this->test->wasSetup());
    }
}

ini_set('assert.active', '1');
ini_set('assert.exception', '1');

(new TestCaseTest('testRunning'))->run();
(new TestCaseTest('testSetup'))->run();
