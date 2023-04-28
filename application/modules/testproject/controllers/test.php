<?php
// This Controller for test
class test extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public $isPublic = [
        'hello' => 'public Hello',
        'world' => 'public World',
        'foo' => 'public Foo',
    ];

    private $isPrivate = [
        'hello' => 'private Hello',
        'world' => 'private World',
        'foo' => 'private Foo',
    ];

    protected $isProtected = [
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

    public function ObjName(String $array ,Int $second): String {
        return $first . ' ' . $second;
    }

}

class test2 extends MX_Controller {
    public function __construct()
    {
        parent::__construct();
    }

//    public function getHello2() {
//        return 'Hello 2';
//    }

    protected function getWorld2() {
        return 'World 2';
    }
}