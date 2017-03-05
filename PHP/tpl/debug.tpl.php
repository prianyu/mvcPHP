<html>
	<head>
		<title></title>
		<style type="text/css">
			*{margin:0px;paddig:0px;}
			body{margin:20px;}
			#debug{position:absolute;width:880px;border:solid 1px #dcdcdc;padding:10px;bottom:0px;background:#fff;}
			#debug fieldset{padding:10px;font-size:14px;}
			#debug fieldset legend{padding:5px;}
			#debug fieldset p{background-color: #888;font-size:12px;color:#fff;margin-top:10px;padding:3px;}
		</style>
	</head>
	<body>
		<div id="debug">
			<h2>DEBUG</h2>
			<?php if(isset($e['message'])){ ?>
			<fieldset>
				<legend>ERROR</legend>
				<?php  echo $e['message'];?>
			</fieldset>
			<?php } ?>
			<?php if(isset($e['info'])){ ?>
			<fieldset>
				<legend>TRACE</legend>
				<?php echo $e['info']; ?>
			</fieldset>
			<?php } ?>
		</div>
	</body>
</html>