<?php 
	header('Content-type:text/html;charset=utf-8');
	//项目处理类
	class APP{
		static $module;//模块
		static $control;//控制器
		static $action;//动作方法
		static function run(){
			include PHP_PATH.'/plugins/smarty/Smarty.class.php';
			//配置自动加载文件
			spl_autoload_register(array(__CLASS__,'autoload'));
			//注册错误处理函数
			set_error_handler(array(__CLASS__,"error"));
			//注册异常处理函数
			set_exception_handler(array(__CLASS__,"exception"));
			//是否转义
			define("MAGIC_QUOTES_GPC",get_magic_quotes_gpc()?true:false);
			//设置时区
			if(function_exists("date_default_timezone_set")){
				date_default_timezone_set(C('DATE_TIMEZONE_SET'));
			}
			if(!MAGIC_QUOTES_GPC){//数据转义
				$_GET=_addslashes($_GET);
				$_POST=_addslashes($_POST);
				$_COOKIE=_addslashes($_COOKIE);
			}
			self::init();
			//调试开始
			if(C("DEBUG")){
				debug::start("app_start");
			}
			if(C("DEBUG")){
				debug::show("app_start","app_end");
			}
			log::save();
		}
		//初始化配置 
		static function init(){
			self::config();
			url::parseUrl();
			if(C("APP_GROUP")){
				$config_file=MODULE_PATH.'/'.MODULE.'/config/config.php';
				if(is_file($config_file)){
					C(require $config_file);
				}
				self::extenload(MODULE_PATH.'/'.MODULE.'/config');//加载模块扩展配置
			}
			if(C("APP_GROUP")){//开启分组
				$control_file=MODULE_PATH.'/'.MODULE.'/'.C('CONTROL_DIR').'/'.CONTROL.C('CONTROL_FIX').C('CLASS_FIX').'.php';
			}else{
				$control_file=MODULE_PATH.'/'.MODULE.'/'.CONTROL.C('CONTROL_FIX').C('CLASS_FIX').'.php';
			}
			if(loadfile($control_file)){
				$control=A(MODULE.'.'.CONTROL);
				$action=ACTION;
				if(!method_exists($control, $action)){
					error("控制器".CONTROL."中的".$action."方法不存在");
				}
				$control->$action();
			}
		}

		//初始化配置文件
		static function config(){
			$config_file=CONFIG_PATH.'/config.php';
			if(is_file($config_file)){
				C(require $config_file);
			}
			self::extenload(CONFIG_PATH);//加载公共扩展配置
		}

		//扩展配置文件加载函数
		static function extenload($pre_path){
			if(strlen(C('LOAD_EXT_CONFIG'))){//开启扩展配置项，此配置项以逗号隔开的
				$fileArr=explode(',',C('LOAD_EXT_CONFIG'));
				if(!empty($fileArr)){//引入扩展配置文件
					foreach($fileArr as $v){
						$file=$pre_path.'/'.$v.'.php';
						if(is_file($file)){
							C(require $file);
						}
					}
				}
			}
		}
		//自动加载类文件
		static function autoload($classname){
			if(strpos($classname,C("CONTROL_FIX"))>0){//实例化控制器
				//于2015-3-10修改，待测试严谨性
				//error("错误：控制器必须由A()方法创建，或者类没有创建");
				//实例化模型
				if(C('APP_GROUP')){//开启分组，先加载分组模型
					$classfile=MODULE_PATH.'/'.MODULE.'/'.'/control/'.$classname.'.class.php';
					if(is_file($classfile)){//分组存在模型则加载
						loadfile($classfile);
					}else{//分组不存在模型则加载公共模型
						$classfile=MODULE_PATH.'/control/'.$classname.'.class.php';
						loadfile($classfile);
					}
				}else{//没有开启分组则直接加载公共模型
					$classfile=MODULE_PATH.'/model/'.$classname.'.class.php';
					loadfile($classfile);
				}		
			}else if(strpos(strtolower($classname),'model')>0 && strlen($classname)>5){
				//实例化模型
				if(C('APP_GROUP')){//开启分组，先加载分组模型
					$classfile=MODULE_PATH.'/'.MODULE.'/'.'/model/'.$classname.'.class.php';
					if(is_file($classfile)){//分组存在模型则加载
						loadfile($classfile);
					}else{//分组不存在模型则加载公共模型
						$classfile=MODULE_PATH.'/model/'.$classname.'.class.php';
						loadfile($classfile);
					}
				}else{//没有开启分组则直接加载公共模型
					$classfile=MODULE_PATH.'/model/'.$classname.'.class.php';
					loadfile($classfile);
				}		
			}else{//系统核心类库的加载
				$classfile=PHP_PATH.'/libs/bin/'.$classname.'.class.php';
				loadfile($classfile);
			}
		}
		//错误处理函数
		static function error($errno,$errstr,$errfile,$errline){
			switch($errno){
				case E_ERROR:
				case E_USER_ERROR:
					$errmsg="ERROR:[$errno]<strong>$errstr</strong>File:$errfile"."[$errline]";
					log::write("[$errno]<strong>$errstr</strong>File:$errfile"."[第{$errline}行]");
					error($errmsg);
					break;
				case E_NOTICE:
				case E_USER_NOTICE:
				case E_USER_WARNING:
				default:
					$errmsg="NOTICE:[$errno]<strong>$errstr</strong>File:$errfile"."[$errline]";
					log::set("[$errno]<strong>$errstr</strong>File:$errfile"."[第{$errline}行]","NOTICE");
					notice(func_get_args());
					break;
			}
		}
		//异常处理函数
		static function exception($e){
			error($e->show());
		}
	}
 ?>