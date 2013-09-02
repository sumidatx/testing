<?php
require_once __DIR__."/../../vendor/autoload.php";

class SampleTest extends PHPUnit_Framework_TestCase
{
  public function testSample()
  {
    $got = "hoge";
    $expected = "hoge";
    $this->assertEquals($got, $expected);
  }

  public function testInvalidSample()
  {
    $got = "ihoge";
    $expected = "hoge";
    $this->assertEquals($got, $expected, "同じ値でないといけない");
  }
}
