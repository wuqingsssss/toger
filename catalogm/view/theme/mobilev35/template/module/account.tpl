<div class="member-left">
	<ul class="nav">
	<?php if ($logged) { ?>
		<li><h1><?php echo $text_orders;?></h1>
			<dl class="navigation">
				<dd><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></dd>
				<dd><a href="<?php echo $order; ?>&filter_order_status=16">未付款订单</a></dd>
				<dd><a href="<?php echo $order; ?>&filter_order_status=2">进行中订单</a></dd>
				<dd><a href="<?php echo $order; ?>&filter_order_status=5">已完成订单</a></dd>
				<?php if(isset($transaction)){?>
				<dd><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></dd>
				<?php }?>
<?php if(false) {?>
				<dd><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></dd>
				<dd><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></dd>			
				<dd><a href="<?php echo $consulation; ?>"><?php echo $text_consulation; ?></a></dd>
<?php } ?>
				<!--  <dd><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></dd>-->
			</dl>
		</li>
		<li><h1><?php echo $text_info;?></h1>
			<dl class="navigation">
				<dd><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></dd>
				<dd><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></dd>
<?php if(false) {?>	
				<dd><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></dd>
<?php } ?>
				<dd><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></dd>
			</dl>
		</li>
		<li><h1><?php echo $text_service;?></h1>
			<dl class="navigation">
				<dd><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></dd>
			</dl>
		</li>
<?php if(false) {?>	
		<li><h1><?php echo $text_service;?></h1>
			<dl class="navigation">
				<dd><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></dd>
				<dd><a href="<?php echo $invite; ?>"><?php echo $text_invite; ?></a></dd>
				<dd><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></dd>
			</dl>
		</li>
<?php } ?>
		
	 <?php }  else {?>
		<li><h1><?php echo $text_member_center;?></h1>
			<dl class="navigation">
				<dd><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></dd>
				<dd><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></dd>
				<dd><a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></dd>
			</dl>
		</li>
	 <?php } ?>
	</ul>
</div>
