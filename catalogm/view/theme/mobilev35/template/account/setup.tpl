<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/us.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<div id="header">
    <div class="pull-left">
        <a class="return" href="<?php echo $this->url->link('account/account'); ?>"></a>
    </div>
    <div class="text-center">
        <a class="fz-18">设置</a>
    </div>
</div>
<!-- 公共头结束 -->

<div class="fz-16 us-content1">
    <a href="<?php echo $this->url->link('account/edit'); ?>">个人资料</a>
    <a href="<?php echo $this->url->link('account/password'); ?>">修改密码</a>
    <a href="<?php echo $this->url->link('information/information&information_id=45'); ?>">关于我们</a>
		<a href="javascript:logout_alert();" >退出登录</a>
</div>


<?php echo $footer35?>
<script type="text/javascript">
	function logout_alert(){
		var url = "<?php echo $this->url->link('account/logout'); ?>";
		_.confirm('是否确认退出?', function () {
				location.href = url;
			})
	}
</script>