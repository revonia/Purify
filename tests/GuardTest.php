<?php

/**
 * Created by PhpStorm.
 * User: Wangjian
 * Date: 2017/7/6
 * Time: 9:31
 */

namespace tests\Purify;

use Purify\Exception\PurifyException;
use Purify\Exception\RejectPurifyException;
use Purify\Purify;

class GuardTest extends \PHPUnit_Framework_TestCase
{
    public $input = array(
        //valid
        'a' => 1,
        'b' => 'b',
        'c' => '0',
        'd' => 'false',
        'e' => array(0),
        'f' => 'true',
        'g' => false,
        'h' => true,
        'i' => 0,
        //invalid
        'j' => null,
        'k' => '',
        'l' => '  ',
        'm' => array(),
        'n' => "\t"
    );

    public function validKeys()
    {
        return array(
            array('a'),
            array('b'),
            array('c'),
            array('d'),
            array('e'),
            array('f'),
            array('g'),
            array('h'),
            array('i')
        );
    }

    public function invalidKeys()
    {
        return array(
            array('j'),
            array('k'),
            array('l'),
            array('m'),
            array('n')
        );
    }

    /**
     * @test
     */
    public function accept_should_work()
    {
        $g = Purify::guard($this->input);
        $g()->accept();
    }

    /**
     * @test
     */
    public function reject_should_work()
    {
        $g = Purify::guard($this->input);
        $this->setExpectedException('Purify\Exception\PurifyException');
        $g()->reject();
    }

    /**
     * @test
     */
    public function valid_pass_should_work()
    {
        $g = Purify::guard($this->input);
        $g('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i')->valid();
    }

    /**
     * @test
     * @dataProvider invalidKeys
     * @param $key
     */
    public function valid_fail_should_work($key)
    {
        $g = Purify::guard($this->input);
        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', $key)->valid();
    }

    /**
     * @test
     */
    public function invalid_pass_should_work()
    {
        $g = Purify::guard($this->input);
        $g('j', 'k', 'l', 'm', 'n')->invalid();
    }

    /**
     * @test
     * @dataProvider validKeys
     * @param $key
     */
    public function invalid_fail_should_work($key)
    {
        $g = Purify::guard($this->input);
        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('j', $key)->invalid();
    }

    /**
     * @test
     */
    public function eq_should_work()
    {
        $g = Purify::guard($this->input);
        $g('a', 'b')->eq('1', 'b');

        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'j')->eq(0, null);
    }

    /**
     * @test
     */
    public function neq_should_work()
    {
        $g = Purify::guard($this->input);
        $g('a', 'b')->neq(0, 1);

        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'j')->neq(1, null);
    }

    /**
     * @test
     */
    public function ieq_should_work()
    {
        $g = Purify::guard($this->input);
        $g('a', 'b')->ieq(1, 'b');

        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'j')->ieq('1', null);
    }

    /**
     * @test
     */
    public function nieq_should_work()
    {
        $g = Purify::guard($this->input);
        $g('a', 'j')->nieq('1', false);

        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'j')->nieq(1, null);
    }

    /**
     * @test
     */
    public function between_should_work()
    {
        $i = array('a' => 2, 'b' => 3);
        $g = Purify::guard($i);
        $g('a', 'b')->between(1, 4);
        $g('a', 'b')->some->between(2, 4);

        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'b')->between(2, 4);
    }

    /**
     * @test
     */
    public function some_each_eq_should_work()
    {
        $i = array('a' => 'foo', 'b' => 'bar');
        $g = Purify::guard($i);
        $g('a', 'b')->some->each->eq('bar', 'xxx');
        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'b')->some->each->eq('yyy', 'xxx');
    }

    /**
     * @test
     */
    public function beside_should_work()
    {
        $i = array('a' => 1, 'b' => 7);
        $g = Purify::guard($i);
        $g('a', 'b')->beside(0, 8);
        $g('a', 'b')->some->beside(0, 5);

        $this->setExpectedException('Purify\Exception\PurifyException');
        $g('a', 'b')->between(2, 4);
    }

}
