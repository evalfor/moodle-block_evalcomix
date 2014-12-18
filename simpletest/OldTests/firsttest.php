<?php

require_once('../classes/evalcomix_tool.php');

class FirstTest extends PHPUnit_Framework_TestCase {
  protected $fixture; 

  protected function setUp(){
    $this->fixture =  Array();
}

  public function testNewArrayIsEmpty() {
  $this->assertEquals(0, sizeof($this->fixture));
}

  public function testArrayContainsAnElement() {
    $this->fixture[] = 'Element';
  $this->assertEquals(1, sizeof($this->fixture));
}
}
?>