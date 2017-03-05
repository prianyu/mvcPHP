<?php 
	/**
	 * 数据库操作类
	 */
	class db{
		//数据库连接
		protected $mysqli;
		//表名
		protected $tabname;
		//选项，存储各种查询条件
		protected $opt;
		//主键
		protected $pk='id';
		/**
		 * 构造方法 __construct
		 */
		function __construct($tabname,$pk=''){
			$this->config($tabname);
			if($pk){
				$this->pk=$pk;
			}
		}
		/**
		 * 配置方法  config
		 */
		protected function config($tabname){
			$this->db=new mysqli(C('DB_HOST'),C('DB_USER'),C('DB_PWD'),C('DB_NAME'));
			$this->tabname=C('DB_FIX').$tabname;
			if(mysqli_connect_errno()){
				die('数据库连接失败').mysqli_connect_errno();
			}
			$this->db->query('set names utf8');
			$this->opt['fields']="*";
			$this->opt['limit']=$this->opt['where']=$this->opt['order']=$this->opt['group']='';
		}
		/**
		 * 获取表字段 tbFiedlds
		 */
		function tbFields(){
			$result=$this->db->query("desc {$this->tabname}");
			$fieldArr=array();
			while($row=$result->fetch_assoc()){
				$fieldArr[]=$row['Field'];
			}
			return $fieldArr;
		}
		/**
		 * 获取查询字段 fields
		 */
		function fields($fields){
			$fieldArr=is_string($fields)?explode(',',$fields):$fields;
			if(is_array($fieldArr)){
				$field='';
				foreach($fieldArr as $v){
					$field.='`'.$v.'`'.',';
				}
				$field=rtrim($field,',');
				//return $field;
				$this->opt['fields']=$field;
				return $this;
			}
		}
		/**
		 * 查询条件  where /order /limit /group 
		 */
		function where($where){
			$this->opt['where']=is_string($where)?'where '.$where:'';
			return $this;
		}
		function order($order){
			$this->opt['order']=is_string($order)?'order by '.$order:'';
			return $this;
		}
		function limit($limit){
			$this->opt['limit']=is_string($limit)?'limit '.$limit:'';
			return $this;
		}
		function group($group){
			$this->opt['group']=is_string($group)?'group by '.$group:'';
			return $this;
		}
		/**
		 * 查询方法 select,结果为二维数组
		 */
	 function select(){
			$sql="select {$this->opt['fields']} from {$this->tabname} {$this->opt['where']} {$this->opt['group']} 
			  {$this->opt['order']} {$this->opt['limit']}";
			return $this->sql($sql);
		}
		/**
		 * 返回结果集 sql，结果为二维数组
		 */
		function sql($sql){
			$result=$this->doQuery($sql) or die($this->dbError());
			$data=array();
			while($row=$result->fetch_assoc()){
				$data[]=$row;
			}
			return $data;
		}
		/**
		 * 根据id查询单条记录 find，结果为一位数组
		 */
		function find($id){
			$sql="select {$this->opt['fields']} from {$this->tabname} where {$this->pk}={$id}";
			$result=$this->sql($sql);
			return 	$result[0];	
		}
		// 根据条件查询单个记录
		function findOne(){
			$sql="select {$this->opt['fields']} from {$this->tabname} {$this->opt['where']} limit 1";
			$result=$this->sql($sql);
			return $result[0];
		}
		/**
		 * 删除记录 delete
		 */
		function delete($id=''){
			if($id=='' && empty($this->opt['where'])){
				die('删除条件不能为空！');
			}
			if(!$id==''){
				if(is_array($id)){
					$id=implode(',',$id);
				}
				$this->opt['where']="where {$this->pk} in('".$id."')";
			}
			$sql="delete from {$this->tabname} {$this->opt['where']} {$this->opt['limit']}";
			return $this->query($sql);
		}
		/**
		 * 无结果集执行sql语句,返回影响行 query
		 */
		function query($sql){
			$this->doQuery($sql) or die($this->dbError());
			return $this->db->affected_rows;
		}
		/**
		 * 添加数据 insert
		 */
		function insert($args){
			is_array($args) or die('插入数据需要为一个数组');
			$this->fields(array_keys($args));
			$values=$this->values(array_values($args));
			$sql="insert into {$this->tabname} ({$this->opt['fields']}) values ({$values})";
			return $this->query($sql)>0? $this->db->insert_id:false;
		}
		/**
		 * 更新数据 update
		 */
		function update($args,$id=''){
			is_array($args) or die('更新的数据要求为一个数组！');
			if(empty($this->opt['where']) && $id==''){
				die ('没有设置更新的条件！');
			}
			$set='';
			$gpc=get_magic_quotes_gpc();
			while(list($k,$v)=each($args)){
				$v=!$gpc?addslashes($v):$v;
				$set.="`{$k}`='".$v."',";
			}
			$set=rtrim($set,',');
			if($id){
				$sql="update {$this->tabname} set {$set} where {$this->pk}={$id}";
			}else{
				$sql="update {$this->tabname} set {$set} {$this->opt['where']}";
			}			
			return $this->query($sql);
		}
		/**
		 * 统计记录总数 count
		 */
		function count($tabname=''){
			$tabname=$tabname==''?$this->tabname:$tabname;
			$sql='select * from '.$tabname.' '.$this->opt['where'];
			return $this->query($sql);
		}
	/**
	 * 将数组数据转化为字符串，并进行转义
	 */
	protected function values($value){
		if(!get_magic_quotes_gpc()){
			$strValue='';
			foreach($value as $v){
				$strValue.="'".addslashes($v)."',";
			}
		}else{
			foreach ($value as $v) {
				$strValue.="'$v',";
			}
		}
		return rtrim($strValue,',');
	}
    //执行sql语句，并写入日志文件
    protected function doQuery($sql){
    	log::sqlWrite($sql);
    	return $this->db->query($sql);
    }
	/**
	 *错误返回方法 dbError
	 */
	function dbError(){
		return $this->db->error;
	}

}
 ?>