<?php 
	/**
	 * 计算运行时间
	 */
	function run_time($start,$end='',$decimial=3){
		static $times=array();
		if($end !=''){
			 $times[$end]=microtime();
			return number_format($times[$end]-$times[$start],$decimial);
		}
		$times[$start]=microtime();
	}
	run_time("start");
	//项目初始化
	if(!defined("APP_PATH")){
		define('APP_PATH',dirname($_SERVER['SCRIPT_FILENAME']));
	}
	//框架主目录
	define("PHP_PATH",dirname(__FILE__));
	//框架临时目录
	define("TEMP_PATH",APP_PATH.'/temp');
	//加载编译文件
	$runtime_file=TEMP_PATH.'/runtime.php';
	if(is_file($runtime_file)){
		require $runtime_file;
	}else{
		include PHP_PATH.'/common/runtime.php';
		runtime();
	}
	run_time('end');
 ?>