<?php echo $header35; ?>
<style>
#button-confirm{
border: medium none;
background: transparent none repeat scroll 0% 0%;
color: rgb(255, 255, 255);
font-size: 18px;
}
</style>
 <link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/uc.css" rel="stylesheet"/>
 <?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>'<a class="return" href="javascript:_.go();"></a>',
       'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
       'right'=>''
)));?>
<div id="uc_body">
<div class="uc-body col-gray">
             <ul>
              <li class="fz-16 ">
                <span><img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco1.png">订单状态</span>
                <span class="pull-right">
                <a class="col-red"><?php echo $status; ?></a>
                </span> 
              </li>
              <li class="fz-13 plist">
                 <div><span><?php echo $text_order_id; ?></span><span>#<?php echo $order_id; ?></span></div>
                 <div><span><?php echo $text_date_added; ?></span><span> <?php echo $date_added; ?></span></div>
                 <div><span>配送时间：</span><span><?php if ($shipping_method && $pdate) { ?><?php echo $pdate; ?> <?php }else{ ?><?php echo date('Y-m-d H:i',strtotime($shipping_time)-3600).'-'.date('H:i',strtotime($shipping_time)); ?><?php }?></span></div>
                  <div><span>支付方式：</span><span><?php echo "$payment_method $payment_code"; ?></span></div>
              </li>
              <li class="fz-16 col-red">
                            实付款：<?php echo $order_total; ?>
              </li>
             </ul>
		<ul>
			<li class="fz-16 "><span><img
					src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco2.png">费用明细</span>
				<span class="pull-right"> </span></li>
				 <?php foreach ($groups as $key => $products2) { ?>
			<li class="fz-13 plist">
			<?php foreach ($products2 as $product) { ?>
				<div>
					<span><a><?php echo $product['name']; ?> <?php foreach ($product['option'] as $option) { ?>
                                            <br/>
                                            &nbsp;
                                            <small> - <?php echo $option['name']; ?>
                                                : <?php echo $option['value']; ?></small>
                                        <?php } ?></a></span><span class="pull-right price ">
						<?php if($product['promotion_price']){ echo $product['promotion_price'].'<font class="text-delete">'. $product['price'].'</font>';}else{echo $product['price'];}?></span><span class="pull-right">X<?php echo $product['quantity']; ?></span>
				</div><?php } ?>
				
			</li> <?php } ?>
			<li class="fz-13 plist">
				<?php $i=0;$count=count($totals);
                 foreach ($totals as $total) { ?> <?php  $i++; if($i==$count){?>
			</li>
			<li class="fz-13 plist">
				<?php }?>
				<div
					<?php if($i<$count) echo ' class="col-red"';?>><span><a><?php echo $total['title']; ?></a></span><span class="pull-right price"><?php echo $total['text']; ?></span></div> <?php } ?>
			</li>
		</ul>
		<ul>
              <li class="fz-16 ">
                <span><img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco3.png">收货信息</span>
                <span class="pull-right">
                </span> 
              </li>
              <li class="fz-13 plist">
              <span class="ad-t">
              <img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco4.png">
              </span>
              <span class="ad-b">
                 <div><span class="tab">收&nbsp;货&nbsp;人:</span><span><?php echo $shipping_firstname; ?></span><span class="pull-right"><?php echo $shipping_mobile; ?> </span></div>
                 <div><span class="tab">收货地址：</span><span><?php echo $shipping_address_1.$shipping_address_2; ?></span></div>
              </span></li>
             </ul>  
              <?php if ($histories) { ?>
              <ul>
              <li class="fz-16 ">
                <span><img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco5.png">配送信息</span>
                <span class="pull-right">
                </span> 
              </li>
              <li class="fz-13 plist">
              <span class="ad-t"></span>
               <span class="ad-b">     
                 
                   <?php foreach ($histories as $history) { ?>
                        <div><span class="tab"><?php echo $text_history; ?></span>
                                <span><?php echo $history['date_added']; ?></span>
                                <span><?php echo $history['status']; ?></span>
                                <span><?php echo $history['comment']; ?></span>
                       </div>
                        <?php } ?>
              
              </span>
              </li>
             </ul>      <?php } ?>
          
</div>
<div class="text-center uc-foot col-gray fz-13">
<?php if($order_status_id=='16'){?>
    <a href="<?php echo $reorder_action.'&order_id='.$order_id; ?>">
        <div class="btn1 bg-red col-white fz-18" id="reorder-btn"> 
            <span>立刻支付</span>
        </div>
    </a>
<?php }?>
<?php if ($refundpj) { ?>
    <div class="btn1 bg-red col-white fz-18"> <a onclick="">去评价</a></div>
    <?php } ?>
     <?php if ($refundable) { ?>
    <div class="btn1 bg-red col-white fz-18"> <a onclick="$('#order-cancel').show();">申请退款</a></div>
    <?php } ?>
      <?php if ($cancelable) { ?>
    <div data-id="<?php echo $order_id; ?>" id="cancel-order-btn" class="btn1 bg-gray col-white fz-18"> <a>取消订单</a></div>
<?php } ?>
</div>

</div>

<div id="order-cancel" class="overlay-container scroll-y hidden">
<div class="uc-body col-gray bg-white">
             <ul>
              <li class="fz-16 plist ">
                 <span> 退款金额：</span><span class="pull-right"><?php echo $order_total; ?></span>
              </li>
             </ul>
             <ul>
              <li class="fz-16 ">
                <span><img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/uco2.png">退款原因</span>
                <span class="pull-right">
                </span> 
              </li>
              <li class="fz-13 plist">

                 <div><span><input type="radio" id="radio1" class="regular-radio" name="reason" checked="checked" value="错了，买多了" /> 
                            <label for="radio1"></label>
                 错了，买多了</span></div>
                 </li>
                <li class="fz-13 plist"> <div><span>
                <input type="radio" id="radio2" class="regular-radio" name="reason" value="地址、电话填写有误" /> 
                           <label for="radio2"></label>
                地址、电话填写有误</span></div>
                </li>
                 <li class="fz-13 plist"><div><span>
                 <input type="radio" id="radio3" class="regular-radio" name="reason" value="计划有变、不想要了" />
                                    <label for="radio3"></label>
                  计划有变、不想要了</span></div>
                 </li>
                 <li class="fz-13 plist"><div><span>
                 <input type="radio" id="radio4" class="regular-radio" name="reason" value="送的太慢，等的太久了" />
                              <label for="radio4"></label>
                  送的太慢，等的太久了</span></div>
                 </li>
                 <li class="fz-13 plist"><div><span>
                 <input type="radio" id="radio5" class="regular-radio" name="reason" value="" />
                              <label for="radio5"></label> 其他</span></div>
              </li>
             </ul>
 <ul>
              <li class="fz-16 ">
                <span><textarea id="reasontext" name="reasontext" style="width:100%;height:80px;" placeholder="请详细描述您遇到的问题，有助于更快处理退款" ></textarea></span>
              </li>
              </ul>
          
<ul>
          <li class="text-center col-gray text-overflow bg-white fz-13">
            <span class="btn1-50 bg-red pull-left col-white fz-18"> <a onclick="refund_order('<?php echo $order_id; ?>');return false;" >提交</a></span>
            <span class="btn1-50 bg-miao pull-left col-white fz-18"> <a onclick="$('#order-cancel').hide();return false;" >取消</a></span>
</li>
</ul>
</div>
</div>
<div class="overlay-container hidden" id="filter-payemnt">
    <div class="overlay-content-container">
        <div class="overlay-content col-red fz-18 text-center payment-list ">

            <span class="btn1 h2 bg-white">   <a onclick="$('#filter-payemnt').hide();">微信支付</a></span>
            <span class="btn1 h2 bg-white">   <a onclick="$('#filter-payemnt').hide();">百度钱包</a></span>
            <span class="btn1 h2 bg-white">   <a onclick="$('#filter-payemnt').hide();">储值</a></span>
            <span class="btn1 h2 m2 bg-white"><a onclick="$('#filter-payemnt').hide();">取消</a></span>


        </div>
    </div>
</div>
<div class="overlay-container hidden" id="filter-success">
    <div class="overlay-content-container">
        <div class="overlay-content bg-white col-gray fz-18 text-center uc-body cancel">
            <ul>
              <li class="fz-16 plist ">
                  <span>提交成功，请耐心等待</span>
</li><li>
            <span class="col-red "><a onclick="$('#filter-success').hide();window.location.reload();">确定</a></span>
              </li>
             </ul>
        </div>
    </div>
</div>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>
<script>
$(document).ready(function () {
    
    $('#cancel-order-btn').click(function () {
        var order_id = $(this).data('id');
       _.confirm('确认取消订单#' + order_id + '？',function(){
           $.ajax({
               url: '<?php echo $link_cancel; ?>',
               type: 'post',
               data: 'order_id=' + order_id,
               dataType: 'json',
               success: function (json) {
                   if (json['success']) {
                       window.location.reload();
                   }
               }
           });
       });
    })
});

function refund_order(order_id) {
	
	
    var reason = '';
     $('#order-cancel .regular-radio').each(
    		function(index){
    			if(this.checked)reason+=this.value;
    }
     );
    reason+=$('#order-cancel #reasontext').val();
    console.log(reason);

    
    if (!!reason) {
        $.ajax({
            url: '<?php echo $refund; ?>',
            type: 'post',
            data: {order_id: order_id, reason: reason},
            dataType: 'json',
            success: function (result) {
                if (!!result.success) {
                	$('#filter-success').show();
                	$('#order-cancel').hide();
                    //window.location.reload();
                }else if(result.redirect){
                	window.location=result.redirect;
                }
                else
                	{
                	window.location.reload();
                	}
            }
        });
    }
}
</script>