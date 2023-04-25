<?php

class test {

    public $public = [
        'hello' => 'public Hello',
        'world' => 'public World',
        'foo' => 'public Foo',
    ];

    private $private = [
        'hello' => 'private Hello',
        'world' => 'private World',
        'foo' => 'private Foo',
    ];

    protected $protected = [
        'hello' => 'protected Hello',
        'world' => 'protected World',
        'foo' => 'protected Foo',
    ];


    public function getHello() {
        return 'Hello';
    }

    private function getWorld() {
        return 'World';
    }

    protected function getFoo() {
        return 'Foo';
    }

}