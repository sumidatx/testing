<?php namespace Testing\Part3;

class DI
{
  static protected $pool;
  
  /**
   * 指定された名前のインスタンスを生成します。
   * @param string $name
   * @throws \Exception
   */
  static public function make($name)
  {
    if (isset(self::$pool[$name])) {
      return self::$pool[$name];
    } elseif (class_exists('Testing\Part3\\'.$name)) {
      $class = 'Testing\Part3\\'.$name;
      self::$pool[$name] = new $class;
      return self::$pool[$name];
    } else {
      throw new \Exception($name . 'not binded');
    }
  }
  
  /**
   * 指定された名前にインスタンスを関連付けます。
   * @param string $name
   * @param object $instance
   */
  static public function bind($name, $instance)
  {
    self::$pool[$name] = $instance;
  }
}
