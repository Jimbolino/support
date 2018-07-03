<?php namespace MattyRad\Support\Test\Unit\Result;

abstract class SuccessTest extends BaseTest
{
    protected $result;

    public function test_isSuccess()
    {
        $expected = true;
        $actual = $this->result->isSuccess();

        $this->assertEquals($expected, $actual);
    }

    public function test_isFailure()
    {
        $expected = false;
        $actual = $this->result->isFailure();

        $this->assertEquals($expected, $actual);
    }

    public function test_getReason()
    {
        $expected = '';
        $actual = $this->result->getReason();

        $this->assertEquals($expected, $actual);
    }
}