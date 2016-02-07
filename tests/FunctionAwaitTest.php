<?php

namespace Rx\React\Tests;

use Rx\Functional\FunctionalTestCase;
use Rx\Observable;

class FunctionAwaitTest extends FunctionalTestCase
{

    /**
     * @test
     * @expectedException \Exception
     */
    public function await_rejected()
    {
        $observable = Observable::error(new \Exception());

        $generator = \Rx\await($observable);

        foreach ($generator as $item) {
            $this->assertTrue(false); //should never get here
        }

    }

    /**
     * @test
     */
    public function await_multiple()
    {
        $array = [1, 2, 3];

        $observable = Observable::fromArray($array);

        $generator = \Rx\await($observable);

        $result = [];
        foreach ($generator as $item) {
            $result[] = $item;
        }

        $this->assertEquals($array, $result);
        $this->assertEquals(count($result), 3);

    }


    /**
     * @test
     */
    public function await_default_timeout()
    {

        $start = microtime(true);

        $observable = Observable::never()->takeUntil(Observable::timer(2000));

        $generator = \Rx\await($observable);

        foreach ($generator as $item) {

        }

        $totalTime = microtime(true) - $start;

        $this->assertEquals('2', round($totalTime));

    }

}
