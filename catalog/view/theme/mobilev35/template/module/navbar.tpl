<div id="navbar">
    <div class="text-center bg-white">
        <a href="index.php?route=common/home" class="home col-green">首页</a>
        <a href="index.php?route=product/category&path=49" class="category">分类</a>
        <a href="index.php?route=checkout/cart" class="cart"><span><span class="cart-num" id="cart_total"><?php echo $this->cart->countProducts(); ?></span></span>购物车</a>
        <?php if($this->customer->isLogged()) {?>
	  <a class="mine" href="index.php?route=account/account">我的</a>
		  <?php } else {?>
		  <a class="mine" href="index.php?route=account/login">我的</a>
		  <?php } ?>
    </div>
</div>