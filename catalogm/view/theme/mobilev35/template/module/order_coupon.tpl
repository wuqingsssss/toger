<?php if($message){?>
<style>
.sinfo ul{overflow: hidden;}
.sinfo li{float:left;padding: 0.1rem;}
.sleft{width: 2rem;}
.sleft img{width:100%;}
.sright{
width:3.7rem;
}
li.sright{
height: 1rem;
line-height: 0.5rem;
}
</style>
<div class="sinfo fz-16" >
 <ul class="order-add bg-white">
 <li class="sleft"><img src="<?php echo DIR_DIR;?>view/theme/mobilev35/images/order_coupon.png"/></li>
 <li class="sright fz-13 col-gray">
   <?php echo $message; ?>
</li>
</ul>
</div>
<?php }?>