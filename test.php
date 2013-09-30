<?php
require_once "vendor/autoload.php";
ini_set('display_errors',1);

use Testing\Part3\CardManager;
$card_manager = new CardManager;
try{
$card_manager->read(1);
$card_manager->create($params = array());
$card_manager->update(1, $params);
$card_manager->delete(1);
}
catch (Exception $e) {
  echo $e->getMessage();
}
?>