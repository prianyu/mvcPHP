<?php 
	class debug{
		//运行时间
		static $runtime;
		//内存占用
		static $memory;
		//内存峰值
		static $memory_peak;
		//调试开始
		static function start($start){
			self::$runtime[$start]=microtime(true);
			self::$memory[$start]=memory_get_usage();
			self::$memory_peak[$start]=memory_get_peak_usage();
		}
		//运行时间
		static function runtime($start,$end='',$decimals=6){
			if(!isset(self::$runtime[$start])){
				error("必须设置项目运行起始点！");
			}
			if(empty(self::$runtime[$end])){
				self::$runtime[$end]=microtime(true);
				return number_format(self::$runtime[$end]-self::$runtime[$start],$decimals);
			}
		}
		//内存峰值占用
		static function memory_peak($start,$end=''){
			if(!isset(self::$memory_peak[$start])){
				return false;
			}
			if(!empty($end)){
				self::$memory_peak[$end]=memory_get_peak_usage();
			}
			return max(self::$memory_peak[$start],self::$memory_peak[$end]);
		}
		//项目运行结果
		static function show($start,$end){
			$message="项目运行时间是".self::runtime($start,$end)."&nbsp;&nbsp;内存峰值是".
			number_format(self::memory_peak($start,$end)/1024)."KB";
			$load_file_list=loadfile();
			$info='';
			$i=1;
			foreach($load_file_list as $k=>$v){
				$info.="[".$i++."]".$k."<br/>";
			}
			$e['info']=$info."<p>$message</p>";
			include C("DEBUG_TPL");
		}
	}
 ?>	