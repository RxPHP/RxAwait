<?php

//This example requires https://github.com/RxPHP/RxHttp

require __DIR__ . '/../vendor/autoload.php';
$loop = \EventLoop\getLoop();
Rx\Scheduler::setDefaultFactory(function() use($loop){
    return new Rx\Scheduler\EventLoopScheduler($loop);
});

$terms  = ["rxphp", "php", "make php great again"];
$search = \Rx\Observable::fromArray($terms)
    ->map(function ($term) {
        return urlencode($term);
    })
    ->flatMap(function ($term) {
        return \Rx\React\Http::get("http://www.google.com/search?q={$term}")
            ->map(function ($result) use ($term) {
                return [
                    "term" => $term,
                    "page" => $result
                ];
            });
    });


$generator = \Rx\await($search);

echo "BLOCKING", PHP_EOL;

foreach ($generator as $item) {
    echo "Result page for: {$item['term']}", PHP_EOL, $item['page'];
}

echo "DONE";
