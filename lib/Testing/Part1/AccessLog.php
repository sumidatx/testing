<? namespace Testing\Part1;
/**
 * AccessLog
 *
 * @package
 * @version
 * @copyright
 * @author
 * @licence
 */
class AccessLog {
  private $path;

  /**
   * __construct__
   *
   */
  public function __construct($path) {
    $this->path = $path;
  }

  /**
   * lineCountByStatusCode
   *
   * @param mixed $status_code
   * @access public
   * @return integer
   */
  public function getLineCountByStatusCode($status_code) {

    try {

      if (!file_exists($this->path)) {
        throw new \Exception('ファイルが存在しません');
      }

      // 入力チェック
      if (!is_numeric($status_code) || strlen($status_code) != 3) {
        throw new \Exception('数値３桁で入力して下さい');
      }

      $count = 0;
      $serch_str = 'HTTP/1.1" '.$status_code;
      if ($fp = fopen($this->path, 'r')) {
        if (flock($fp, LOCK_SH)) {
          while (!feof($fp)) {
            $buffer = fgets($fp);
            if (strpos($buffer, $serch_str)) {
              $count++;
            }
          }
        }
        fclose($fp);
      } else {
        throw new \Exception('ファイルが開けません');
      }

    }
    catch(Exception $e)
    {
      echo $e->getMessage();
    }

    return $count;
  }
}
