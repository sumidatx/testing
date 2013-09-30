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
		if(! isset($params['name']))
		{
			throw new \Exception($message = '受け取った配列に入力必須のプロパティが無いじゃん');
		}

		$dummy_query = $params;
		$result = DI::make('DB')->method_insert($dummy_query);

		$card = DI::make('Card');
		foreach($result as $key => $val)
		{
			$card->$key = $val;
		}

		return $card;
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
		if(! is_numeric($id))
		{
			throw new \Exception($message = '$idが数値ではなかったよねー');
		}

		$dummy_query = $id;
		$result = DI::make('DB')->method_select($dummy_query);

		if(is_null($result))
		{
			throw new \Exception($message = 'idが存在しないだろ、てやんでぃ');
		}

		$card = DI::make('Card');
		foreach($result as $key => $val)
		{
			$card->$key = $val;
		}

		return $card;
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
		if(isset($params['id']))
		{
			throw new \Exception($message = 'idは更新できません');
		}

		$dummy_query = array_merge(array($id), $params);
		$result = DI::make('DB')->method_update($dummy_query);
		if(is_null($result))
		{
			throw new \Exception($message = 'idが存在しない');
		}

		$card = DI::make('Card');
		foreach($result as $key => $val)
		{
			$card->$key = $val;
		}

		return $card;
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
	public function delete($id)
	{
		$dummy_query = $id;
		$result = DI::make('DB')->method_delete($dummy_query);
		if(! $result)
		{
			throw new \Exception($message = '削除失敗');
		}

		return true;
	}
}