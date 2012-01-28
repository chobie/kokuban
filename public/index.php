<?php
require dirname(__DIR__) . "/config.php";

$app->mount('/', new Kokuban\GistProvider());
$app->mount('/', new Kokuban\SmartprotocolProvider());

$app->run();

