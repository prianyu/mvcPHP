<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台提醒</title>
	<style>
		#warnning{
			font-size:22px;
			color:#666;
			font-family: "Microsoft Yahei";
		}
		#write{font-size: 18px;font-family: "Microsoft Yahei";}
		#time{color:red;}
		#url{color:#249ff1;}
	</style>
</head>
<body>
	<p id='warnning'><?php echo $msg; ?></p>
	<div id='write'>
		页面将在<span id='time'><?php echo $time;?></span>秒后跳转...
		<a href="<?php echo $url;?>" id='url'>点击跳转</a>	
	</div>
</body>
<script>
	window.onload=function(){
		var time=document.getElementById('time');
		var timecount=time.innerHTML;		
		var url=document.getElementById('url').href;
		var t=setInterval(
				function(){
					if(timecount<=0){
						timecount=0;
						if(url){
							location.href=url;
						}else{
							document.getElementById('url').href='javascript:history.go(-2);';
							window.history.go(-2);
						}
						
						clearInterval(t);
						return;
					}
					timecount--;
					time.innerHTML=timecount;
				}
			,1000);

	}
</script>
</html>
