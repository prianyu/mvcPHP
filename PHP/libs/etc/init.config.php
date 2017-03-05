<?php 
return array(
	//***********数据库配置项*************
	"DB_HOST"=>'localhost',//数据库主机
	"DB_USER"=>'root',//数据库用户
	"DB_PWD"=>'',//数据库密码
	"DB_NAME"=>'',//数据库名
	"DB_FIX"=>'',//表前缀
	//***********系统配置*******************
	"SHOW_TIME"=>1,//显示运行时间
	"DEBUG"=>0,//开启调试模式
	"DEBUG_TPL"=>PHP_PATH.'/tpl/debug.tpl.php',//错误异常模板
	"WARNING_TPL"=>PHP_PATH.'/tpl/warning.tpl.php',//控制器执行失败或者成功提示模板
	"ERROR_MESSAGE"=>"页面错误",//关闭debug调试显示的错误信息
	"DATE_TIMEZONE_SET"=>"PRC",//默认时区
	"NOTICE_SHOW"=>0,//提示性信息模式
	//***********路由器配置项PATHINFO***************
	"PATHINFO_DIL"=>'/',//PATHINFO分隔符
	"PATHINFO_VAR"=>'q',//兼容模式GET变量
	"PATHINFO_HTML"=>'.html',//默认伪静态后缀
	"PATHINFO_MODEL"=>1,//路由模式，默认为1，即PATHINFO模式,2为兼容模式，0为普通模式
	//**************日志配置项**********************
	"LOG_START"=>1,//是否开启日志记录
	"LOG_SIZE"=>2000000,//日志文件大小
	"LOG_TYPE"=>array('SQL','NOTICE','ERROR'),//日志类型
	//********************项目配置项**********************
	"DEFAULT_MODULE"=>'index',//默认的模块名
	"DEFAULT_CONTROL"=>'index',//默认的控制器
	"DEFAULT_ACTION"=>'index',//默认的方法
	"CONTROL_FIX"=>'Control',//默认的控制器后缀规则
	"CLASS_FIX"=>'.class',//默认的类后缀规则
	'APP_GROUP'=>false,//是否开启分组
	"CONTROL_DIR"=>'control',//默认的控制器存放目录的名称
	"MODEL_DIR"=>'model',//默认的模型存放目录的名称
	//*********************全局变量***************************
	"VAR_MODULE"=>"m",//模块变量
	"VAR_CONTROL"=>"c",//控制器变量
	"VAR_ACTION"=>'a',//方法变量
	//**********验证码配置项***********************
	'VERIFY_LENGTH'=> 4,//验证码长度
	'VERIFY_WIDTH'=>250,//验证码图片宽度(像素)
	'VERIFY_HEIGHT'=> 60,//验证码图片高度(像素)
	'VERIFY_BGCOLOR'=> '#F3FBFE',//验证码背影颜色(16进制色值)
	'VERIFY_SEED'=>'3456789aAbBcCdDeEfFgGhHjJkKmMnNpPqQrRsStTuUvVwWxXyY',//验证码种子
	'VERIFY_FONTFILE'=> PHP_PATH.'/data/yahei.ttf',//验证码字体文件
	'VERIFY_SIZE'=>30,//验证码字体大小
	'VERIFY_COLOR'=>'#444444',//验证码字体颜色(16进制色值)
	'VERIFY_NAME'=>'verify',//SESSION识别名称
	'VERIFY_FUNC'=>'strtolower',//存储验证码到SESSION时使用函数
	//*********文件上传***************************
	"UPLOAD_EXT_SIZE"=>array("jpg"=>'',"jpeg"=>'',"gif"=>'',"bmp"=>'',"txt"=>'','doc'=>'',"rar"=>'',"php"=>''),//文件上传类型及大小
	"UPLOAD_PATH"=>APP_PATH."/data/upload/user/".date("Ymd"),//文件保存目录
	"UPLOAD_PATH_IMG"=>APP_PATH.'/data/upload/img/'.date("Ymd"),//图片保存路径

	//**********水印配置项****************************
	'WATER_IMAGE'=>PHP_PATH.'/data/water.png',//水印图路径 
	'WATER_POS'=>9,//水印位置
	'WATER_ALPHA'=>60,//水印透明度
	'WATER_COMPRESSION'=>80,//JPEG图片压缩比
	'WATER_TEXT'=>'www.webname.com',//水印文字
	'WATER_ANGLE'=> 0,//水印文字旋转角度
	'WATER_FONTSIZE'=>30,//水印文字大小
	'WATER_FONTCOLOR'=>'#670768',//水印文字颜色
	'WATER_FONTFILE'=>PHP_PATH.'/data/yahei.ttf',//水印文字字体文件(写入中文字时需使用支持中文的字体文件)
	'WATER_CHARSET'=>'UTF-8',//水印文字字符编码
	'VERIFY_FUNC'=>'md5',//验证码session处理函数


	//**************缩略图配置项*******************
	'THUMB_WIDTH'=>200,//缩略图宽度
	'THUMB_HEIGHT'=>120,//缩略图高度
	'THUMB_PATH'=>APP_PATH.'/data/upload/img/thumb/',//缩略图保存路径
);
 ?>