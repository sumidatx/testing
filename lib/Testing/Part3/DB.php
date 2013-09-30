<?php namespace Testing\Part3;

class DB
{
  /**
   * インサートされたレコードの値を連想配列で返す。
   * @param unknown_type $query
   * @throws BadMethodCallException
   */
  public function method_insert($query)
  {
    throw new BadMethodCallException('dameyo');
  }
  
  /**
   * レコードの値を連想配列で返す。
   * @param unknown_type $query
   * @throws BadMethodCallException
   */
  public function method_select($query)
  {
    throw new BadMethodCallException('dameyo');
  }
  
  /**
   * 更新されたレコードの値を連想配列で返す。
   * @param unknown_type $query
   * @throws BadMethodCallException
   */
  public function method_update($query)
  {
    throw new BadMethodCallException('dameyo');
  }
  
  /**
   * レコードを削除する。成功したらtrue、それ以外はfalseを返す
   * @param unknown_type $query
   * @throws BadMethodCallException
   */
  public function method_delete($query)
  {
    throw new BadMethodCallException('dameyo');
  }
}
