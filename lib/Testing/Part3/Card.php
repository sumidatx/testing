<?php namespace Testing\Part3;

class Card
{
  /**
   * idはinsertした時に自動で作成されます。
   * @var int
   */
  public $id;
  
  /**
   * カードの名前です。
   * 入力必須です。
   * @var string
   */
  public $name;
  
  /**
   * カードの説明文です。
   * 入力必須ではありません。
   * @var string
   */
  public $description;
}
