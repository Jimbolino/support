<?php namespace MattyRad\Support\Test\Unit\Result;

abstract class FailureTest extends BaseTest
{
    protected $result;

    public function test_isSuccess()
    {
        $expected = false;
        $actual = $this->result->isSuccess();

        $this->assertEquals($expected, $actual);
    }

    public function test_isFailure()
    {
        $expected = true;
        $actual = $this->result->isFailure();

        $this->assertEquals($expected, $actual);
    }

    public function test_getStatusCode()
    {
        $expected = 500;
        $actual = $this->result->getStatusCode();

        $this->assertEquals($expected, $actual);
    }
}