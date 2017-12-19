<?php echo $header; ?>
<div id="header" class="bar bar-header bar-default">
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
<div class="account-info card">
	<div class="username">
	  	<strong><?php echo $this->customer->getDisplayName();?></strong>,
	  	<span id="welcome">欢迎您！</span>
	</div>
	<div class="account">
	  		<div class="rank r<?php echo $user_group_id; ?>">
	        <s></s>
	        <a target="_blank" href="<?php echo $this->url->link('information/information&information_id=20'); ?>"><?php echo $user_group_name; ?></a>
	      </div>
	</div>

	<?php if(isset($transaction)){?>
	<div id="remind">
		 <div class="row">
			<dl class="fore col">
				<dt>账户余额：</dt>
				<dd><span id="divBalance"><strong class="ftx-03"><?php echo $total;?></strong></span></dd>
			</dl>
			 
			<dl class="col">
			<!-- <dt class="score"><?php echo $text_reward?>：</dt>
				<dd class="fore1">
					<a href="<?php echo $this->url->link('account/reward');?>" title="<?php echo $text_reward?>">
					   <span id="divContent" class="ftx-03"><?php echo $points;?></span>
					</a>
				</dd> -->	
			</dl>
		</div>
   </div>
   <?php }?>
 
</div>

<div class="list">
	<div class="item item-divider">
          	交易管理
    </div>
	<a class="item" href="<?php echo $this->url->link('account/order');?>">我的订单(<span class="count"><?php echo $this->getTotalOrderCount(); ?></span>)</a>
	
	<a class="item" href="<?php echo $this->url->link('account/coupon'); ?>"><?php echo $text_coupon; ?></a>
	<?php if(isset($transaction)){?>
	<a class="item" href="<?php echo $this->url->link('account/transaction'); ?>"><?php echo $text_transaction; ?></a>
	<?php }?>
 <!--     <a class="item" href="<?php echo $this->url->link('account/reward'); ?>"><?php echo $text_reward; ?></a>-->
	<div class="item item-divider">
          	帐户管理
    </div>
    <a class="item" href="<?php echo $this->url->link('account/edit'); ?>"><?php echo $text_edit; ?></a>
    <a class="item" href="<?php echo $this->url->link('account/password'); ?>"><?php echo $text_password; ?></a>
    <a class="item" href="<?php echo $this->url->link('account/logout'); ?>"><?php echo $text_logout; ?></a>
    
	<div class="item item-divider">
          	帮助
    </div>
    <a class="item" href="<?php echo $this->url->link('information/information&information_id=20'); ?>"><?php echo $text_member; ?></a>
    <a class="item" href="<?php echo $this->url->link('information/information&information_id=45'); ?>"><?php echo $text_about; ?></a>
</div>
</div>
<?php echo $footer; ?> 
