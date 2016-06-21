<?php

namespace Rx;

use React\EventLoop\LoopInterface;
use Rx\Observer\CallbackObserver;
use Rx\Scheduler\EventLoopScheduler;

/**
 * Wait until observable completes.
 *
 * @param Observable|ObservableInterface $observable
 * @param LoopInterface $loop
 * @return \Generator
 */
function await(Observable $observable, LoopInterface $loop = null)
{

    $completed = false;
    $results   = [];
    $loop      = $loop ?: \EventLoop\getLoop();
    $scheduler = new EventLoopScheduler($loop);

    $observable->subscribe(new CallbackObserver(
        function ($value) use (&$results, &$results, $loop) {
            $results[] = $value;

            $loop->stop();
        },
        function ($e) use (&$completed) {
            $completed = true;
            throw $e;
        },
        function () use (&$completed) {
            $completed = true;
        }

    ), $scheduler);

    while (!$completed) {

        $loop->run();

        foreach ($results as $result) {
            yield $result;
        }

        $results = [];
    }
}
