<?php

require __DIR__ . '/../vendor/autoload.php';

$source = \Rx\Observable::interval(1000);

$generator = \Rx\await($source);

foreach ($generator as $item) {
    if ($item === 3) {
        \Rx\cancelAwait($generator);
    }
    echo $item, PHP_EOL;
}

echo 'DONE';
