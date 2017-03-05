<?php 
/**
 * 文件上传类
 */
class upload{
	//文件上传类型
	private $exts;
	//文件上传大小
	public $size;
	//文件保存目录
	public $path;
	//文件上传表单域
	public $field;
	//错误信息
	public $error;
	//是否开启缩略图
	public $thumb_on;
	//缩略图参数
	public $thumb;
	//水印处理
	public $water_on;
	//水印参数
	public $water;
	//上传文件成功信息
	public $uploadFiles=array();
	/*构造函数
	 * @param $path 文件存储路径
	 * @param $ext_size  数组  支持的文件类型与大小
	 * @param $water_on bool 是否开启水印
	 * @param $thumb_on bool 是否开启缩略图
	 * @param $water  数组  水印配置项
	 * @param @thumb  数组  缩略图配置项
	 *
	 */
	function __construct($path='',$ext_size=array(),$water_on=1,$thumb_on=1,$water=array(),$thumb=array()){
		$this->water_on=$water_on;//是否开启水印
		$this->water=$water;//水印配置项	
		$this->thumb_on=$thumb_on;//是否开启缩略图
		$this->thumb=$thumb;//缩略图配置项
		$this->path=empty($path)?C("UPLOAD_PATH"):$path;//文件保存路径
		$this->exts=empty($ext_size)?array_keys(C("UPLOAD_EXT_SIZE")):array_keys($ext_size);
		$this->size=empty($ext_size)?C('UPLOAD_EXT_SIZE'):$ext_size;
	}
	/**
	 * 文件上传
	 */
	public function up(){
		if(!$this->checkDir()){
			$this->error="目录".$this->path."不存在或者不可写！";
			return false;
		}
		$files=$this->format();
		foreach($files as $v){
			$info=$this->path_info($v['name']);
		//	print_r($info);
			$v['ext']=strtolower($info['extension']);
			$v['filename']=$info['filename'];
		//	print_r($v);
			if(!$this->checkFile($v)){
				continue;
			}
			$uploadFile=$this->save($v);
			if($uploadFile){
				$this->uploadFiles[]=$uploadFile;
			}
		}
		return $this->uploadFiles;
	}
	/**
	 * 格式化文件
	 */
	private function format(){
		$files=$_FILES;
		if(!isset($files)){
			$this->error="没有上传任何文件";
			return false;
		}
		$info=array();
		$n=0;
		foreach($files as $v){
			if(is_array($v['name'])){
				$count=count($v['name']);
				for($i=0;$i<$count;$i++){
					foreach($v as $m=>$k){
						$info[$n][$m]=$k[$i];
					}
					$n++;
				}
			}else{
				$info[$n]=$v;
				$n++;
			}
		}
		return $info;
	}
	/**
	 * 目录验证
	 */
	private function checkDir(){
		$path=$this->path;
		if(!dir::create($path) || !is_writeable($path)){
			return false;
		}
		$img_path=C("UPLOAD_PATH_IMG");
		if(!dir::create($img_path) || !is_writable($path)){
			return false;
		}
		return true;
	}

	/**
	 * 文件验证
	 */
	private function checkFile($file){
		if($file['error']!=0){
			$this->error($file['error']);
			return false;
		}
		$ext_size=empty($this->size)?C("UPLOAD_EXT_SIZE"):$this->size;
		$ext=strtolower($file['ext']);
		if(!in_array($ext,$this->exts)){
			$this->error="非法的文件类型";
			return false;
		}
		if(!is_uploaded_file($file['tmp_name'])){
			$this->error="非法文件";
			return false;
		}
		return true;
	}
	/**
	 * 保存上传的文件
	 */
	private function save($v){
		$is_img=0;//判断是否上传图片
		$filePath=$this->path.'/'.$v['filename'].time().mt_rand(100000,999999).'.'.$v['ext'];
		//echo $filePath;
		if(in_array($v['ext'],array("jpg","jpeg","bmp","gif","png","psd")) && getimagesize($v['tmp_name'])){
			//判断上传的文件是否为图片
			$filePath=C("UPLOAD_PATH_IMG").'/'.time().mt_rand(100000,999999).".".$v['ext'];
			$is_img=1;
		}
		if(!move_uploaded_file($v['tmp_name'],iconv("UTF-8", "GBk", $filePath))){
			//移动上传文件失败
			$this->error="上传文件失败";
			return false;
		}
		if(!$is_img){
			return array("path"=>$filePath);
		}
		/**
		 *对图像进行水印或缩略图处理
		 */
		$img=new Image();//实例化图像类
		//缩略图处理
		if($this->thumb_on){
			$args=array();
			if(is_array($this->thumb) && !empty($this->thumb)){
				array_unshift($args,$filePath,"");
				$args=array_merge($args,$this->thumb);
			}else{
				array_unshift($args, $filePath);
			}
			$thumbfile=call_user_func_array(array($img,"thumb"),$args);
		}
		//水印处理
		if($this->water_on){
			$args=array();
			if(is_array($this->water) && !empty($this->water)){
				array_unshift($args,$filePath,"");
				$args=array_merge($args,$this->water);
			}else{
				array_unshift($args, $filePath);
			}
			call_user_func_array(array($img,"water"),$args);
		}
		return array("path"=>$filePath,"thumb"=>$thumbfile);
	}
	/**
	 * 获取错误类型
	 */
	private function error($type){
		switch($type){
			case UPLOAD_ERR_INI_SIZE:
				$this->error="文件大小超过配置文件指定大小";
				break;
			case UPLOAD_ERR_FORM_SIZE:
				$this->error="上传文件大小超过HTML表单指定的大小";
				break;
			case UPLOAD_ERR_NO_FILE:
				$this->error="没有上传文件";
				break;
			case UPLOAD_ERR_PARTIAL:
				$this->error="文件上传不完整";
				break;
			case UPLOAD_ERR_NO_TMP_DIR:
				$this->error="长传文件临时目录不存在";
				break;
			case UPLOAD_ERR_CANT_WRITE:
				$this->error="无法写入临时文件";
				break;

		}
	}
	/**
	 * 获取错误的处理方法
	 */
	public function geterror(){
		return $this->error;
	}
	/**
	 * 获取文件信息的函数,去除系统pathinfo中文乱码错误
	 */
	public function path_info($filepath){
		$path_parts = array();   
	    $path_parts ['dirname'] = rtrim(substr($filepath, 0, strrpos($filepath, '/')),"/")."/";   
	    $path_parts ['basename'] = ltrim(substr($filepath, strrpos($filepath, '/')),"/");   
	    $path_parts ['extension'] = substr(strrchr($filepath, '.'), 1);   
	    $path_parts ['filename'] = ltrim(substr($path_parts ['basename'], 0, strrpos($path_parts ['basename'], '.')),"/");   
	    return $path_parts;   
	}
}	
 ?>