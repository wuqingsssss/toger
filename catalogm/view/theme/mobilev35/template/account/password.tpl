<?php echo $header35 ?>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/uc.css" rel="stylesheet"/>
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>"<a class='return' href='{$this->url->link('account/account')}'></a>",
       'center'=>'<a class="locate fz-18" >修改密码</a>',
       'right'=>''
)));?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="password">
<div id="uc_body">
		<div class="uc-body col-gray">
				<ul>
						<li class="bod-gray1">
								<span>
										<input type="password" class="fz-15" name="old_password" placeholder="请输入当前密码" />
								</span>
						</li>
						<?php if ($error_old_password) { ?>
							<span class="col-red fz-12 bt-10">&nbsp;<?php echo $error_old_password; ?></span>
						<?php } ?>
				</ul>
				<ul>
						<li class="bod-gray1">
								<span>
										<input type="password" class="fz-15" name="password" placeholder="请输入新密码" />
								</span>
						</li>
						<?php if ($error_password) { ?>
							<span class="col-red fz-12 bt-10">&nbsp;<?php echo $error_password; ?></span>
						<?php } ?>
				</ul>
				<ul>
						<li class="bod-gray1">
								<span>
										<input type="password" class="fz-15" name="confirm" placeholder="请重复输入新密码" />
								</span>
						</li>
						<?php if ($error_confirm) { ?>
							<span class="col-red fz-12 bt-10">&nbsp;<?php echo $error_confirm; ?></span>
						<?php } ?>
				</ul>
		</div>
		<div class="text-center uc-foot col-gray fz-13">
				<a onclick="$('#password').submit();"><span class="btn btn-block btn-green btn-submit">提交</span></a>
		</div>
</div>
</form>
<?php echo $this->getChild('module/navbar'); ?>
<div id="footer"></div>
<div class="overlay-container hidden" id="filter-success">
		<div class="overlay-content-container ">
				<div class="overlay-content col-white fz-18 bg-gray text-center cancel2">
						<ul>
								<li>
										<span>
												<a href="javascript:hide('#filter-success');" class="rol">&#10003;</a>
										</span>
								</li>
								<li class="fz-16">
										<span>您的密码修改成功</span>
								</li>
						</ul>
				</div>
		</div>
</div>
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/lib.min.js"></script>
<script type="text/javascript">
	function show(id) {
			$(id).removeClass('hidden');
	}
	function hide(id) {
			$(id).addClass('hidden');
	}
</script>