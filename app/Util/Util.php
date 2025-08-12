<?php
namespace App\Util;

use ReflectionException;

class Util
{
    /**
     * @throws ReflectionException
     */
    public static function getConstants($class): array
    {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->getConstants();
    }

    public static function getLocalConstants(): array
    {
        return static::getConstants(static::class);
    }

    public static function getConstantField($variable)
    {
        return constant(static::class . '::' . $variable);
    }
}
