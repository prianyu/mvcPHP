<?php 
	//核心控制器类
   	// include PHP_PATH.'/plugins/smarty/Smarty.class.php';
	class Control{
		private $_view;
		function __construct(){//实例化smarty对象
			$this->_view=new Smarty();
			$template_dir=TEMPLATE_DIR.'/'.MODULE.'/'.CONTROL;
			$compile_dir=TPL_DIR.'/'.MODULE.'/'.CONTROL.'/compile';
			$this->_view->template_dir=$template_dir;
			$this->_view->compile_dir=$compile_dir;
			$this->_view->left_delimiter=C('SM_L_DEL');
			$this->_view->right_delimiter=C('SM_R_DEL');
			is_dir($template_dir) || mkdir($template_dir,0777,true);
			is_dir($compile_dir) || mkdir($compile_dir,0777,true);
			if(method_exists($this,'_beforeDone')){
				$this->_beforeDone();
			}
		}
		//smarty 变量分配
		public function assign($k,$v){
		 $this->_view->assign($k,$v);
	}

		//smarty模板显示
		public function display($file,$key=''){
		 $this->_view->display($file,$key);
	}
		//执行失败提醒
		public function error($msg='',$url='',$time=5){
			$msg=$msg==''?'执行失败':$msg;
			include C('WARNING_TPL');
		}
		//执行成功提醒
		public function success($msg='',$url='',$time=5){
			$msg=$msg==''?'执行成功':$msg;
			include C('WARNING_TPL');
		}
}
 ?>