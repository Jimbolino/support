# Sunlight Support

## Conformation
### Instantiate objects from an unsorted array

```php
use Sunlight\Support\Conformation;

class Sample {
    use Conformation;

    public function __construct(
        string $str,
        int $int,
        bool $bool,
        float $float,
        string $optional1 = '',
        int $optional2 = 1
    ) {
        $this->str = $str;
        $this->int = $int;
        $this->bool = $bool;
        $this->float = $float;
        $this->optional1 = $optional1;
        $this->optional2 = $optional2;
    }
}
```

```php
$sample = Sample::fromArray(['str' => 'example', 'int' => 1, 'bool' => false, 'float' => 2.0 ]);
```