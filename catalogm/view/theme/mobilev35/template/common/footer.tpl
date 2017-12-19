


<div class="bar bar-footer bar-light">
  <div class="tabs tabs-icon-top tabs-default">
	  <a class="tab-item" href="index.php?route=common/home">
	    <i class="icon ion-home<?php if (stristr($_REQUEST[route],'home')) echo ' select';?>" id="home_icon">首页</i>
	  </a>
	  <a class="tab-item" href="index.php?route=product/category/lists">
	    <i class="icon ion-navicon<?php if (stristr($_REQUEST[route],'category')) echo ' select';?>" id="catagery_icon">分类</i>
	  </a>
	  <?php if($this->customer->isLogged()) {?>
	  <a class="tab-item" href="index.php?route=account/account">
		  <?php } else {?>
		  <a class="tab-item" href="index.php?route=account/login">
		  <?php } ?>
	    <i class="icon ion-person<?php if (stristr($_REQUEST[route],'account')) echo ' select';?>" id="person_icon">个人中心</i>
	  </a>

	   <a class="tab-item tab-item-cart" href="index.php?route=checkout/cart">
	    	<div class="tab-item-cart-total">
				<span id="cart_total" class="ix-badge ix-radius">
					<?php echo $this->cart->countProducts(); ?>
				</span>
			</div>
		   <i class="icon ion-bag<?php if (stristr($_REQUEST[route],'checkout')) echo ' select';?>" id="cart_icon">购物车</i>
	  </a>
	</div>
</div><!--END .bar-footer-->
</div>
<?php echo $google_analytics; ?>
<script type="text/javascript" src="catalog/view/javascript/common.js?id=<?php echo time();?>"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
</body>
</html>