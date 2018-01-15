<?php namespace Sunlight\Support;

use ReflectionClass;
use InvalidArgumentException;

trait Conformation {
    public static function fromArray(array $arr)
    {
        $parts = static::filterAndOrderSource($arr);

        return new self(...$parts);
    }

    private static function filterAndOrderSource(array $source)
    {
        $constructor_params = static::getConstructorParameters();
        $filtered = static::only($source, $constructor_params);
        $ordered = static::order($constructor_params, $filtered);

        if (array_count_values(array_keys($filtered)) != array_count_values($constructor_params)) {
            throw new InvalidArgumentException(
                static::selfName() . ' missing key(s): ' . implode(', ', array_diff($constructor_params, array_keys($filtered)))
            );
        }

        return array_values($ordered);
    }

    private static function getConstructorParameters()
    {
        $params = (new ReflectionClass(__CLASS__))->getConstructor()->getParameters();

        return array_map(function ($value) {
            return $value->name;
        }, $params);
    }

    private static function only(array $from, array $to)
    {
        return array_intersect_key($from, array_flip((array) $to));
    }

    private static function order(array $keys_to_conform_to, array $from)
    {
        return array_merge(array_flip($keys_to_conform_to), $from);
    }

    private static function selfName()
    {
        return (new ReflectionClass(__CLASS__))->getShortName();
    }
}
