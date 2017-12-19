<?php echo $header35; ?>
<?php echo $header; ?>
<link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/us.css" rel="stylesheet"/>
<div class="fz-14 bg-white">
    <ul class="order-suc">
        <li class="col-red"><?php echo $text_comment;?></li>
        <li class="fz-12 col-gray"><?php echo $text_message;?></li>
        <li class="img-cartoon"><img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/icon-cartoon.png"></li>
    </ul>
</div>
<?php if($order_info){?>
<div class="fz-12">
    <ul class="order-add">
        <li class="order-addname">
            <img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco4.png">
            <div><span>收货人: </span><span><?php echo $order_info['shipping_firstname'].$order_info['shipping_lastname'];?></span>
            <span class="pull-right"><?php echo $order_info['shipping_mobile'];?></span></div>
            <div><span>收货地址：</span><span><?php echo $order_info['shipping_address_1'].$order_info['shipping_address_2'];?></span></div>
        </li>
        <li class="col-gray">
            <div><span>订单号码：</span><span><?php echo $order_info['order_id'];?></span></div>
            <div><span>下单时间：</span><span><?php echo $order_info['date_added'];?></span></div>
            <div><span>配送时间：</span><span><?php echo $order_info['shipping_time'];?></span></div>
            <div><span>支付方式：</span><span><?php echo $order_info['payment_method'];?></span></div>
        </li>
    </ul>
</div>
<?php }?>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>