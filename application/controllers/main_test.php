<?php

class main_test extends test {
    public function test_main(){
        echo $this->getHello();
        echo $this->public['hello'];
        echo $this->protected['hello'];
        echo $this->private['hello'];
    }
}