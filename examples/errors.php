<?php

require __DIR__ . '/../vendor/autoload.php';


$source = \Rx\Observable::error(new Exception('some error'));

$generator = \Rx\await($source);

try {
    foreach ($generator as $item) {
        echo $item, PHP_EOL;
    }
} catch (\Exception $e) {
    echo "caught error: ", $e->getMessage();
}





