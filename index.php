<?php
	define('APP_PATH','.');//项目路径
	define('ROOT_URL','http://localhost/demo');//网站根目录
	define('MODULE_PATH',APP_PATH."/demo");//项目名称
	define('PUBLIC_DIR',ROOT_URL.'/'.'public');//公共文件目录
	define('HOME_PUBLIC',PUBLIC_DIR.'/index/');//前台模板公共目录
	define('ADMIN_PUBLIC',PUBLIC_DIR.'/admin');//后台模板公共目录
	define('__APP__',"http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);//应用主页


    //全局打印信息的函数
	function p($name){
		echo "<pre style='font-size:14px;color:#666'>";
		print_r($name);
		echo "</pre>";
	}
	/**
 * URL重定向
 * @param string $url 重定向的URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return void
 */
function redirect($url, $time=0, $msg='') {
    //多行URL地址支持
    $url        = str_replace(array("\n", "\r"), '', $url);
    $url="http://".ltrim($url,'http://');
        $msg    .= " 系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}

/**
 * URL组装 支持不同URL模式
 * @param string $url URL表达式，格式：'[分组/模块/操作#锚点@域名]?参数1=值1&参数2=值2...'
 * @param string|array $vars 传入的参数，支持数组和字符串
 * @param string $suffix 伪静态后缀，默认为true表示获取配置值
 * @param boolean $redirect 是否跳转，如果设置为true则表示跳转到该URL地址
 * @param integer $time 重定向的等待时间（秒）
 * @param string $msg 重定向前的提示信息
 * @return string
 */
function U($url='',$vars='',$suffix=true,$redirect=false,$time=0,$msg='') {
    // 解析URL
    $info   =  parse_url($url);
    $url    =  !empty($info['path'])?$info['path']:ACTION;

    // 解析参数
    if(is_string($vars)) { // aaa=1&bbb=2 转换成数组
        parse_str($vars,$vars);
    }elseif(!is_array($vars)){
        $vars = array();
    }
    if(isset($info['query'])) { // 解析地址里面参数 合并到vars
        parse_str($info['query'],$params);
        $vars = array_merge($params,$vars);
    }
    // URL组装
    $depr = C('PATHINFO_DIL');//获取PATHINFO分隔符
    if($url) {
        if(0=== strpos($url,'/')) {// 定义路由
            $route      =   true;
            $url        =   substr($url,1);
            if('/' != $depr) {
                $url    =   str_replace('/',$depr,$url);
            }
        }else{
            if('/' != $depr) { // 安全替换
                $url    =   str_replace('/',$depr,$url);
            }
            // 解析分组、模块和操作
            $url        =   trim($url,$depr);
            $path       =   explode($depr,$url);
            $var        =   array();
            $var[C('VAR_ACTION')]       =   !empty($path)?array_pop($path):ACTION;
            $var[C('VAR_CONTROL')]       =   !empty($path)?array_pop($path):CONTROL;
            $var[C('VAR_MODULE')]		=   !empty($path)?array_pop($path):MODULE;
        }
    }

    if(C('PATHINFO_MODEL') == 0) { // 普通模式URL转换
        $url =  __APP__.'?'.http_build_query(array_reverse($var));
        if(!empty($vars)) {
            $vars   =   urldecode(http_build_query($vars));
            $url   .=   '&'.$vars;
        }
    }else{ // PATHINFO模式或者兼容URL模式
        if(isset($route)) {
            $url    =   __APP__.'/'.rtrim($url,$depr);
        }else{
            $url    =   __APP__.'/'.implode($depr,array_reverse($var));
        }
        if(!empty($vars)) { // 添加参数
            foreach ($vars as $var => $val){
                if('' !== trim($val))   $url .= $depr . $var . $depr . urlencode($val);
            }
        }
        if($suffix) {
            $suffix  =  $suffix===true?C('PATHINFO_HTML'):$suffix;
            if($pos = strpos($suffix, '|')){
                $suffix = substr($suffix, 0, $pos);
            }
            if($suffix && '/' != substr($url,-1)){
                $url  .=  '.'.ltrim($suffix,'.');
            }
        }
    }
    if($redirect) // 直接跳转URL
        redirect($url,$time,$msg);
    else
        return $url;
}


//获取上传文件的格式
function getExt($str){
    return end(explode('.',$str));
}
 //引入框架文件
include "./PHP/PHP.php";
//运行项目
APP::run();
