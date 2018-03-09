<?php namespace MattyRad\Support\Result;

abstract class Failure extends Base
{
    const DEFAULT_HTTP_ERROR = 500;

    protected static $message;
    protected static $http_code;

    abstract public function getContext(); //: array

    public function isSuccess()
    {
        return false;
    }

    public function isFailure()
    {
        return true;
    }

    public function getReason()
    {
        return $this->formatMessage(static::$message, $this->getContext());
    }

    public function getStatusCode()
    {
        return static::$http_code ?: self::DEFAULT_HTTP_ERROR;
    }

    private function formatMessage($message, array $context)
    {
        return sprintf('%s; %s', $message, json_encode($context));
    }
}
