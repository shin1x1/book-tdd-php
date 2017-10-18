<?php
declare(strict_types=1);

namespace Acme\Chapter20;

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
        $this->tearDown();
    }

    protected function tearDown(): void
    {
        // nop
    }
}

class WasRun extends TestCase
{
    /** @var  string */
    private $log;

    protected function setUp(): void
    {
        $this->log = 'setUp ';
    }

    public function testMethod(): void
    {
        $this->log .= 'testMethod ';
    }

    public function log(): string
    {
        return $this->log;
    }

    protected function tearDown(): void
    {
        $this->log .= 'tearDown ';
    }
}

class TestCaseTest extends TestCase
{
    public function testTemplateMethod(): void
    {
        $test = new WasRun('testMethod');
        $test->run();
        assert('setUp testMethod tearDown ' === $test->log());
    }
}

ini_set('assert.active', '1');
ini_set('assert.exception', '1');

(new TestCaseTest('testTemplateMethod'))->run();
