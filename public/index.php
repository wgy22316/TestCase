<?php
define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */
$app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
$app->bootstrap()/*实例化Bootstrap, 依次调用Bootstrap中所有_init开头的方法*/
	->run();
