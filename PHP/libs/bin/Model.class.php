<?php 
	//基本模型类  支持自动验证，自动填充，自动过滤
	class Model{
		protected $table=null;//数据表
		protected $db=null;//数据库对象
		protected $fields=array();//过滤数组，用于存放实际需要的字段名
		protected $auto=array();//自动填充数组，用于填充需要但未提交字段
		protected $valid=array();//验证字段与规则，存放需要验证的字段以及字段格式化
		protected $err=array();//错误存放数组，用于字段验证不通过时的错误提示
		protected $pk='id';
		public function __construct($table=''){
			if($table!=''){
				$this->table=$table;
			}
			$this->db=new db($this->table,$this->pk);
		}
		public function table($table){//设置数据表
			$this->table=$table;
		}
//***************************基本的增删改查操作*******************、
		//无结果执行sql语句
		public function query($sql){
			return $this->db->query($sql);
		}
		//添加数据  @param data类型为一数组
		public function add($data){
			return $this->db->insert($data);
		}
		//删除数据  @param id为一个删除条件，若为空应结合where()方法连贯操作
		public function delete($id){
			return $this->db->delete($id);
		}
		//修改数据  @param data 为修改后的数据，需要结合where()方法查询
		public function update($data,$id=''){
			return $this->db->update($data,$id);
		}
		//查询所有记录
		public function select(){
			return $this->db->select();
		}
		//根据id查看一条记录
		public function find($id){
			return $this->db->find($id);
		}
		// 根据条件查询一条记录
		function findOne(){
			return $this->db->sql($sql);
		}
		//根据sql语句查询并返回结果集
		function sql($sql){
			return $this->db->sql($sql);
		}
		//统计记录数
		public function count(){
			return $this->db->count();
		}
//****************查询条件等，实现连贯操作*******************
		//获取表字段
		public function getFields(){
			return $this->db->tbFields();
		}
		//设置查询字段 @param data为数组类型或用逗号隔开的字符串类型
		public function fields($data){
			return $this->db->fields($data);
		}
		//查询条件  where /order /limit /group
		public function where($where){
			return $this->db->where($where);
		}
		public function order($order){
			return $this->db->order($order);
		}
		public function limit($limit){//limit为字符串类型，为数值类型时不起作用
			return $this->db->limit($limit);
		}
		public function group($group){
			return $this->db->group($group);
		}
//**********************自动完成方法***************************
		//自动过滤  将表单中上传的数据过滤掉非法字段
		public function _facade($array=array()){
			$data=array();
			foreach ($array as $k => $v) {
				if(in_array($k,$this->fields)){
					$data[$k]=$v;
				}
			}
			return $data;
		}
		//自动填充  按照指定的填充规则进行填充未填写的信息
		public function _autofill($data){
			foreach($this->auto as $k=>$v){			
				if (!array_key_exists($v[0], $data)) {//判断auto数组的键值是否在已获得的数组
					switch ($v[1]) {//监测数组的值获取的类型
						case 'value':
							$data[$v[0]]=$v[2];
							break;					
						case 'function':
							$data[$v[0]]=call_user_func($v[2]);
							break;
					}
				}
			}
			return $data;
		}
		/**
		 *自动验证方法
		 *验证的判断格式为：
		 *	$this->valid=array(
		 *		array(验证字段，0/1/2(验证场景)，错误提示，require/in(某种情况)/between(范围)/length(某个范围))
		 *			)
		*/
		public function _validate($data){
			if(empty($this->valid)){
				return true;//若无设置验证规则与字段，验证默认通过
			}
			$this->err=array();
			//若设置了验证规则与验证字段
			foreach ($this->valid as $k => $v) {
				switch ($v[1]) {
					case 0://不能为空的情况
						if (!isset($data[$v[0]])) {//检验为空，返回错误信息
							$this->err[]=$v[2];
							return false;
						}
						if (!$this->check($data[$v[0]],$v[3])) {//不为空则验证格式
							$this->err[]=$v[2];
							return false;
						}
					break;
					case 1://可以为空时若为空则不验证，若不为空则验证格式
					if (isset($data[$v[0]])) {
						if(!$this->check($data[$v[0]],$v[3],$v[4])){
							$this->err[]=$v[2];
							return false;
						}
					}
					break;
					case 2://若有此字段且不为空，则对内容格式进行验证
					if(isset($data[$v[0]]) && !empty($data[$v[0]])){
						if(!$this->check($data[$v[0]],$v[3],$v[4])){
							$this->err[]=$v[2];
							return false;
						}
					}
					break;

					case 3://若变量存在且为数字，对其数字格式进行验证
					if(isset($data[$v[0]]) && is_numeric($data[$v[0]])){
						if(!$this->check($data[$v[0]],$v[3],$v[4])){
							$this->err[]=$v[2];
							return false;
						}
					}
				}
			}
			return true;
		}
		//规则验证函数  require/in(某种情况)/between(范围)/length(某个范围) 给$validate调用
		public function check($value,$rule='',$parm=''){
				switch ($rule) {
					case 'require':
						return !empty($value);
					case 'in':
						$tmp=explode(',', $parm);
						return in_array($value, $tmp);
					case 'number':
						return is_numeric($value);
					case 'email':
						return filter_var($value,FILTER_VALIDATE_EMAIL)!==false;
					case 'between':
						list($min,$max)=explode(',',$parm);
						return $value>=$min && $value<=$max;
					case 'length':
						list($min,$max)=explode(',',$parm);
						$len=strlen($value);
						return $len>=$min && $len<=$max;
					case 'big': 
						return $value>$parm;
					case 'biglength':
						return mb_strlen($value,'utf-8')>$parm;
					default:
						return false;
				}
		}
		//返回验证错误信息
		public function getErr(){
			return $this->err;
		}
	}
?>