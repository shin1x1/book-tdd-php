<?php
declare(strict_types=1);

namespace Acme\Chapter22;

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

    public function run(): TestResult
    {
        $result = new TestResult();
        $result->testStarted();

        $this->setUp();

        try {
            call_user_func([$this, $this->name]);
        } catch (\Exception $e) {
            $result->testFailed();
        }

        $this->tearDown();

        return $result;
    }

    protected function tearDown(): void
    {
        // nop
    }
}

class TestResult
{
    /** @var int */
    private $runCount;

    /** @var int */
    private $errorCount;

    public function __construct()
    {
        $this->runCount = 0;
        $this->errorCount = 0;
    }

    public function testStarted(): void
    {
        $this->runCount++;
    }

    public function summary(): string
    {
        return sprintf('%d run, %d failed', $this->runCount, $this->errorCount);
    }

    public function testFailed()
    {
        $this->errorCount++;
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

    public function testBrokenMethod(): void
    {
        throw new \Exception();
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

    public function testResult(): void
    {
        $test = new WasRun('testMethod');
        $result = $test->run();
        assert('1 run, 0 failed' === $result->summary());
    }

    public function testFailedResult(): void
    {
        $test = new WasRun('testBrokenMethod');
        $result = $test->run();
        assert('1 run, 1 failed' === $result->summary());
    }

    public function testFailedResultFormatting(): void
    {
        $result = new TestResult();
        $result->testStarted();
        $result->testFailed();
        assert('1 run, 1 failed' === $result->summary());
    }
}

ini_set('assert.active', '1');
ini_set('assert.exception', '1');

(new TestCaseTest('testTemplateMethod'))->run();
(new TestCaseTest('testResult'))->run();
(new TestCaseTest('testFailedResult'))->run();
(new TestCaseTest('testFailedResultFormatting'))->run();
