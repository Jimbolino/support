<?php namespace MattyRad\Support\Result;

use Illuminate\Support\Arr;

class Success extends Base implements \JsonSerializable
{
    private $response_data;

    public function __construct(array $response_data)
    {
        $this->response_data = $response_data;
    }

    final public function isSuccess()
    {
        return true;
    }

    final public function isFailure()
    {
        return false;
    }

    final public function getReason()
    {
        return null;
    }

    public function has($offset)
    {
        return Arr::has($this->response_data, $offset);
    }

    public function get($offset)
    {
        return Arr::get($this->response_data, $offset);
    }

    public function toArray()//: array
    {
        return $this->response_data;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
