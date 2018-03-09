<?php namespace MattyRad\Support\Test\Unit\Result\Sample;

use MattyRad\Support\Test;
use MattyRad\Support\Result;

class Success extends Result\Success {}

class SuccessTest extends Test\Unit\Result\SuccessTest
{
    protected $result;

    public function setUp()
    {
        $this->result = new Success;
    }

    public function tearDown()
    {
        unset($this->result);
    }
}