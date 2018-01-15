<?php namespace Sunlight\Support;

use ReflectionClass;
use ReflectionParameter;
use InvalidArgumentException;

trait Conformation {
    public static function fromArray(array $arr)
    {
        $parts = static::filterAndOrderSource($arr);

        return new self(...$parts);
    }

    private static function filterAndOrderSource(array $source)
    {
        $constructor_params = static::getConstructorParameterNames();
        $filled = static::padded($source);
        $filtered = static::arrayOnly($filled, $constructor_params);
        $ordered = static::order($constructor_params, $filled);

        if (array_count_values(array_keys($filtered)) != array_count_values($constructor_params)) {
            throw new InvalidArgumentException(
                static::selfName() . ' missing key(s): ' . implode(', ', array_diff($constructor_params, array_keys($filtered)))
            );
        }

        return array_values($ordered);
    }

    private static function getConstructorParameterNames()
    {
        $params = (new ReflectionClass(__CLASS__))->getConstructor()->getParameters();

        return array_map(function (ReflectionParameter $param) {
            return $param->name;
        }, $params);
    }

    private static function getRequiredConstructorParameters()
    {
        $params = (new ReflectionClass(__CLASS__))->getConstructor()->getParameters();

        $required_params = array_filter(function ($param) {
            return ! $param->isOptional();
        }, $params);

        return array_map(function (ReflectionParameter $param) {
            return $param->name;
        }, $required_params);
    }

    private static function getOptionalConstructorParameters()
    {
        $params = (new ReflectionClass(__CLASS__))->getConstructor()->getParameters();

        return array_filter($params, function (ReflectionParameter $param) {
            return $param->isOptional();
        });
    }

    private static function padded(array $source)
    {
        $optional_params = static::getOptionalConstructorParameters();

        foreach ($optional_params as $optional_param) {
            if (! array_key_exists($optional_param->getName(), $source)) {
                $source[$optional_param->getName()] = $optional_param->getDefaultValue();
            }
        }

        return $source;
    }

    private static function arrayOnly(array $from, array $to)
    {
        return array_intersect_key($from, array_flip((array) $to));
    }

    private static function order(array $keys_to_conform_to, array $from)
    {
        return array_merge(array_flip($keys_to_conform_to), $from);

        //$conform_array = array_fill_keys(array_flip($keys_to_conform_to), null);

        //return array_diff_assoc($from, $conform_array);
    }

    private static function selfName()
    {
        return (new ReflectionClass(__CLASS__))->getShortName();
    }
}