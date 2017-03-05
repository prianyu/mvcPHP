<?php
	//这里是一个入口文件的例子,
	define('APP_PATH','.');//项目路径
	define('ROOT_URL','http://localhost/mvcPHP/demo');//网站根目录
	define('MODULE_PATH',APP_PATH."/webname");//项目名称
	define('PUBLIC_DIR',ROOT_URL.'/'.'public');//公共文件目录
	define('HOME_PUBLIC',PUBLIC_DIR.'/index/');//前台模板公共目录
	define('ADMIN_PUBLIC',PUBLIC_DIR.'/admin');//后台模板公共目录
	define('__APP__',"http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);//应用主页


//引入框架文件
include "./PHP/PHP.php";
//运行项目
APP::run();
