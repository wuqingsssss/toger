<div class="module fm-16" id="m-payment">
   <div>
        <span>菜君币金额: <span class="col-red"><?php echo $product['value'];?></span><i  class="icon icon-money col-green"></i></span>
    </div>
  	<!-- 支付方式  START-->
    <div class="order_pay">
        <div class="order_p_way">支付方式：</div>
       	<div class="order_p_con">		
            <?php echo $payment_methods; ?>    		 
        </div>
    </div>   
    <!-- 支付方式  END-->
    
    <div>
        <span>实际支付金额: <span class="col-red"><?php echo $product['price'];?></span>元</span>
    </div>
    <div id="payment">
        <a href="javascript:" class="btn-submit btn btn-green" id="submit-pay">确认支付</a>
    </div>
</div>
<link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/order.css" rel="stylesheet"/>