<nav id="menu">
<header id="header" class="bar bar-header bar-positive rel">
		<h1 class="title aside-left"><?php echo $text_account; ?></h1>
</header>
<div id="content" class="content">
	<ul class="list">
	<a class="item" href="<?php echo $this->url->link('account/order');?>">我的订单(<span class="count"><?php echo $this->getTotalOrderCount(); ?></span>)</a>
    <a class="item" href="<?php echo $this->url->link('account/coupon'); ?>"><?php echo $text_coupon; ?></a>
    <?php if(isset($transaction)){?>
	<a class="item" href="<?php echo $this->url->link('account/transaction'); ?>"><?php echo $text_transaction; ?></a>
	<?php }?>
    <!--  <a class="item" href="<?php echo $this->url->link('account/reward'); ?>"><?php echo $text_reward; ?></a>-->
	<a class="item" href="<?php echo $this->url->link('account/edit'); ?>"><?php echo $text_edit; ?></a>
    <a class="item" href="<?php echo $this->url->link('account/password'); ?>"><?php echo $text_password; ?></a>
    <a class="item" href="<?php echo $this->url->link('account/logout'); ?>"><?php echo $text_logout; ?></a>
    <a class="item" href="<?php echo $this->url->link('information/information&information_id=20'); ?>"><?php echo $text_member; ?></a>
    <a class="item" href="<?php echo $this->url->link('information/information&information_id=45'); ?>"><?php echo $text_about; ?></a>
	</ul>
</div>
</nav>

<link type="text/css" rel="stylesheet" href="catalog/view/theme/<?php echo get_template(); ?>/javascript/jquery.mmenu.css" />
<script type="text/javascript" src="catalog/view/theme/<?php echo get_template(); ?>/javascript/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="catalog/view/theme/<?php echo get_template(); ?>/javascript/jquery.mmenu.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('nav#menu').mmenu();
});
</script>
