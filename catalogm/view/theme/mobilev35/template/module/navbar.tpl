<div id="navbar">
    <div class="text-center bg-white">
        <a href="index.php?route=common/home&sequence=0" class="<?php echo (stripos($route,'common')!==false||!$route)? 'col-green':'col-gray';?>">
            <div>
            <i class="icon icon-home fz-28 "></i>
            </div>
            <span>&nbsp;&nbsp;&nbsp;首页</span></a>
        <a href="index.php?route=product/category&path=49" class="<?php echo (stripos($route,'product')!==false||!$route)? 'col-green':'col-gray';?>">
            <div>
            <i class="icon icon-food fz-28 "></i>
            </div>
            <span>&nbsp;&nbsp;&nbsp;点餐</span></a>
        <a href="index.php?route=checkout/cart" class="<?php echo (stripos($route,'checkout')!==false||!$route)? 'col-green':'col-gray';?>">
            <div>
            <i class="icon icon-basket fz-28 "></i>
            </div>
            <span>&nbsp;&nbsp;&nbsp;购物车</span>
            <div class="cart"><span class="cart-num" id="cart_total"><?php echo $this->cart->countProducts(); ?></span></div></a>
        <?php if($this->customer->isLogged()) {?>
	    <a class="<?php echo (stripos($route,'account')!==false||!$route)? 'col-green':'col-gray';?>" href="index.php?route=account/account">
	    <?php } else {?>
		<a class="<?php echo (stripos($route,'account')!==false||!$route)? 'col-green':'col-gray';?>" href="index.php?route=account/login">
		<?php } ?>
	        <div>
            <i class="icon icon-user fz-28 "></i>
            </div>
            <span>&nbsp;&nbsp;&nbsp;我的</span></a>
    </div>
</div>