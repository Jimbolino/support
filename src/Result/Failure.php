<?php namespace MattyRad\Support\Result;

abstract class Failure extends Base
{
    protected static $message;

    abstract public function getContext(); //: array

    final public function isSuccess()
    {
        return false;
    }

    final public function isFailure()
    {
        return true;
    }

    public function getReason()
    {
        return $this->formatMessage(static::$message, $this->getContext());
    }

    private function formatMessage($message, array $context)
    {
        return sprintf('%s; %s', $message, json_encode($context));
    }

    public function toException()//: \Exception
    {
        return new \Exception(static::$message);
    }

    public function __call($name, $arguments)
    {
        throw $this->toException();
    }
}
