<?php
require dirname(__DIR__) . "/config.php";


$app->get("/", function(Silex\Application $app){
	$tmp = $app['redis']->lrange('kokuban.list',0,10);
	$list = array();
	foreach($tmp as $offset => $value) {
	  $list[$value] = unserialize($app['redis']->get($value));
	}

	return new Symfony\Component\HttpFoundation\Response($app['twig']->render('index.htm',array('list'=>$list)));
});


$app->mount('/', new Kokuban\GistProvider());
$app->mount('/', new Kokuban\SmartprotocolProvider());

$app->run();

