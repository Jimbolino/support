# MattyRad Support

[![Build Status](https://api.travis-ci.org/MattyRad/support.png?branch=master])(https://travis-ci.org/MattyRad/support)

## Installation

`composer require mattyrad/support`

## Usage
### Conformation Trait
#### Instantiate objects from an unsorted array

```php
use MattyRad\Support\Conformation;

class Sample {
    use Conformation;

    private $arg1;
    private $arg2;
    private $arg3;
    private $arg4;
    private $optional1;
    private $optional2;

    private function __construct(
        string $arg1,
        int $arg2,
        bool $arg3,
        float $arg4,
        string $optional1 = '',
        int $optional2 = 1
    ) {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->arg3 = $arg3;
        $this->arg4 = $arg4;
        $this->optional1 = $optional1;
        $this->optional2 = $optional2;
    }
}
```

```php
$sample = Sample::fromArray([
    'optional2' => 777,
    'arg2' => 1,
    'arg3' => false,
    'arg1' => 'example',
    'arg4' => 2.0,
]);
```