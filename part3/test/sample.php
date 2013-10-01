<?php
require_once __DIR__."/../../vendor/autoload.php";

class SampleTest extends PHPUnit_Framework_TestCase
{
  private $card_manager = null;

  public function setup()
  {
    $this->card_manager = new \Testing\Part3\CardManager();
  }

  public function tearDown()
  {
    $this->card_manager = null;
  }

  public function test作成_OK()
  {
    $okCaseArray = array(
      // id => array(input, msg)
      1 => array(
        array('name' => 'カード1', 'description' => '説明1'),
        '1件目のレコード',
      ),
      2 => array(
        array('name' => 'カード2', 'description' => '説明2'),
        '2件目のレコード(連番チェック)',
      ),
      3 => array(
        array('name' => 'カード2'),
        'descriptionが無い',
      ),
      4 => array(
        array('name' => 'カード4', 'description' => '説明4', 'title' => 'タイトル'),
        '存在しないプロパティ(title)を含む',
      ),
    );

    foreach ($okCaseArray as $id => $case) {
      list($input, $msg) = $case;
      $result = $this->card_manager->create($input);
      $actual = get_object_vars($result);
      $expected = $input + array('id' => $id);
      $this->assertEquals($expected, $actual, $msg);
    }
  }

  public function test作成_NG()
  {
    $ngCaseArray = array(
      array(                                           'msg' => '入力値が無い'),
      array('input' => null,                           'msg' => '入力値がnull'),
      array('input' => array(),                        'msg' => '入力値が空配列'),
      array('input' => array('description' => '説明'), 'msg' => '入力必須プロパティ(name)が無い'),
    );

    foreach ($ngCaseArray as $case) {
      try {
        if (array_key_exists('input', $case)) {
          $this->card_manager->create($case['input']);
        } else {
          $this->card_manager->create();
        }
        $this->fail($case['msg']);
      } catch (Exception $e) {
        $this->assertTrue(true, $case['msg']);
      }
    }
  }

  public function test読み取り_OK()
  {
    $okCaseArray = array(
      // id => array(input, msg)
      1 => array(
        array('name' => 'カード1', 'description' => '説明1'),
        '存在するidを指定',
      ),
    );

    foreach ($okCaseArray as $id => $case) {
      list($input, $msg) = $case;
      $this->card_manager->create($input);
      $result = $this->card_manager->read($id);
      $actual = get_object_vars($result);
      $expected = $input + array('id' => $id);
      $this->assertEquals($expected, $actual, $msg);
    }
  }

  public function test読み取り_NG()
  {
    $id = 1;
    $msg = '存在しないidを指定';

    try {
      $this->card_manager->read($id);
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertTrue(true, $msg);
    }
  }

  public function test更新_OK()
  {
    $okCaseArray = array(
      // id => array(input, update, expected, msg)
      1 => array(
        array('name' => 'カード1', 'description' => '説明1'),
        array('name' => 'カードA', 'description' => '説明A'),
        array('name' => 'カードA', 'description' => '説明A'),
        'nameとdescriptionの更新'
      ),
      2 => array(
        array('name' => 'カード2', 'description' => '説明2'),
        array(                     'description' => '説明B'),
        array('name' => 'カード2', 'description' => '説明B'),
        '入力必須プロパティ(name)が無い'
      ),
      3 => array(
        array('name' => 'カード3', 'description' => '説明3'),
        array(                     'description' => '説明C', 'title' => 'タイトル'),
        array('name' => 'カード3', 'description' => '説明C'),
        '存在しないプロパティ(title)がある'
      ),
    );

    foreach ($okCaseArray as $id => $case) {
      list($input, $update, $expected, $msg) = $case;
      $this->card_manager->create($input);
      $actual = $this->card_manager->update($id, $update);
      $this->assertEquals($expected, $actual, $msg);
    }
  }

  public function test更新_NG()
  {
    $ngCaseArray = array(
      // id => array(input, update, update_id, msg)
      1 => array(
        array('name' => 'カード1', 'description' => '説明1'),
        array('name' => 'カードA', 'description' => '説明A'),
        9,
        '存在しないidを更新'
      ),
      2 => array(
        array(           'name' => 'カード2', 'description' => '説明2'),
        array('id' => 2, 'name' => 'カードB', 'description' => '説明B'),
        2,
        '更新値にidを含む'
      ),
    );

    foreach ($ngCaseArray as $id => $case) {
      list($input, $update, $update_id, $msg) = $case;
      try {
        $this->card_manager->create($input);
        $this->card_manager->update($update_id, $update);
        $this->fail($msg);
      } catch (Exception $e) {
        $this->assertTrue(true, $msg);
      }
    }
  }

  public function test削除_OK()
  {
    $okCaseArray = array(
      // id => array(input, msg)
      1 => array(
        array('name' => 'カード1', 'description' => '説明1'),
        '存在するidを指定'
      ),
    );

    foreach ($okCaseArray as $id => $case) {
      list($input, $msg) = $case;
      $this->card_manager->create($input);
      $actual = $this->card_manager->delete($id);
      $this->assertTrue($actual, $msg);
    }
  }

  public function test削除_NG()
  {
    $ngCaseArray = array(
      // id => array(input, delete_id, msg)
      1 => array(
        array('name' => 'カード1', 'description' => '説明1'),
        9,
        '存在しないidを指定'
      ),
    );

    foreach ($ngCaseArray as $id => $case) {
      list($input, $delete_id, $msg) = $case;
      try {
        $this->card_manager->create($input);
        $this->card_manager->delete($delete_id);
        $this->fail($msg);
      } catch (Exception $e) {
        $this->assertTrue(true, $msg);
      }
    }
  }
}
