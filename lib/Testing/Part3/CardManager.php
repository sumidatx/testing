<?php namespace Testing\Part3;

/**
 * ゲームのカードを表すモデルです。
 * カードに対してCRUDができます。
 * 各メソッドはDBクラスに依存しています。
 * 仕様は各メソッドやプロパティのPHPDocに記述します
 * 
 * @author tatemi
 *
 */
class CardManager
{

  
  /**
   * カード情報を新規作成します。
   * 入力はプロパティ名をキーにした配列で受取ります。
   * 存在しないプロパティが入力された場合、それは無視されます。
   * 受け取った配列に入力必須のプロパティが無い場合、Exceptionを投げます。
   * 返り値は、入力した値をセットしたCardインスタンスです。
   * そのインスタンスにはidが連番で付与されます。
   * 
   * @param array('property' => 'value', ...) $params
   * @return \Testing\Part3\Card
   */
  public function create($params)
  {
    
    $dummy_query = '';
    $result = DI::make('DB')->method_insert($dummy_query);
    
  }
  
  /**
   * 指定したカード情報を取得します。
   * 入力はカードのidです。そのidが存在しない場合はExceptionを投げます。
   * 返り値は、入力したidのCardインスタンスです。
   * 
   * @param int id
   * @return \Testing\Part3\Card
   */
  public function read($id)
  {
    $dummy_query = '';
    $result = DI::make('DB')->method_select($dummy_query);
  }
  
  /**
   * 指定したカード情報を更新します。
   * 入力はカードのidと、プロパティ名をキーにした配列です。
   * 存在しないプロパティが入力された場合、それは無視されます。
   * 存在しないidが入力された場合は、Exceptionを投げます。
   * 受け取った配列に入力必須のものが無くても大丈夫です。（アップデートなので）
   * idは更新できません。idを入力した配列に入れるとExceptionを投げます。
   * 返り値は、更新後のCardインスタンスです。
   * 
   * @param int $id
   * @param array('property' => 'value', ...) $params
   * @return \Testing\Part3\Card
   */
  public function update($id, $params)
  {
    $dummy_query = '';
    $result = DI::make('DB')->method_update($dummy_query);
  }
  
  /**
   * 指定したカード情報を削除します。
   * 入力はidです。
   * 存在しないidが入力された場合、その他削除に失敗した場合はExceptionを投げます。
   * 返り値はtrueです。
   * 
   * @param int $id
   * @return boolean true
   */
  public function delete()
  {
    $dummy_query = '';
    $result = DI::make('DB')->method_delete($dummy_query);
  }
}