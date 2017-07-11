<?php
namespace Purify;

use Purify\Input\Immutable;
use Purify\Input\Extracted;
use Purify\Input\Symbols;

class Purify
{
    protected static $extractor = null;

    public static function isExtractor(&$extractor)
    {
        return (self::$extractor !== null) && ($extractor === self::$extractor);
    }

    public static function extractArgs(array &$args)
    {
        //array_merge可能造成运行缓慢，在这里将array_merge放在最后只执行一次
        $ret = array(array());
        $rest = array();
        foreach ($args as $arg) {
            if ($arg instanceof Extracted) {
                $ret[] = $arg->getContainer();
            } else {
                $rest[] = $arg;
            }
        }
        $ret[] = $rest;
        $ret = call_user_func_array('array_merge', $ret);
        return $ret;
    }

    /**
     * @param Immutable $immutable
     * @return \Closure
     */
    protected static function genGuard(Immutable $immutable)
    {
        return function () use ($immutable) {
            $guard = new Guard($immutable);
            //支持输入一个数组作为keys，或者使用参数列表作为keys
            $args = func_get_args();
            if (count($args) === 0) {
                $args = array_keys($immutable->getContainer());
            }
            $guard->keys = Purify::extractArgs($args);
            return $guard;
        };
    }

    /**
     * @param Immutable $immutable
     * @return \Closure
     */
    protected static function genJudge(Immutable $immutable)
    {
        return function () use ($immutable) {
            $judge = new Judge($immutable);
            //支持输入一个数组作为keys，或者使用参数列表作为keys
            $args = func_get_args();
            if (count($args) === 0) {
                $args = array_keys($immutable->getContainer());
            }
            $judge->keys = Purify::extractArgs($args);
            return $judge;
        };
    }

    /**
     * 失败抛出异常
     *
     * @param $input
     * @return \Closure
     */
    public static function guard($input)
    {
        return self::genGuard(new Immutable($input));
    }

    /**
     * 通过返回true，失败返回false
     *
     * @param $input
     * @return \Closure
     */
    public static function judge($input)
    {
        return self::genJudge(new Immutable($input));
    }

    /**
     * 同时生成 Guard 和 Judge，并共用一个$input，可以节省内存
     *
     * @example list($guard, $judge) = guard_judge($input);
     *
     * @param $input
     * @return array
     */
    public static function guard_judge($input)
    {
        $immutable = new Immutable($input);
        return array(self::genGuard($immutable), self::genJudge($immutable));
    }

    public static function formatter($input)
    {
        return function ($keys) {

        };
    }

    /**
     * 解析器 占位符
     *
     * @example $_ = Wrap::extract(); $p('a', 'b')->eq(1, $_); $p($_($someArray))->condition($_($someArray));
     *
     * @return \Closure|null
     */
    public static function extractor()
    {
        if (self::$extractor === null) {
            self::$extractor = function ($input, $map = false) {
                return new Extracted($input, $map);
            };
        }

        return self::$extractor;
    }

}