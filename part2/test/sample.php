<?php
require_once __DIR__."/../../vendor/autoload.php";

class SampleTest extends PHPUnit_Framework_TestCase
{
  private $sortobject = null;

  public function setup ()
  {
    $this->sortobject = new \Testing\Part2\Sort();
  }

  public function tearDown()
  {
    $this->sortobject = null;
  }

  public function test入力値_昇順_OK ()
  {
    $okCaseArray = array(
      // array(input, expected, msg)
      array(array(4, 2, 1, 3),            array(1, 2, 3, 4),         '数値の配列'),
      array(array("b", "a", "d", "c" ),   array("a", "b", "c", "d"), '文字列の配列'),
      array(array(),                      array(),                   '空の配列'),
      array(array("4", "a", 1, "b", "5"), array( 1, 4, 5, "a", "b"), '混在している配列'),
      array(
        array("もり", "ふくだ", "こやなぎ"),
        array("こやなぎ", "ふくだ", "もり"),                         '全角文字列'),
    );
    foreach ($okCaseArray as $case) {
      list($input, $expected, $msg) = $case;
      $actual = $this->sortobject->sort($input);
      $this->assertEquals($expected, $actual, $msg);
    };
  }
  public function test入力値_降順_OK ()
  {
    $okCaseArray = array(
      // array(input, expected, msg)
      array(array(4, 2, 1, 3),            array(4, 3, 2, 1),         '数値の配列'),
      array(array("b", "a", "d", "c" ),   array("d", "c", "b", "a"), '文字列の配列'),
      array(array(), array(),                                        '空の配列'),
      array(array("4", "a", 1, "b", "5"), array("b", "a", 5, 4, 1),  '混在している配列'),
      array(
        array("ふくだ", "もり", "こやなぎ"),
        array("もり", "ふくだ", "こやなぎ"),                         '全角文字列'),
    );
    foreach ($okCaseArray as $case) {
      list($input, $expected, $msg) = $case;
      $actual = $this->sortobject->rsort($input);
      $this->assertEquals($expected, $actual, $msg);
    };
  }

  public function test入力値_NG ()
  {
    $ngCaseArray = array(
      // array(input&expected, msg)
      array(array(new stdClass),      'オブジェクトを含む'),
      array(array(true, false),       '真偽値を含む'),
      array(array(null),              'NULLを含む'),
      array(array("a"=>1),            '連想配列を含む'),
      array(array("b"=>new stdClass), '連想配列 値にオジェクト含む'),
      array(array("b"=>array()),      '連想配列 値に配列含む'),
      array(array(array()),           '配列を含む'),
      array()
    );

    foreach ($ngCaseArray as $case) {
      list($input, $msg) = $case;
      try {
        $actual = $this->sortobject->sort($input);
      } catch (Exception $e) {
        return;
      }
      $this->fail($msg);
    };
  }

  public function test結果_未ソート数値のみ_昇順()
  {
    $okCaseArray = array(
      // array(input, expected, msg)
      array(array(1, 10, 5, 2, 8, 20),    array(1, 2, 5, 8, 10, 20),    'ソート済み'),
      array(array(1.2, 5.5, 2.1, 3.3),    array(1.2, 2.1, 3.3, 5.5),    '少数'),
      array(array(1, 10, -5, 2, -8, -20), array(-20, -8, -5, 1, 2, 10), 'マイナス'),
      array(array(1, 10, 5, 2, 0, 8, 20), array(0, 1, 2, 5, 8, 10, 20), 'ゼロを含む'),
      array(array(1, 5, 8, 20, 8, 5, 8),  array(1, 5, 5, 8, 8, 8, 20),  '同じ数値を含む'),
      array(array(2, 2, 2, 2),            array(2, 2, 2, 2),            '同じ数値だけ'),
    );

    foreach ($okCaseArray as $case) {
      list($input, $expected, $msg) = $case;
      $actual = $this->sortobject->sort($input);
      $this->assertEquals($expected, $actual, $msg);
    };
  }

  public function test結果_ソート済み数値のみ_降順()
  {
    $okCaseArray = array(
      // array(input&expected, msg)
      array(array(20, 10, 5, 2, 1),       'ソート済み'),
      array(array(5.5, 3.3, 2.1, 1.2),    '少数'),
      array(array(10, 2, 1, -5, -8, -20), 'マイナス'),
      array(array(20, 10, 8, 5, 2, 1, 0), 'ゼロ含む'),
      array(array(20, 8, 8, 8, 5, 5, 1),  '同じ数値を含む'),
      array(array(2, 2, 2, 2),            '同じ数値だけ'),
    );

    foreach ($okCaseArray as $case) {
      list($input, $msg) = $case;
      $actual = $this->sortobject->rsort($input);
      $this->assertEquals($input, $actual, $msg);
    };
  }

  public function test結果_未ソート文字列のみ_昇順()
  {
    $okCaseArray = array(
      // array(input, expected, msg)
      array(array("d", "b", "a", "c"),      array("a", "b", "c", "d"),      '一文字'),
      array(array("dd", 'bb', 'aa', 'cc'),  array('aa', 'bb', 'cc', 'dd'),  '二文字'),
      array(array('', '', '', '', ''),      array('', '', '', '', ''),      'すべて空文字'),
      array(array(' ', ' ', ' ', ' ', ' '), array(' ', ' ', ' ', ' ', ' '), 'すべて半角スペース'),
      array(
        array("\n\n", "\n\n", "\n\n", "\n\n", "\n\n"),
        array("\n\n", "\n\n", "\n\n", "\n\n", "\n\n"),                      'すべて改行'),
      array(
        array("a", "aa", "", " ", "\n\n"),
        array('', "\n\n", " ", "a", "aa"),                                  '一文字二文字空文字半角スペース改行'),
    );

    foreach ($okCaseArray as $case) {
      list($input, $expected, $msg) = $case;
      $actual = $this->sortobject->sort($input);
      $this->assertEquals($expected, $actual, $msg);
    };
  }

  public function test結果_ソート済み文字列のみ_降順()
  {
    $okCaseArray = array(
      // array(input&expected, msg)
      array(array("d", "c", "b", "a"),                     '一文字'),
      array(array('dd', 'cc', 'bb', 'aa'),                 '二文字'),
      array(array('', '', '', '', ''),                     'すべて空文字'),
      array(array(' ', ' ', ' ', ' ', ' '),                'すべて半角スペース'),
      array(array("\n\n", "\n\n", "\n\n", "\n\n", "\n\n"), 'すべて改行文字'),
      array(array('aa', 'a', " ", "\n\n", ""),             '一文字二文字空文字半角スペース全角改行'),
    );

    foreach ($okCaseArray as $case) {
      list($input, $msg) = $case;
      $actual = $this->sortobject->rsort($input);
      $this->assertEquals($input, $actual, $msg);
    };
  }

  public function test結果_混在_昇順()
  {
    // 数値が前
    $okCaseArray = array(
      // array(input, expected, msg)
      array(array("aa", "a", 0, 10, 1, "", " ", "\n\n"), array(0, 1, 10, '', "\n\n", " ", "a", "aa"), '混在'),
    );

    foreach ($okCaseArray as $case) {
      list($input, $expected, $msg) = $case;
      $actual = $this->sortobject->sort($input);
      $this->assertEquals($expected, $actual, $msg);
    };
  }

  public function test結果_混在_降順()
  {
    // 数値が後
    $okCaseArray = array(
      // array(input, expected, msg)
      array(array("a", "aa", 0, 10, 1,"", " ", "\n\n"), array("aa", "a", " ", "\n\n", "", 10, 1, 0), '混在'),
    );

    foreach ($okCaseArray as $case) {
      list($input, $expected, $msg) = $case;
      $actual = $this->sortobject->rsort($input);
      $this->assertEquals($expected, $actual, $msg);
    };
  }
}
