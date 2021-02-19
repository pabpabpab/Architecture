<?php


trait NotSingle {
    public static function getAnotherInstance() {
        return new static();
    }
}


final class Singleton {
    use NotSingle;

    private static $instance;
    public $name = 'singleton1';

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return new static();
    }
}

$o1 = Singleton::getInstance();
$o1->name = 'singleton2';
$o2 = Singleton::getAnotherInstance();

echo $o1->name . PHP_EOL;
echo $o2->name . PHP_EOL;


/*
singleton2
singleton1
 */