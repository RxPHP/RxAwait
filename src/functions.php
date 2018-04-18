<?php

namespace Rx;

use React\EventLoop\LoopInterface;

const AWAIT_CANCEL = 'cancel';

/**
 * Wait until observable completes.
 *
 * @param Observable|ObservableInterface $observable
 * @param LoopInterface $loop
 * @return \Generator
 */
function await(Observable $observable, LoopInterface $loop = null): \Generator
{
    $completed = false;
    $results   = [];
    $loop      = $loop ?: \EventLoop\getLoop();

    $disposable = $observable->subscribe(
        function ($value) use (&$results, $loop) {
            $results[] = $value;

            $loop->stop();
        },
        function ($e) use (&$completed) {
            $completed = true;
            throw $e;
        },
        function () use (&$completed) {
            $completed = true;
        });

    while (!$completed) {

        $loop->run();

        foreach ($results as $result) {
            $cancel = yield $result;

            if ($cancel === AWAIT_CANCEL) {
                $disposable->dispose();
                return;
            }
        }

        $results = [];
    }
}

function cancelAwait(\Generator $generator)
{
    $generator->send(AWAIT_CANCEL);
}
