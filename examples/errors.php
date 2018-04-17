<?php

require __DIR__ . '/../vendor/autoload.php';
$loop = \EventLoop\getLoop();
Rx\Scheduler::setDefaultFactory(function() use($loop){
    return new Rx\Scheduler\EventLoopScheduler($loop);
});

$source = \Rx\Observable::error(new Exception('some error'));

$generator = \Rx\await($source);

try {
    foreach ($generator as $item) {
        echo $item, PHP_EOL;
    }
} catch (\Exception $e) {
    echo "caught error: ", $e->getMessage();
}





