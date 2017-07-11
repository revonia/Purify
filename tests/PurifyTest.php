<?php

/**
 * Created by PhpStorm.
 * User: Wangjian
 * Date: 2017/7/6
 * Time: 9:31
 */

namespace tests\Purify;

use Purify\Purify;

class PurifyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function generate_should_success()
    {
        $input = array(
            'a' => 1,
            'b' => 'b',
            'c' => false,
        );
        $guard = Purify::guard($input);
        $judge = Purify::judge($input);

        $_ = Purify::extractor();

        list($guard, $judge) = Purify::guard_judge($input);

        $formatter = Purify::formatter($input);
    }

    /**
     * @test
     */
    public function push_should_success()
    {

        $guard = Purify::guard(array());

        $keys = $guard('a', 'b')->push('c')->keys;
        $this->assertEquals(array('a', 'b', 'c'), $keys);
    }

    /**
     * @test
     */
    public function set_keys_should_success()
    {
        $input = array(
            'a' => 1,
            'b' => 'aaa',
            'c' => true,
        );

        $g = Purify::guard($input);

        $this->assertEquals(array('a', 'b'), $g('a', 'b')->keys);
    }

    /**
     * @test
     */
    public function is_extractor_should_work()
    {
        $_ = Purify::extractor();
        $this->assertTrue(Purify::isExtractor($_));
    }

    /**
     * @test
     */
    public function wrap_by_extractor_should_success()
    {
        $_ = Purify::extractor();
        $e = $_(array());
        $this->assertInstanceOf('\\Purify\\Input\\Extracted', $e);
    }

    /**
     * @test
     */
    public function extractor_should_work()
    {
        $_ = Purify::extractor();
        $g = Purify::guard(array('a' => 1, 'b' => 2));
        $keys = $g($_(array('a', 'b')))->keys;
        $this->assertEquals(array('a', 'b'), $keys);
    }

    /**
     * @test
     */
    public function placeholder_should_work()
    {
        $_ = Purify::extractor();
        $g = Purify::guard(array('a' => 1, 'b' => 2));
        $g($_, 'b')->eq(3, 2);
        $g('c', 'b')->eq($_, 2);
    }
}
