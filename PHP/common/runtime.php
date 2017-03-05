<?php 
	function runtime(){
		$files=require_once PHP_PATH.'/common/files.php';
		foreach ($files as $v) {
			if(is_file($v)){
				require $v;
			}
		} 
		$data='';
		mkdirs();
		//框架常规配置项
		C(require PHP_PATH.'/libs/etc/init.config.php');
		// 生成编译文件
		foreach($files as $v){
			$data.=del_space($v);
		}
			$data="<?php".$data."C(require PHP_PATH.'/libs/etc/init.config.php')?>";
			file_put_contents(TEMP_PATH.'/runtime.php', $data);
			index_control();//第一次运行时调用的默认页面
	}
	//第一次运行默认页面
	function index_control(){
		$index_dir=MODULE_PATH.'/index';
		$index_file=$index_dir.'/index'.C("CONTROL_FIX").C('CLASS_FIX').'.php';
		if(!is_dir($index_dir)){
			mkdir($index_dir,0777);
		}
		if(!is_file($index_file)){
			$data=<<<str
			<?php
				class indexControl extends Control{
					function index(){
						echo "欢迎使用自制框架！";
					}
				}
			?>
str;
			file_put_contents($index_file, $data);
			}
		}
	//创建环境目录
	function mkdirs(){
		//判断目录是否存在
		if(!is_dir(TEMP_PATH)){
			@mkdir(TEMP_PATH,0777);
		}
		//检测目录是否有写权限
		if(!is_writeable(TEMP_PATH)){
			error('目录没有写权限，程序无法运行');
		}
		if(!is_dir(CACHE_PATH))mkdir(CACHE_PATH,0777,true);
		if(!is_dir(LOG_PATH))mkdir(LOG_PATH,0777,true);
		if(!is_dir(CONFIG_PATH))mkdir(CONFIG_PATH,0777,true);
		if(!is_dir(TEMPLATE_PATH))mkdir(TEMPLATE_PATH,0777,true);
		if(!is_dir(TPL_PATH))mkdir(TPL_PATH,0777,true);	
		if(!is_dir(MODULE_PATH))mkdir(MODULE_PATH,0777,true);
			
	}
 ?>