<?php
require "silex.phar";
define("REPOSITORY_DIRS",__DIR__ . "/repos/");

$app = new Silex\Application();

$app['debug'] = true;
$app['redis'] = new Redis();
$app['redis']->connect("localhost");


$app['autoloader']->registerNamespace("Kokuban",__DIR__);
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path'       => __DIR__ . "/templates",
	'twig.class_path' => __DIR__ . "/vendors/twig/lib"
));
