<?php namespace MattyRad\Support\Test\Unit;

use MattyRad\Support\Conformation;

class Sample
{
    use Conformation;

    private $str;
    private $int;
    private $bool;
    private $float;
    private $optional1;
    private $optional2;

    private function __construct($str, $int, $bool, $float, $optional1 = '', $optional2 = 2)
    {
        $this->str = $str;
        $this->int = $int;
        $this->bool = $bool;
        $this->float = $float;
        $this->optional1 = $optional1;
        $this->optional2 = $optional2;
    }

    public function toArray()
    {
        return [
            'str' => $this->str,
            'int' => $this->int,
            'bool' => $this->bool,
            'float' => $this->float,
            'optional1' => $this->optional1,
            'optional2' => $this->optional2,
        ];
    }
}