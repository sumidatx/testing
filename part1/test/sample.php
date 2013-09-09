<?php
require_once __DIR__."/../../vendor/autoload.php";

class SampleTest extends PHPUnit_Framework_TestCase
{
	private $file_path = null;
	private $status_codes = array(100 => 100, 101 => 101,
			200 => 200, 201 => 201, 202 => 202, 203 => 203, 204 => 204, 205 => 205, 206 =>206,
			300 => 300, 301 => 0, 302 => 302, 303 => 303, 304 => 0, 305 => 305,
			400 => 400, 401 => 401, 402 => 402, 403 => 403, 404 => 404, 405 => 405, 406 => 406, 407 => 407, 408 => 408, 409 => 409, 410 => 410, 411 => 411, 412 => 412, 413 => 413, 414 => 414, 415 =>415,
			500 => 500, 501 => 501, 502 => 502, 503 => 503, 504 => 504, 505 => 505,);

	public function setUp() {
		$this->file_path = dirname(__DIR__) . '/data/access_log';
		$this->access_log =  new \Testing\Part1\AccessLog($this->file_path);
		$this->file_pointer = fopen($this->file_path, "r");
	}

	public function test有効なパスの初期化テスト()
	{
		try {
			$access_log = new \Testing\Part1\AccessLog($this->file_path);
		}
		catch(Exception $e)
		{
			$this->fail($message = '有効なパスで初期化失敗' . $e->getMessage());
			return;
		}

		$this->assertTrue(true, $message = '有効なパスで初期化成功');

		}

	public function test無効なパスの初期化テスト()
	{
		try {
			$access_log = new \Testing\Part1\AccessLog($path = '../data/hogehoge_log');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '無効なパスで初期化失敗' . $e->getMessage());
			return;
		}

		$this->fail($message = '無効なパスで初期化成功');
	}
	
	public function test不正なパスの初期化テスト()
	{
		try {
			$access_log = new \Testing\Part1\AccessLog($path = '@@93843=^^^\003hogehoge');
			$this->fail($message = '不正なパス(文字列)で初期化失敗');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '不正なパス(文字列)で初期化成功', $e->getMessage());
		}

		try{
			$access_log = new \Testing\Part1\AccessLog($path = '');
			$this->fail($message = '不正なパスで初期化失敗');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '不正なパスで初期化成功', $e->getMessage());
		}

		try{
			$access_log = new \Testing\Part1\AccessLog($this->file_pointer);
			$this->fail($message = '不正なパスで初期化失敗');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '不正なパスで初期化成功', $e->getMessage());
		}

		try{
			$access_log = new \Testing\Part1\AccessLog($path = 12345);
			$this->fail($message = '不正なパスで初期化失敗');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '不正なパス(数値)で初期化成功', $e->getMessage());
		}
	}

	public function test正常にステータスコードをカウントできたぜ()
	{
		foreach($this->status_codes as $status_code => $expected)
		{
			$acctual = $this->access_log->getLineCountByStatusCode($status_code);
			$this->assertEquals($expected, $acctual, $message = '失敗');
		}
	}

	public function test無効なステータスコードが入力されましてよ()
	{

		try
		{
			$count = $this->access_log->getLineCountByStatusCode(33);
			$this->fail($message = '無効なステータスコード(2桁数値)が入力されたのに例外が発生しなかった');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '無効なステータスコード(2桁数値)が入力されたので例外発生成功');
		}

		try
		{
			$count = $this->access_log->getLineCountByStatusCode(3333);
			$this->fail($message = '無効なステータスコード(4桁数値)が入力されたのに例外が発生しなかった');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '無効なステータスコード(4桁数値)が入力されたので例外発生成功');
		}

		try
		{
			$count = $this->access_log->getLineCountByStatusCode('hogehoge');	
			$this->fail($message = '無効なステータスコードが入力されたのに例外が発生しなかった');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '無効なステータスコードが入力されたので例外発生成功');
		}

		try
		{
			$count = $this->access_log->getLineCountByStatusCode($this->file_pointer);
			$this->fail($message = '無効なステータスコードが入力されたのに例外が発生しなかった');
		}
		catch(Exception $e)
		{
			$this->assertTrue(true, $message = '無効なステータスコードが入力されたので例外発生成功');
		}
	}

	public function tearDown() {
		fclose($this->file_pointer);
	}
}
