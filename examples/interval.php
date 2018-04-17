<?php

require __DIR__ . '/../vendor/autoload.php';
$loop = \EventLoop\getLoop();
Rx\Scheduler::setDefaultFactory(function() use($loop){
    return new Rx\Scheduler\EventLoopScheduler($loop);
});

$source = \Rx\Observable::interval(1000);

$generator = \Rx\await($source);


foreach ($generator as $item) {
    echo $item, PHP_EOL;
}

echo "DONE";
