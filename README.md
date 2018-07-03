# MattyRad Support

![Build Status](https://api.travis-ci.org/MattyRad/support.png?branch=master) ![Code Coverage](https://img.shields.io/codecov/c/github/mattyrad/support.svg)

## Installation

`composer require mattyrad/support`

## Table of Contents

- [Conformation Trait](#conformation-trait)
- [Result Objects](#result-objects)

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

It's very common to require extensible result objects for success and failures, particularly for APIs.

#### Defining Results

You can hit the ground running with generic success results

```php
$json_response = $this->api->request('something');

$response_data = json_decode($json_response->getBody(), true); // ['user' => ['name' => 'John', 'email' => 'user@example.com']];

$result = new \MattyRad\Support\Result\Success($response_data);

$result->get('user.email'); // dot syntax enabled
$result->isSuccess(); // true
$result->isFailure(); // false
```

But it's better to extend the Success object with more specific results, particularly because unit testing will be easier.
```php
class WidgetPurchased extends Result\Success
{
    public function __construct(Widget $widget)
    {
        $this->widget = $widget;
    }

    public function getWidget(): Widget
    {
        return $this->widget;
    }
}

$result = new Result\Success\WidgetPurchased($widget);

$result->getWidget(); // Widget object
$result->isSuccess(); // true
$result->isFailure(); // false
```

Failure results are required to be a bit more specific

```php
namespace MattyRad\Support\Result\Stripe;

use MattyRad\Support\Result;

class ChargeFailed extends Result\Failure
{
    protected static $message = 'Stripe charge failed, delinquent card';

    public function __construct($last_four_digits)
    {
        $this->last_four_digits = $last_four_digits;
    }

    public function getContext()
    {
        return ['last_four_digits' => $this->last_four_digits];
    }
}

$result->get('widget.name'); // throws exception with message 'Stripe charge failed, delinquent card'
$result->getWidget(); // also throws exception with message 'Stripe charge failed, delinquent card'
$result->isSuccess(); // false
$result->isFailure(); // true
$result->getReason(); // 'Stripe charge failed, delinquent card; {"last_four_digits":"1234"}'
```

#### Creating and Using Results

```php
use MattyRad\Support\Result;
use Stripe;

function purchaseWidget($user, string $widget_name): Result
{
    if ($existing_widget = $this->db->getWidgetByName($widget_name)) {
        return new Result\Failure\WidgetExists($existing_widget);
    }

    try {
        $user->charge(100); // API call, this could be any interface to stripe
    } catch (Stripe\Error\Card $e) {
        return new Result\Failure\Stripe\ChargeFailed($e->getLastFour()); // pretend that getLastFour exists
    }

    $widget = new Widget($widget_name);

    return new Result\Success\WidgetPurchased($widget);
}
```

#### Consuming Results

You can check for a failure manually

```php
$result = purchaseWidget(Auth::user(), 'my_cool_new_widget');

if ($result->isFailure()) {
    return new JsonResponse(['error' => $result->getReason()], 422);
}

return new JsonResponse($result->getWidget());
```

Or you can catch the exception for failures

```php
$result = purchaseWidget(Auth::user(), 'my_cool_new_widget');

try {
    $widget = $result->getWidget();
} catch (\Exception $e) {
    // handle it. you can override the toException function in the Failure result if
    // you want to catch a more specific exception (which you should)
}

return new JsonResponse($widget);
```

Or don't worry about checking the result at all, and use your Exception Handler to deal with errors

```php
$result = purchaseWidget(Auth::user(), 'my_cool_new_widget');

return new Response($result->getWidget());
```

```php
// Your exception handler

public function render($request, Exception $e)
{
    if ($e instanceof SpecificException) {
        // Override the toException method in a failure to write a specific exception
        return new JsonResponse(['error' => $e->getMessage()], 422);
    }
}
```

Don't forget that Exception Handlers usually handle a number of exceptions by default, which we can use to our advantage

```php
public function toException()
{
    $response = new JsonResponse(['error' => static::$message], 422);

    return new HttpResponseException($response);
}
```

Now our controller has error handling built in, and we can focus on the success cases

```php
$result = purchaseWidget(Auth::user(), 'my_cool_new_widget');

return new JsonResponse($result->getWidget());
```

One could argue that this obscures how error handling works, but we've gained clean code and smaller units. Unit testing across the entire stack should now be trivial. For example:

```php
/**
 * @dataProvider resultProvider
 */
public function test_purchase(Result $purchase_result, $expected_data)
{
    $this->dependency->purchaseWidget(Argument::type(User::class), Argument::type('string'))
        ->willReturn($result);

    $response = $this->controller->purchase(...);

    $this->assertArrayContains(json_encode($expected_data), $response->getContent());
}

public function resultProvider()
{
    return [
        [
            new Result\Success\WidgetPurchased($widget = new Widget('my_cool_new_widget')),
            $widget
        ],
        [
            new Result\Failure\Stripe\ChargeFailed('1234'),
            ['error' => 'Stripe charge failed, delinquent card'],
        ],
        [
            new Result\Failure\WidgetExists(new Widget(['name' => $name = 'abc123'])),
            ['error' => "Sorry, a widget with the name '$name' already exists"],
        ],
    ];
}
```