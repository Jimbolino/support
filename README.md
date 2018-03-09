# MattyRad Support

![Build Status](https://api.travis-ci.org/MattyRad/support.png?branch=master) ![Code Coverage](https://img.shields.io/codecov/c/github/mattyrad/support.svg)

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

### Result Objects
#### It's very common to require extensible result objects for success and failures, particularly for APIs.

```php
use MattyRad\Support\Result;

class ApiRequestRejected extends Result\Failure
{
    protected static $message = 'Api rejected the request';
    protected static $status_code = 422;

    private $reason;

    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    public function getReason()
    {
        return $this->reason;
    }
}

class PurchasedWidged extends Success
{
    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    public function getWidget()
    {
        return $this->reason;
    }
}
```

```php
public function purchaseWidget(): Result
{
    if ($this->notEnoughCredit()) {
        return new Result\Failure\ApiRequestRejected('Not enough money!');
    }

    $this->credit_api->charge(...);

    $widget = $this->makeWidget();

    return new Result\Success\PurchasedWidged($widget)
}
```

```php
$result = $this->thing->purchaseWidget();

if ($result->isFailure()) {
    throw new Exception\PurchaseFailed($result->getReason());
}

return $result->getWidget();
```