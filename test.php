<?php
require_once "vendor/autoload.php";
ini_set('display_errors',1);

use Testing\Part3\Card;
$card = new Card;
try{
$card->read(1);
$card->create($params = array());
$card->update(1, $params);
$card->delete(1);
}
catch (Exception $e) {
  echo $e->getMessage();
}
?>