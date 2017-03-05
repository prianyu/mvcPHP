# mvcPHP
这是一个自己在学习PHP框架时写的简单MVC框架，使用方法类似ThinkPHP,适合初学者用来理解框架的理念，代码和功能简单，仅供学习和参考使用。
##安装方法
将PHP文件夹拷贝至项目目录，在项目根目录新建index.php文件，文件内容如下：

```php   
<?php

	define('APP_PATH','.');//项目路径,必须
	define('ROOT_URL','http://localhost/app');//网站根目录
	define('MODULE_PATH',APP_PATH."/app");//项目名称
	//引入框架文件
	include "../PHP/PHP.php";
	//运行项目
	APP::run();
```

配置完成后运行项目，系统将生成相应的项目骨架，安装完成。

##目录结构

安装后将在项目根目录生成项目骨架，各文件和文件夹说明如下：

>`app`：由MODULE_PATH决定，项目的名称

>>`app/config`: 系统公共配置文件
>>`app/index`: 生成的默认的模块，里面包含的indexControl文件为默认运行的控制器。

>`temp`: 缓存文件夹。包含系统缓存目录`cache`，日志目录`log`，模板缓存目录`tpl`和编译文件`runtime.php`

>`template`:系统使用的模板文件目录

##访问方法

框架有一套自己的路径解析机制，支持以下路由模式进行访问：

 (1)常规模式：http://localhost/blog/index.php?m=admin&c=user&a=index

 (2)完整路由模式：http://localhost/blog/index.php/m/admin/c/user/a/index

 (3)精简路由模式：http://localhost/blog/index.php/admin/user/index

 (4)兼容模式：http://localhsot/blog/index.php?q=m/admin/c/user/a/index

 (5)精简兼容模式：http://localhost/blog/index.php?q=admin/user/index

 *参数说明：q为配置的get变量，PATHINFO_VAR，PATHINFO_DIL为路由分隔符

以上的访问方法均可以访问app/admin下的userCotrol里面的index方法。
其中，amdin为模块名，user为控制器名称前缀，index为控制器方法。

##配置文件

系统默认完整的配置项如下：

```php

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

```

可以通过自己自定义来覆盖默认的配置，以下三种方法可以覆盖默认的配置：

（1）在config目录下新建文件，以数组的形式返回相应的键值对；

（2）如果开启了前后端模块分离，可以在模块所在目录下新建config目录，以同样的方法在config目录下创建配置文件。如以上项目初始化后生成的app/index为系统默认的模块，在模块下新建config目录，然后新建file.php文件，内容如下：

```php

return array(
	"UPLOAD_EXT_SIZE"=>array("jpg"=>'',"jpeg"=>'',"gif"=>'',"bmp"=>'',"txt"=>'','doc'=>'',"rar"=>'',"php"=>''),//文件上传类型及大小
	"UPLOAD_PATH"=>APP_PATH."/data/upload/user/".date("Ymd"),//文件保存目录
	"UPLOAD_PATH_IMG"=>APP_PATH.'/data/upload/img/'.date("Ymd"),//图片保存路径
);

```

那么index模块下，所有的控制器涉及文件上传的配置将会被此文件覆盖。

（3）在写业务逻辑的时候用使用全局的C方法进行设置，如

	C('DEBUG',true);

所有配置文件的优先级如下C方法>模块下的config目录>项目目录下的config目录>框架默认配置

##数据库

1. 数据库配置，如下

	"DB_HOST"=>'localhost',//数据库主机
	
	"DB_USER"=>'root',//数据库用户
	
	"DB_PWD"=>'',//数据库密码
	
	"DB_NAME"=>'app',//数据库名
	
	"DB_FIX"=>'zs_',//表前缀

2. 数据模型Model
   
   一般一张数据表对应一个数据模型。使用时，在项目根目录下创建model目录，然后创建文件名为："模型名称+Model+.class.php"的文件，然后定义同名类集成Model类，并指定表明即可。如下为userModel.class.php的例子：

```php
  class userModel extends Model {
	protected $table = 'user';
    protected $pk = 'uuid';
  }
```

这样就定义了一个user模型，其中$table不需要指定前缀，如你的表名为zs_user,则在数据库配置时配置DB_FIX = "zs_"之后，在模型中指定表名时则无需带上"zs_";

定义了模型之后，我们就可以在控制器进行数据库的操作。我们使用new方法或框架提供的M方法进行数据模型实例化，如`M('user')`和`new userModel()`,然后对实例进行链式操作即可进行数据表操作，如下为查询所有的用户。

```php

M('user')->select();

```

目前提供的模型方法如下:

`count`:查询满足条件的数据的记录数

`getFields`： 获取表字段

`fields(string|array)`：设置查询的字段,可以是逗号分隔字符串或者数组

`where(string)`：设置查询的条件，目前仅支持字符串，后期会支持数组

`order(string)`：排序方式

`limit(number)`：查询限制的数量 

`group(string)`： 分组

`query($sql)`： 无结果执行sql语句

`add(array $data)`： 添加一条数据

`delete($id?)`： 根据主键删除一条记录或者根据条件删除

`update(array $data, $id?)`： 根据条件或者主键数据更新数据

`find($pk)`： 根据主键查询一条记录

`findOne`： 按照条件查询一条记录

**以上除了做链式操作时，select方法必须放在最后**

例子：

```php
$user = M('user');

$user->find(1);//查询主键为1的用户

$user->where('age>18')->fields('uuid,username,age')->order('age desc')->limit(20)->select();//按照指定的字段查询20条年龄大于18岁的用户，并按照年龄进行降序排列.

$user->update(array('username'=>'林黛玉'),3);//将主键为3的用户名字改为林黛玉

$user->where('status = 0')->update(array('status' => 1));//将所有status为0的用户修改为status为1

$user->delete(3);//删除用户主键为3的用户

$user->where('age < 10')->limit(10)->delete();//删除十条用户年龄小于10的记录

```
##视图View

框架的视图模板使用了Smarty模板引擎，你可以在控制器上直接使用Smarty分配数据的方法，在视图上使用Smarty的插值表达式。
如admin/userControl.class.php下有users方法

```php
function users(){
	$users = M('user')->select();
	$this->assign('users',$user);
	$this->display('users.html');
}

```

框架将自动找到template/admin/user/users.html文件并载入，之后即可以在users.html文件使用smarty视图语法，smarty定界符配置如下：

	//Smarty配置项

 	'SM_L_DEL'=>'{<',

 	'SM_R_DEL'=>'>}',

##工具函数

框架提供几个常用的工具函数

`p($var)`:打印数据

`_addslashes($arr)`: 递归转义诸如$_GET,$_POST,$_COOKIE等

`_strip($arr)`:标签恢复函数,用于读取数据库内容清除标签的转义，在页面正常显示

`_html`: 数据转义函数，用于转义Ueditor等上传的数据，返回转义后的内容供数据库使用

` error(string|array)`： 错误输出，调用会输出错误模板

`notice(string|array)`: 提示性错误输出

`del_space($file_name)`: 格式化编译文件 去空白，注释

`session($name,$value='',$set='')`:设置或获取session

`redirect($url, $time=0, $msg='')`: 重定向

`getExt($str)`： 获取上传文件格式


###说明

因为时间有限，没有将完整的框架使用方法进行详细的说明。后续将会增加文档说明，并附带整个框架的源码解析，谢谢。






