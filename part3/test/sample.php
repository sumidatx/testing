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
  public function test作成_OK($input, $mock_args, $msg, $expected)
  {
    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_insert')
         ->with($this->equalTo($mock_args))
         ->will($this->returnValue($expected));
    \Testing\Part3\DI::bind('DB', $mock);

    $result = $this->card_manager->create($input);
    $actual = get_object_vars($result);
    $this->assertEquals($expected, $actual, $msg);
  }

  public function provider_作成_OK()
  {
    // 実際はinputによって変わる
    $mock_args = '';

    return array(
      array(
        array('name' => 'カード1', 'description' => '説明1'),
        $mock_args,
        '1件目のレコード',
        array('id' => 1,'name' => 'カード1', 'description' => '説明1')
      ),
      array(
        array('name' => 'カード2', 'description' => '説明2'),
        $mock_args,
        '2件目のレコード(連番チェック)',
        array('id' => 2,'name' => 'カード2', 'description' => '説明2')
      ),
      array(
        array('name' => 'カード3'),
        $mock_args,
        'descriptionが無い',
        array('id' => 3, 'name' => 'カード3'),
      ),
      array(
        array('name' => 'カード4', 'description' => '説明4', 'title' => 'タイトル'),
        $mock_args,
        '存在しないプロパティ(title)を含む',
        array('id' => 4, 'name' => 'カード4', 'description' => '説明4', 'title' => 'タイトル'),
      )
    );
  }

  public function test作成_NG()
  {
    $msg = '作成NG';
    $input = array('description' => '説明');
    $error_msg = '受け取った配列に入力必須のプロパティが無いじゃん';
    try {
      $this->card_manager->create($input);
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertEquals($error_msg, $e->getMessage(), $msg);
    }
  }

  // 引数チェック
  /*
   * @dataProvider provider_作成_NG
   */
  public function _test作成_NG($input, $msg)
  {
    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->never())
         ->method('method_insert');
    \Testing\Part3\DI::bind('DB', $mock);

    try {
      if (array_key_exists('input', $input)) {
        $this->card_manager->create($input['input']);
      } else {
        $this->card_manager->create();
      }
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertTrue(true, $msg);
      $this->assertEquals('受け取った配列に入力必須のプロパティが無いじゃん', $e->getMessage());
    }
  }

  public function provider_作成_NG()
  {
    return array(
      array(array('dummy' => 'dummy'),                        '入力値が無い'),
      array(array('input' => null),                           '入力値がnull'),
      array(array('input' => array()),                        '入力値が空配列'),
      array(array('input' => array('description' => '説明')), '入力必須であるnameが無い'),
    );
  }

  public function test読み取り_OK()
  {
    $msg = '読み取りOK';
    $query = '';
    $expected = array('id' => 1, 'name' => 'カード1', 'description' => '説明1');

    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_select')
         ->with($this->equalTo($query))
         ->will($this->returnValue($expected));
    \Testing\Part3\DI::bind('DB', $mock);
    $result = $this->card_manager->read(1);
    $actual = get_object_vars($result);
    $this->assertEquals($expected, $actual, $msg);
  }

  public function test読み取り_NG()
  {
    $msg = '数値以外を指定';
    $error_msg = '$idが数値ではなかったよねー';
    try {
      $this->card_manager->read('hoge');
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertEquals($error_msg, $e->getMessage(), $msg);
    }


    $msg = '存在しないidを指定';
    $error_msg = 'idが存在しないだろ、てやんでぃ';
    $query = '';
    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_select')
         ->with($this->equalTo($query))
         ->will($this->returnValue(null));
    \Testing\Part3\DI::bind('DB', $mock);
    try {
      $this->card_manager->read(1);
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertEquals($error_msg, $e->getMessage(), $msg);
    }
  }

  // 実際には以下の観点でのテストも必要だが省略
  // ・引数が両方無いとき(引数0)
  // ・idが無いとき(引数1つ)
  // ・paramsが無いとき(引数1つ)
  // ・idにnullが指定されているとき
  // ・paramsにnullが指定されているとき
  public function test更新_OK()
  {
    $msg = '更新OK';
    $params = array();
    $query = '';
    $return_value = array();
    $expected = array('id' => null, 'name' => null, 'description' => null);

    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_update')
         ->with($this->equalTo($query))
         ->will($this->returnValue($return_value));
    \Testing\Part3\DI::bind('DB', $mock);

    $result = $this->card_manager->update(1, $params);
    $actual = get_object_vars($result);
    $this->assertEquals($expected, $actual, $msg);
  }

  public function test更新_NG()
  {
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
    $query = '';
    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_update')
         ->with($this->equalTo($query))
         ->will($this->returnValue(null));
    \Testing\Part3\DI::bind('DB', $mock);
    try {
      $this->card_manager->update(1, $params);
      $this->fail($msg);
    } catch (\Exception $e) {
      $this->assertEquals('idが存在しない', $e->getMessage(), $msg);
    }
  }

  public function test削除_OK()
  {
    $msg = '削除OK';
    $query = '';

    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_delete')
         ->with($this->equalTo($query))
         ->will($this->returnValue(true));
    \Testing\Part3\DI::bind('DB', $mock);

    $actual = $this->card_manager->delete(1);
    $this->assertTrue($actual, $msg);
  }

  public function test削除_NG()
  {
    $msg = '削除NG';
    $error_msg = '削除失敗';
    $query = '';

    $mock = $this->getMock('\Testing\Part3\DB');
    $mock->expects($this->once())
         ->method('method_delete')
         ->with($this->equalTo($query))
         ->will($this->returnValue(false));
    \Testing\Part3\DI::bind('DB', $mock);

    try {
      $this->card_manager->delete(1);
      $this->fail($msg);
    } catch (Exception $e) {
      $this->assertEquals($error_msg, $e->getMessage(), $msg);
    }
  }
}
