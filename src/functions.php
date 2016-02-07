<?php

namespace Rx;

use Rx\Observer\CallbackObserver;
use Rx\Scheduler\EventLoopScheduler;

/**
 * Wait until observable completes.
 *
 * @param Observable|ObservableInterface $observable
 * @param EventLoopScheduler $scheduler
 * @return \Generator
 */
function await(Observable $observable, EventLoopScheduler $scheduler = null)
{

    $completed = false;
    $results   = [];
    $scheduler = $scheduler ?: new EventLoopScheduler(\EventLoop\getLoop());

    $observable
        ->subscribe(new CallbackObserver(
            function ($value) use (&$results, &$results) {
                $results[] = $value;

                \EventLoop\getLoop()->stop();
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

        \EventLoop\getLoop()->run();

        foreach ($results as $result) {
            yield $result;
        }

        $results = [];
    }
}
