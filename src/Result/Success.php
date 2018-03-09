<?php namespace MattyRad\Support\Result;

abstract class Success extends Base
{
    const HTTP_OK_RESPONSE_CODE = 200;

    public function isSuccess()
    {
        return true;
    }

    public function isFailure()
    {
        return false;
    }

    public function getReason()
    {
        return null;
    }

    public function getStatusCode()
    {
        return self::HTTP_OK_RESPONSE_CODE;
    }
}
