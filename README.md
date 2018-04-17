# Await for RxPHP


This library will allow observables to block until complete.  For use when combining observables with imperative code.


It uses the [Voryx event-loop](https://github.com/voryx/event-loop) which behaves like the Javascript event-loop.  ie. You don't need to start it.


### Basic Example

```PHP

require __DIR__ . '/../vendor/autoload.php';
$loop = \EventLoop\getLoop();
Rx\Scheduler::setDefaultFactory(function() use($loop){
    return new Rx\Scheduler\EventLoopScheduler($loop);
});
//Do some aysnc craziness with observables
$observable = \Rx\Observable::interval(1000);

//Returns a `Generator` with the results of the observable
$generator = \Rx\await($observable);

//You can now use the results like a regular `Iterator`
foreach ($generator as $item) {

    //Will block here until the observable completes
    echo $item, PHP_EOL;
}


```


### Timeout Example

Since observables can return 1 to an infinite number of results, you'll need to make sure that you either limit the number of items you take or use a timeout or it could block forever.


```PHP
require __DIR__ . '/../vendor/autoload.php';
$loop = \EventLoop\getLoop();
Rx\Scheduler::setDefaultFactory(function() use($loop){
    return new Rx\Scheduler\EventLoopScheduler($loop);
});

$source = \Rx\Observable::interval(1000)
    ->takeUntil(\Rx\Observable::timer(10000)); //timeout after 10 seconds

$generator = \Rx\await($source);

foreach ($generator as $item) {
    echo $item, PHP_EOL;
}

echo "DONE";

```



```PHP

$source = \Rx\Observable::interval(1000)
    ->take(5); //Limit items to 5

$generator = \Rx\await($source);

foreach ($generator as $item) {
    echo $item, PHP_EOL;
}

echo "DONE";

```