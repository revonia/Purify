<?php

namespace Purify\Condition;

use Purify\Context;

class Basic extends Condition
{
    public static function accept()
    {
        return true;
    }

    public static function reject()
    {
        return false;
    }

    /**
     * @param $value
     * @return bool
     */
    public static function valid($value)
    {
        if (is_string($value) && trim($value) === '') {
            return false;
        }

        if (is_array($value) && empty($value)) {
            return false;
        }

        return $value !== null;
    }

    /**
     * @param $value
     * @return bool
     */
    public static function invalid($value)
    {
        return !self::valid($value);
    }

    public static function equal($value, $key, $symbol)
    {
        return $value == $symbol;
    }

    public static function identicallyEqual($value, $key, $symbol)
    {
        return $value === $symbol;
    }

    public static function notEqual($value, $key, $symbol)
    {
        return $value != $symbol;
    }

    public static function notIdenticallyEqual($value, $key, $symbol)
    {
        return $value !== $symbol;
    }

    public static function between($value, $key, $symbol, Context $ctx)
    {
        return $ctx->symbols[0] < $value && $value < $ctx->symbols[1];
    }

    public static function beside($value, $key, $symbol, Context $ctx)
    {
        return $value < $ctx->symbols[0] || $value > $ctx->symbols[1];
    }

    public static function example($value, $key, $symbol, $ctx)
    {

    }

}