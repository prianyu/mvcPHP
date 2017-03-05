<?php 
	class log{
		static $log=array();
		//记录日志内容
		static function set($message,$type='NOTICE'){
			if(in_array($type, C("LOG_TYPE"))){
				$date=date('y-m-d H:i:s');
				self::$log[]="[".$type."]:".$message."(".$date.")\r\n";
			}	
		}
		//存储日志内容到日志文件
		static function save($message_type=3,$destination=null,$extraheaders=null){
			if(!C("LOG_START")){
				return;
			}
			if(is_null($destination)){
				$destination=LOG_PATH.'/'.date("y_m_d h").".log";
			}
			if($message_type==3){
				if(is_file($destination) && filesize($destination)>C("LOG_SIZE")){
					rename($destination, dirname($destination).'/'.time().'.log');
				}
			}
			error_log(implode('',self::$log),$message_type,$destination);
		}
		//直接写入日志文件
		static function write($message,$type="ERROR",$message_type=3,$destination=null,$extraheaders=null){
			if(!C("LOG_START")){
				return;
			}
			if(is_null($destination)){
				$destination=LOG_PATH.'/'.date("y_m_d h").".log";
			}
			if($message_type==3){
				if(is_file($destination) && filesize($destination)>C("LOG_SIZE")){
					rename($destination, dirname($destination).'/'.time().'.log');
				}
			}
			$date=date("y-m-d H:i:s");
			$message="[$type]:".$message."(".$date.")\r\n";
			error_log($message,$message_type,$destination);
		}
		//记录数据库操作
		static function sqlWrite($sql,$destination=null){
			if($sql!=''){
				if(is_null($destination)){
					$destination=LOG_PATH.'/sql_'.date("y_m_d").'.log';
				}
				if(is_file($destination) && filesize($destination)>C("LOG_SIZE")){
					rename($destination,dirname($destination).'/'.time().'.log.bak');
				}
				if(!file_exists($destination)){touch($destination);}
				$sql="(".date("h:i:sa").")".$sql."\r\n";
				$fh=fopen($destination,'ab');
				fwrite($fh,$sql);
				fclose($fh);
			}
		}
	}
 ?>