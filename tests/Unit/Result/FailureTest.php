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
    public function test_toExceptionMessage()
    {
        $e = $this->result->toException();

        $this->assertInstanceOf(\Exception::class, $e);
    }

    public function test_accessorsThrowExceptions()
    {
        $this->expectException(\Exception::class);

        $this->result->getImportantData();
    }
}
