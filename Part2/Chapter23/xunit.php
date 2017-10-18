<?php
declare(strict_types=1);

namespace Acme\Chapter23;

class TestCase
{
    /** @var  string */
    private $name;

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

    public function run(TestResult $result): void
    {
        $result->testStarted();

        $this->setUp();

        try {
            call_user_func([$this, $this->name]);
        } catch (\Exception $e) {
            $result->testFailed();
        }

        $this->tearDown();
    }

    protected function tearDown(): void
    {
        // nop
    }
}

class TestResult
{
    /** @var int */
    private $runCount = 0;

    /** @var int */
    private $errorCount = 0;

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

class TestSuite
{
    /** @var  array|TestCase[] */
    private $tests = [];

    public function add(TestCase $testCase): void
    {
        $this->tests[] = $testCase;
    }

    public function run(TestResult $result): void
    {
        foreach ($this->tests as $test) {
            $test->run($result);
        }
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
    /** @var  TestResult */
    private $result;

    protected function setUp(): void
    {
        parent::setUp();

        $this->result = new TestResult();
    }

    public function testTemplateMethod(): void
    {
        $test = new WasRun('testMethod');
        $test->run($this->result);
        assert('setUp testMethod tearDown ' === $test->log());
    }

    public function testResult(): void
    {
        $test = new WasRun('testMethod');
        $test->run($this->result);
        assert('1 run, 0 failed' === $this->result->summary());
    }

    public function testFailedResult(): void
    {
        $test = new WasRun('testBrokenMethod');
        $test->run($this->result);
        assert('1 run, 1 failed' === $this->result->summary());
    }

    public function testFailedResultFormatting(): void
    {
        $this->result->testStarted();
        $this->result->testFailed();
        assert('1 run, 1 failed' === $this->result->summary());
    }

    public function testSuite(): void
    {
        $suite = new TestSuite();
        $suite->add(new WasRun('testMethod'));
        $suite->add(new WasRun('testBrokenMethod'));

        $suite->run($this->result);
        assert('2 run, 1 failed' === $this->result->summary());
    }
}

ini_set('assert.active', '1');
ini_set('assert.exception', '1');

$suite = new TestSuite();
$suite->add(new TestCaseTest('testTemplateMethod'));
$suite->add(new TestCaseTest('testResult'));
$suite->add(new TestCaseTest('testFailedResult'));
$suite->add(new TestCaseTest('testFailedResultFormatting'));
$suite->add(new TestCaseTest('testSuite'));

$result = new TestResult();
$suite->run($result);
echo $result->summary(), PHP_EOL;
assert('5 run, 0 failed' === $result->summary());


