<?php
/**
 * Created by PhpStorm.
 * User: Wangjian
 * Date: 2017/7/7
 * Time: 16:47
 */

namespace Purify;

class JudgeTest extends \PHPUnit_Framework_TestCase
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
        $j = Purify::judge($this->input);
        $this->assertTrue($j()->accept());
    }

    /**
     * @test
     */
    public function reject_should_work()
    {
        $j = Purify::judge($this->input);
        $this->assertFalse($j()->reject());
    }

    /**
     * @test
     */
    public function required_pass_should_work()
    {
        $j = Purify::judge($this->input);

        $this->assertTrue($j('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i')->required());
        $this->assertTrue($j('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i')->req());
    }

    /**
     * @test
     * @dataProvider invalidKeys
     * @param $key
     */
    public function required_fail_should_work($key)
    {
        $j = Purify::judge($this->input);
        $this->assertFalse($j('a', $key)->required());
    }

    /**
     * @test
     */
    public function eliminate_pass_should_work()
    {
        $j = Purify::judge($this->input);

        $this->assertTrue($j('j', 'k', 'l', 'm', 'n')->eliminate());
        $this->assertTrue($j('j', 'k', 'l', 'm', 'n')->elim());
    }

    /**
     * @test
     * @dataProvider validKeys
     * @param $key
     */
    public function eliminate_fail_should_work($key)
    {
        $j = Purify::judge($this->input);
        $this->assertFalse($j('j', $key)->eliminate());
    }
}
