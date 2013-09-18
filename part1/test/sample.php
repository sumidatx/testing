<?php
require_once __DIR__."/../../vendor/autoload.php";

class SampleTest extends PHPUnit_Framework_TestCase
{
  public function testSample()
  {
    $actual = "hoge";
    $expected = "hoge";
    $this->assertEquals($expected, $actual);
  }

  public function testInvalidSample()
  {
    $actual = "ihoge";
    $expected = "hoge";
    $this->assertEquals($expected, $actual, "同じ値でないといけない");
  }
}
