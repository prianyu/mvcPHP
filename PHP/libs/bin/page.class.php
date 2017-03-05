<?php 
	/**
	 * 分页类
	 */
	class page{
		private $total_rows;//总记录数
		private $total_page;//总页数
		private $each_rows;//每页显示记录数
		private $page_rows;//显示的页数
		private $self_page;//当前页
		private $url;//当前url地址
		private $start_id;//当前页起始ID
		private $end_id;//当前页结束ID
		private $desc=array();//页码文字描述
		/**
		 * 初始化方法，进行配置 construct
		 */
		function __construct($total,$each_rows=10,$page_rows=10,$desc=''){
			$this->total_rows=$total;//总记录数
			$this->each_rows=$each_rows;//每页显示记录数
			$this->page_rows=$page_rows;//需要显示的页数
			$this->total_page=ceil($this->total_rows/$this->each_rows);//总页数
			$this->self_page=min(max((int)$_GET['page'],1),$this->total_page);//当前页
			$this->start_id=($this->self_page-1)*$this->each_rows+1;//当前页起始ID
			$this->end_id=min($this->self_page*$this->each_rows,$this->total_rows);//当前页结束ID
			$this->url=$this->requestUrl();//配置url地址
			$this->desc=$this->desc($desc);//配置页码文字描述
		}
		/**
		 *  配置url地址方法 requestUrl
		 */
		private function requestUrl(){
			//p($_SERVER);
			/**
			 * 以下注释部分是因为本系统使用了路由解析类url.class.php
			 * 若该分页类应用在其它非框架的地方应开启注释，并注释掉下方其它解析方式
			 */
		//-----------------------------------------------
			/*
			//读取url地址
			$url=isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			//解析url地址
			$url_Arr=parse_url($url);
			//p($url_Arr);
			if(isset($url_Arr['query'])){
				//解析请求参数
				parse_str($url_Arr['query'],$arr);
			//	p($arr);
				//删除page参数
				unset($arr['page']);
				//p($arr);
				//合并路径以及请求参数为标注的url地址
				$url=empty($arr)?$url_Arr['path'].'?'.'page=':$url_Arr['path'].'?'.http_build_query($arr).'&page=';
				//echo $url;
			}else{
				$url=strstr($url,'?')?$url.'page=':$url.'?page=';
			}
			*/
	//----------------------------------------------------------------
	//----------------------------------------------
			//读取url根地址
			$url=$_SERVER['SCRIPT_NAME'];
			//获取请求参数
			unset($_GET['page']);//删除page参数
			$get=$_GET;
			//p($get);
			$url=$this->setUrl($url,$get);
			return $url;
		}
		//设置路由方法
		public function setUrl($url,$param){
			$mode=C('PATHINFO_MODEL');//获取路由模式
			$del= C('PATHINFO_DIL');//路由分隔符
			$var=C('PATHINFO_VAR');//兼容符号
			switch ($mode) {
				case '0'://普通模式
					return $url.'?'.http_build_query($param).'&page=';
					break;
				case '1'://PATHINFO模式
					$url=$url.$del.str_replace('&', $del, http_build_query($param));
					return str_replace('=',$del,$url).$del.'page'.$del;
				default://兼容模式
					$url=$url.'?'.$var.'='.str_replace('=', $del, http_build_query($param));
					return str_replace('&',$del,$url).$del.'page'.$del;
					break;
			}
		}
	//----------------------------------------------
		/**
		 * 配置分页文字描述
		 * 'pre'=>'上一页',
		 * 'next'=>'下一页',
		 * 'first'=>'首页',
		 * 'end'=>'末页'
		 * 'unit'=>'条'
		 */
		private function desc($desc){
			//默认的文字描述
			$d=array(
					'pre'=>'上一页',
					'next'=>'下一页',
					'first'=>'首页',
					'end'=>'末页',
					'unit'=>'条'
				);
			if(empty($desc) || !is_array($desc)){
				return $d;
			}
			function filter($v){
				return !empty($v);
			}
			return array_merge($d,array_filter($desc,'filter'));
		}
		/**
		 * SQL 语句 limit
		 */
		public function limit(){
			return max(0,($this->self_page-1)*$this->each_rows).','.$this->each_rows;
		}
		/**
		 * 上一页，下一页，首页，末页 等
		 */
		//上一页 pre()
		public function pre(){
			return $this->self_page>1?"<a href='".$this->url.($this->self_page-1)."'>".$this->desc["pre"]."</a>":'';
		}
		//下一页 next()
		public function next(){
			return $this->self_page<$this->total_page?"<a href='".$this->url.($this->self_page+1)."'>".$this->desc["next"]."</a>":'';
		}
		// 首页 first()
		public function first(){
			return $this->self_page==1?'':"<a href='".$this->url."1'>".$this->desc["first"]."</a>";
		}
		//末页 end()
		public function end(){
			return $this->self_page==$this->total_page?'':"<a href='".$this->url.($this->total_page)."'>".$this->desc["end"]."</a>";
		}
		//当前页记录 nowpage
		public function nowpage(){
			return "第".$this->start_id.'—'.$this->end_id.$this->desc['unit'];
		}
		//返回当前页码 selfpage
		public function selfpage(){
			return $this->self_page;
		}
		//前几页 pres
		public function pres(){
			$num=$this->self_page-$this->page_rows;
			return $this->self_page>$this->page_rows?"<a href='{$this->url}{$num}'>前{$this->page_rows}{$this->desc['unit']}</a>":'';
		}
		//后几页 nexts
		public function nexts(){
			$num=$this->self_page+$this->page_rows;
			return $this->total_page>=$num?"<a href='{$this->url}{$num}'>后{$this->page_rows}{$this->desc['unit']}</a>":'';
		}
		//总记录数,组合统计信息
		public function count(){
			return "<span>总共{$this->total_page}页&nbsp;&nbsp;当前是第{$this->self_page}页;总计{$this->total_rows}{$this->desc['unit']}</span>";
		}
		/**
		 * 组合页码数组 pagelist
		 */
		public function pagelist(){
			$pagelist=array();
			$pagelist[$this->self_page]['url']='';
			$pagelist[$this->self_page]['str']=$this->self_page;//当前页
			$left=$this->self_page-1;//小于当前页
			$right=$this->self_page+1;//大于当前页
			$count=1;//当前页数目计数器
			$tem_arr=array();//临时数组
			while($count<$this->page_rows && $count<$this->total_page){
				if($left>=1 && $count<$this->page_rows){
					$tem_arr['url']=$this->url.$left;
					$tem_arr['str']=$left;
					array_unshift($pagelist,$tem_arr);
					$left--;
					$count++;
				}
				if($right<=$this->total_page && $count<$this->page_rows){
					$tem_arr['url']=$this->url.$right;
					$tem_arr['str']=$right;
					array_push($pagelist,$tem_arr);
					$right++;
					$count++;
				}
			}
			return $pagelist;
		}
		/**
		 * 字符串返回分页列表 strpage
		 */
		public function strpage(){
			$arr=$this->pagelist();
			$str='';
			foreach ($arr as $v) {
				$str.=empty($v['url'])?"<strong>{$v['str']}</strong>":"<a class='page' href='".$v['url']."'>{$v['str']}</a>";
			}
			return $str;
		}
		/**
		 * 下拉菜单列表 optionPage
		 */
		public function optionPage(){
			$arr=$this->pagelist();
			$str="<select class='pageSelect' onchange='javascript:location.href=this.options[selectedIndex].value'>";
			foreach ($arr as $v) {
				$str.=empty($v['url'])?"<option selected=selected value='{$this->url}{$v['str']}'>{$v['str']}</option>":"<option value='{$v['url']}'>{$v['str']}</option>";
			}
			$str.="</select>";
			return $str;
		}
		/**
		 * 输入数字选择页数 inputPage
		 */
		public function inputPage(){
			$str="<input value='{$this->self_page}' id='pageId' class='pageInput' onkeydown=\"
				javascript:if(event.keyCode==13){
					location.href='{$this->url}'+this.value;
				}
			\"/>
				<button onclick=\"
					javascript:var url='{$this->url}'+document.getElementById('pageId').value;
					location.href=url;
				\">跳转</button>
			";
			return $str;
		} 
		/**
		 * 选择显示类型并显示  show
		 */
		public function show($style_id){
			switch($style_id){
				case 1://字符串形式
					return $this->pre().$this->strpage().$this->next();break;
				case 2://下拉列表形式
					return $this->pres().$this->optionPage().$this->nexts();break;
				case 3://输入框形式
					return $this->inputPage();break;
			}
		}
	}
	/**
	 * 测试
	 */
/*	 
	include './db.class.php';
	$db=new db('user');
	$count=$db->count();//总记录数
	$each=5;
	$page_rows=ceil($count/$each);
	$page=new page($count,$each,2,array('unit'=>'篇'));
	$limit=$page->limit();
	$list=$db->limit($limit)->select();
	$pagelist=$page->show(1);
	$pagelist2=$page->show(2);
	$pagelist3=$page->show(3);
	require '../tpl/page.html';
*/

 ?>