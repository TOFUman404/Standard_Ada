<?php
// This Controller for test
require APPPATH . 'modules/testproject/controllers/test.php';
class main_test extends test {
    public function __construct()
    {
        parent::__construct();
    }
    public function test_main(){
        echo $this->getHello() . '<br>';
        echo $this->isPublic['hello'] . '<br>';
        echo $this->isProtected['foo'] . '<br>';
//        echo $this->isPrivate['bar'];
        echo $this->ObjName("Jame", 27);
        $test2 = new test2();
        echo '<br>';
//        echo $test2->getHello2();
    }
}