<?php
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$iphone = (strpos($agent, 'iphone')) ? true : false;
	$ipad = (strpos($agent, 'ipad')) ? true : false;
	$android = (strpos($agent, 'android')) ? true : false;
	if($iphone || $ipad)
	{
	 echo "<script>window.location.href='https://itunes.apple.com/cn/app/qing-nian-cai-jun/id1035229702'</script>"; //iphone
	}
	if($android){
	 echo "<script>window.location.href='http://a.app.qq.com/o/simple.jsp?pkgname=com.qncj.orderdishes'</script>";//android
	}
?>