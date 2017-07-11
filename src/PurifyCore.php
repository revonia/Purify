<?php
namespace Purify;

use Purify\Exception\PurifyException;
use Purify\Input\Extracted;
use Purify\Input\Symbols;

class PurifyCore
{

    protected static $conditionClass = array(
        'Purify\\Condition\\Basic',
    );

    /**
     * @param string $class
     */
    public static function registerConditionClass($class)
    {
        self::$conditionClass[] = $class;
    }

    public static function call($name, Context $ctx)
    {
        $ctx->remove_();
        foreach (self::$conditionClass as $class) {
            if (method_exists($class, $name)) {
                return self::doCondition(array($class, $name), $ctx);
            }
        }
        trigger_error("Condition $name not found.", E_USER_ERROR);
        return null;
    }

    /**
     * @param $callback
     * @param Context $ctx
     * @return array|bool
     * @throws PurifyException
     */
    protected static function doCondition($callback, Context $ctx)
    {
        $map = self::mapping($ctx);

        $bag = array();
        $pass = true;
        $some = $ctx->decorator('some');

        $symbol = null;
        foreach ($ctx->keys as $key) {
            if ($map !== null) {
                $symbol = $map[$key];
            }
            $value = $ctx->input[$key];
            $res = self::compute($callback, $value, $key, $symbol, $ctx);

            //条件不通过时立刻返回
            if ($res === $some && $ctx instanceof Judge) {
                return $res;
            }
            if (is_array($res)) {
                $pass = false;
                $bag[] = $res;
            }
        }

        if ($pass === false && $some === false) {
            $e = new PurifyException('Purify validate failed. message: ' . json_encode($bag));
            $e->setMessageBag($bag);
            throw $e;
        }

        return true;
    }

    /**
     * 当通过时，返回true，未通过时，返回false或者失败信息数组
     * @param $callback
     * @param $value
     * @param $key
     * @param $s
     * @param Context $ctx
     * @return array|bool
     */
    protected static function compute(&$callback, &$value, &$key, &$s, Context $ctx)
    {
        $msg = array(
            'condition' => $callback[1],
            'key' => $key,
            'value' => $ctx->input[$key],
            'symbol' => null,
        );

        if (empty($ctx->symbols)) {
            if (!call_user_func($callback, $value, $key, null, $ctx)) {
                if ($ctx instanceof Judge) {
                    return false;
                }
                return $msg;
            }
            return true;
        }

        //存在symbol的情况
        $msg['symbol'] = array();
        if ($s instanceof Symbols) {
            $symbols = $s->symbols;
        } else {
            $symbols = array($s);
        }

        foreach ($symbols as $symbol) {
            if (!call_user_func($callback, $value, $key, $symbol, $ctx)) {
                if ($ctx instanceof Judge) {
                    return false;
                }
                $msg['symbol'][] = $symbol;
            }
        }

        return empty($msg['symbol']) ? true : $msg;
    }

    protected static function mapping(Context $ctx)
    {
        if (!$ctx->mapping) return null;
        $m = count($ctx->keys);
        $n = count($ctx->symbols);

        $each = $ctx->decorator('each');

        if ($n === 0) {
            if ($each) trigger_error('Can not apply each decorator when symbols is empty.', E_USER_ERROR);
            return null;
        }
        //symbols和keys的个数不相等，没有用each修饰，且symbols个数大于1时报错。
        if ($n > 1 && $m !== $n && !$each) {
            trigger_error("$m keys assigned, but $n symbols given.", E_USER_ERROR);
        }

        $map = array();
        if ($n === 1) {  // m->1
            foreach ($ctx->keys as $key) $map[$key] = $ctx->symbols[0];
        } else if ($ctx->decorator('each')) {  // m->n
            foreach ($ctx->keys as $key) $map[$key] = new Symbols($ctx->symbols);
        } else {  //m->n m = n
            $map = array_combine($ctx->keys, $ctx->symbols);
        }

        return $map;
    }
}