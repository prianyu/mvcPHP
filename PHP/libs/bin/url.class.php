<?php 
/**
 * 路由器处理类
 *支持模式：
 *(1)常规模式：http://localhost/blog/index.php?m=admin&c=user&a=index
 *(2)完整路由模式：http://localhost/blog/index.php/m/admin/c/user/a/index
 *(3)精简路由模式：http://localhost/blog/index.php/admin/user/index
 *(4)兼容模式：http://localhsot/blog/index.php?q=m/admin/c/user/a/index
 *(5)精简兼容模式：http://localhost/blog/index.php?q=admin/user/index
 *参数说明：q为配置的get变量，PATHINFO_VAR，PATHINFO_DIL为路由分隔符
 */
	final class url{
		//保存PATHINFO信息
		static $pathinfo;
		//解析URL
		static function parseUrl(){
			if(self::Pathinfo()!=false){
				$info=explode(C("PATHINFO_DIL"),self::$pathinfo);
				if($info[0]!=C("VAR_MODULE")){//精简的路由模式下（直接传参数值）
					$get['m']=$info[0];
					array_shift($info);
					$get['c']=$info[0];
					array_shift($info);
					$get['a']=$info[0];
					array_shift($info);
				}
				$count=count($info);
				for($i=0;$i<$count;$i+=2){//完整路由模式下（传参数名与参数值）
					$get[$info[$i]]=$info[$i+1];
				}
				$_GET=$get;
			}
			define("MODULE",isset($_GET['m'])?$_GET['m']:C("DEFAULT_MODULE"));
			define("CONTROL",isset($_GET['c'])?$_GET['c']:C("DEFAULT_CONTROL"));
			define("ACTION",isset($_GET['a'])?$_GET['a']:C("DEFAULT_ACTION"));

		}
		//解析PATHINFO
		static function Pathinfo(){
			//获得PATHINFO变量
			if(!empty($_GET[C('PATHINFO_VAR')])){//兼容模式
				$pathinfo=$_GET[C('PATHINFO_VAR')];
			}elseif(!empty($_SERVER['PATH_INFO'])){//路由器模式
				$pathinfo=$_SERVER['PATH_INFO'];
			}else{
				return false;
			}
			$pathinfo_html='.'.trim(C("PATHINFO_HTML"),'.');
			$pathinfo=str_ireplace($pathinfo_html,"",$pathinfo);
			$pathinfo=trim($pathinfo,"/");
			if(stripos($pathinfo,C("PATHINFO_DIL"))==false){
				return false;
			}
			self::$pathinfo=$pathinfo;
			return true;
		}
	}
 ?>