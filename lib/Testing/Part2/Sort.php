<?php namespace Testing\Part2;

/**
 * ソートライブラリです。
 * 配列を受け取ると、ソートして返します。
 * 配列以外は受け取りません。
 * 配列の中身は数値と文字のみ許可します。
 * 数値と文字が混在している場合、昇順であれば数値の後に文字が、降順であれば文字の後に数値がきます。
 * 
 * @author tatemi
 *
 */
class Sort
{
  /**
   * 昇順にソートします。
   * 
   * @param array $array
   */
  public function sort($array)
  {
    if(! is_array($array))
    {
      throw new \Exception("配列以外やでー");
    }
    if(count($array) == 0) return array();

    foreach($array as $val)
    {
        if(is_numeric($val))
        {
            $numerics[] = $val;
        }
        elseif(is_string($val))
        {
            $strings[] = $val;
        }
        else
        {
            throw new \Exception("文字と数値以外だぜ");
        }
    }

    sort($numerics);
    sort($strings);

    $array = array_merge($numerics, $strings);

    return $array;
  }
  
  /**
   * 降順にソートします。
   * @param array $array
   */
  public function rsort($array)
  {
    $sorted = $this->sort($array);

    return array_reverse($sorted);
  }
}
