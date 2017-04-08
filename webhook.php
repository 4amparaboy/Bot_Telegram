<?php
header("Content-type:application/json");
require __DIR__."/loader.php";
$wb = json_decode(file_get_contents("input"),true);
print_r($wb);
$app = new mgmt($wb,new Telegram(""));
$app->run();