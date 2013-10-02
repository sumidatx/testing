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

  /*
   * @dataProvider provider_作成_OK
   */
  public function test作成_OK($input, $msg, $expected)
  {
    $stub = $this->getMock('\Testing\Part3\DB')
                 ->expects($this->any())
                 ->method('method_insert')
                 ->will($this->returnValue($expected));
    echo get_class($stub);
    \Testing\Part3\DI::bind('DB', $stub);
    $result = $this->card_manager->create($input);
    $actual = get_object_vars($result);
    $this->assertEquals($expected, $actual, $msg);
  }

  public function provider_作成_OK()
  {
    return array(
      array(
        array('name' => 'カード1', 'description' => '説明1'),
        '1件目のレコード',
        array('id' => 1,'name' => 'カード1', 'description' => '説明1')
      ),
      array(
        array('name' => 'カード2', 'description' => '説明2'),
        '2件目のレコード(連番チェック)',
        array('id' => 2,'name' => 'カード2', 'description' => '説明2')
      ),
      array(
        array('name' => 'カード3'),
        'descriptionが無い',
        array('id' => 3, 'name' => 'カード3'),
      ),
      array(
        array('name' => 'カード4', 'description' => '説明4', 'title' => 'タイトル'),
        '存在しないプロパティ(title)を含む',
        array('id' => 4, 'name' => 'カード4', 'description' => '説明4', 'title' => 'タイトル'),
      )
    );
  }

//  /*
//   * @dataProvider provider_作成_NG
//   */
//  public function test作成_NG($input, $msg)
//  {
//    $stub = $this->getMock('\Testing\Part3\DB')
//                 ->expects($this->never())
//                 ->method('method_insert')
//                 ->will($this->throwException(new \InvalidArgumentException()));
//    \Testing\Part3\DI::bind('DB', $stub);
//
//    try {
//      if (array_key_exists('input', $input)) {
//        $this->card_manager->create($input['input']);
//      } else {
//        $this->card_manager->create();
//      }
//      $this->fail($msg);
//    } catch (Exception $e) {
//      $this->assertTrue(true, $msg);
//      $this->assertEquals('受け取った配列に入力必須のプロパティが無いじゃん', $e->getMessage());
//    }
//  }
//
//  public function provider_作成_NG()
//  {
//    return array(
//      array(array('dummy' => 'dummy'),                        '入力値が無い'),
//      array(array('input' => null),                           '入力値がnull'),
//      array(array('input' => array()),                        '入力値が空配列'),
//      array(array('input' => array('description' => '説明')), '入力必須プロパティ(name)が無い'),
//    );
//  }
//
//  public function test読み取り_OK()
//  {
//    $okCaseArray = array(
//      // id => array(input, msg)
//      1 => array(
//        array('name' => 'カード1', 'description' => '説明1'),
//        '存在するidを指定',
//      ),
//    );
//
//    foreach ($okCaseArray as $id => $case) {
//      list($input, $msg) = $case;
//      $expected = $input + array('id' => $id);
//      $stub = $this->getMock('\Testing\Part3\DB')
//                   ->expects($this->any())
//                   ->method('method_select')
//                   ->will($this->returnValue($expected));
//      \Testing\Part3\DI::bind('DB', $stub);
//      $this->card_manager->create($input);
//      $result = $this->card_manager->read($id);
//      $actual = get_object_vars($result);
//      $this->assertEquals($expected, $actual, $msg);
//    }
//  }
//
//  public function test読み取り_NG()
//  {
//    $ngCaseArray = array(
//      // array(id, expects, msg)
//      array(1,      '存在しないidを指定', 1, '$idが数値ではなかったよねー'),
//      array('hoge', '数値以外を指定',     0, 'idが存在しないだろ、てやんでぃ'),
//    );
//
//    foreach ($ngCaseArray as $case) {
//      list($id, $msg, $expects, $error_msg) = $case;
//      $stub = $this->getMock('\Testing\Part3\DB')
//                   ->expects($this->at($expects))
//                   ->method('method_select')
//                   ->will($this->throwException(new \InvalidArgumentException()));
//      \Testing\Part3\DI::bind('DB', $stub);
//      try {
//        $this->card_manager->read($id);
//        $this->fail($msg);
//      } catch (Exception $e) {
//        $this->assertEquals($error_msg, $e->getMessage(), $msg);
//      }
//    }
//  }
//
  public function test更新_OK()
  {
    // TODO: 引数のチェックをする
    // TODO: モックの引数として指定される値もチェックする必要があるが現在は空文字を渡している
    $msg = '更新NG(更新値にidが設定されている)';
    $params = array('id' => 1);
    try {
      $this->card_manager->update(1, $params);
      $this->fail($msg);
    } catch (\Exception $e) {
      $this->assertEquals('idは更新できません', $e->getMessage(), $msg);
    }

    $msg = '更新NG(該当データ無し)';
    $params = array();
    $stub = $this->getMock('\Testing\Part3\DB');
    $stub->expects($this->once())
         ->method('method_update')
         ->will($this->returnValue(null));
    \Testing\Part3\DI::bind('DB', $stub);
    try {
      $this->card_manager->update(1, $params);
      $this->fail($msg);
    } catch (\Exception $e) {
      $this->assertEquals('idが存在しない', $e->getMessage(), $msg);
    }

    $msg = '更新値OK';
    $params = array();
    $return_value = array();
    $expected = array('id' => null, 'name' => null, 'description' => null);
    $stub = $this->getMock('\Testing\Part3\DB');
    $stub->expects($this->once())
         ->method('method_update')
         ->will($this->returnValue($return_value));
    \Testing\Part3\DI::bind('DB', $stub);
    $result = $this->card_manager->update(1, $params);
    $actual = get_object_vars($result);
    $this->assertEquals($expected, $actual, $msg);
  }
//
//  public function test更新_NG()
//  {
//    $ngCaseArray = array(
//      // id => array(input, update, msg, expects, error_msg)
//      array(
//        array('name' => 'カード1', 'description' => '説明1'),
//        array($update_id = 9, array('name' => 'カードA', 'description' => '説明A')),
//        '存在しないidを更新',
//        $expects = 1,
//        'idが存在しない'
//      ),
//      array(
//        array('name' => 'カード2', 'description' => '説明2'),
//        array($update_id = 2, array('id' => 2, 'name' => 'カードB', 'description' => '説明B')),
//        '更新値にidを含む',
//        $expects = 0,
//        'idは更新できません'
//      ),
//    );
//
//    $stub = $this->getMock('\Testing\Part3\DB');
//
//    foreach ($ngCaseArray as $case) {
//      list($input, $update, $msg, $expects, $error_msg) = $case;
//      $stub = $this->getMock('\Testing\Part3\DB')
//                   ->expects($this->at($expects))
//                   ->method('method_update')
//                   ->will($this->throwException(new \InvalidArgumentException()));
//      \Testing\Part3\DI::bind('DB', $stub);
//      try {
//        $this->card_manager->create($input);
//        $this->callCardManagerUpdate($this->card_manager, $update);
//        $this->fail($msg);
//      } catch (Exception $e) {
//        $this->assertEquals($error_msg, $e->getMessage(), $msg);
//      }
//    }
//  }
//
//  private function callCardManagerUpdate(CardManager $instance, $param)
//  {
//    return call_user_func_array(array($instance, 'update'), $update);
//  }

  public function test削除_OK()
  {
    $stub = $this->getMock('\Testing\Part3\DB');
    $stub->expects($this->once())
         ->method('method_delete')
         ->will($this->returnValue(true));
    \Testing\Part3\DI::bind('DB', $stub);
    $actual = $this->card_manager->delete(1);
    $this->assertTrue($actual, '削除OK');
  }

  public function test削除_NG()
  {
    $msg = '削除NG';
    $error_msg = '削除失敗';
    $stub = $this->getMock('\Testing\Part3\DB');
    $stub->expects($this->once())
         ->method('method_delete')
         ->will($this->returnValue(false));
    \Testing\Part3\DI::bind('DB', $stub);

    try {
      $this->card_manager->delete(1);
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertEquals($error_msg, $e->getMessage(), $msg);
    }
  }
}
