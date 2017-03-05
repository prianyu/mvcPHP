<?php 
	final class dir{
		//转换为标准的目录结构
		static function dir_path($dirname){
			$dirname=str_ireplace("\\", '/',$dirname);
			return substr($dirname,-1)=='/'?$dirname:$dirname.'/';
		}
		//获得文件的扩展名
		static function get_ext($filename){
			if(!is_file($filename)){
				return;
			}
			return substr(strrchr($filename, '.'),1);
		}
		//获取目录的内容
		static function tree($dirname,$exts='',$son=0,$list=array()){
			$dirname=self::dir_path($dirname);
			if(is_array($exts)){
				$exts=implode('|',$exts);
			}
			static $id=0;
			foreach(glob($dirname."*") as $v){
				$id++;
				if(!$exts || preg_match("/\.($exts)/i",$v)){
					$list[$id]['name']=basename($v);
					$list[$id]['path']=realpath($v);
					$list[$id]['type']=filetype($v);
					$list[$id]['ctime']=filectime($v);
					$list[$id]['atime']=fileatime($v);
					$list[$id]['filesize']=filesize($v);
					$list[$id]['iswrite']=is_writable($v);
					$list[$id]['isread']=is_readable($v);
				}
				if($son){
					if(is_dir($v)){
						$list=self::tree($v,$exts,$son,$list);//$list以参数形式再次传入
					}
				}
			}
			return $list;
		}
		//只获得目录结构
		static function tree_dir($dirname,$son=0,$pid=0,$list=array()){
			$dirname=self::dir_path($dirname);
			static $id=0;
			foreach(glob($dirname."*") as $v){
				if(is_dir($v)){
					$id++;
					$list[$id]['id']=$id;
					$list[$id]['pid']=$pid;
					$list[$id]['name']=basename($v);
					$list[$id]['path']=realpath($v);
					if($son){
						$list=self::tree_dir($v,$son,$id,$list);
					}
				}
			}
			return $list;
		}
	//删除目录
		static function del($dirname){
			$dirPath=self::dir_path($dirname);
			if(!is_dir($dirPath)) return false;
			foreach(glob($dirPath."*") as $v){
				is_dir($v)?self::del($v):unlink($v);
			}
			return rmdir($dirPath);
		}
	//创建目录，支持层级创建
		static function create($dirname,$auth="0777"){
			$dirPath=self::dir_path($dirname);
			if(is_dir($dirPath)) return true;
			$dirArr=explode('/',$dirPath);
			$dir='';
			foreach($dirArr as $v){
				$dir.=$v.'/';
				if(is_dir($dir)) continue;
				mkdir($dir,$auth);
			}
			return is_dir($dirPath);
		}
	//复制目录的内容
		static function copy($oldDir,$newDir){
			$oldDir=self::dir_path($oldDir);
			$newDir=self::dir_path($newDir);
			if(!is_dir($oldDir)) error("复制失败：".$oldDir."目录不存在！");
			if(!is_dir($newDir)) self::create($newDir);
			foreach(glob($oldDir."*") as $v){
				$toFile=$newDir.basename($v);
				if(is_file($toFile)) continue;
				if(is_dir($v)){
					self::copy($v,$toFile);
				}else{
					copy($v,$toFile);
					chmod($toFile,0777);
					echo substr(base_convert(@fileperms($toFile),10,8),-4);
				}
			}
			return true;
		}		
	}
 ?>