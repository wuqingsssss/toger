<?php echo $headersimple; ?>

<link href="assets/libs/mobiscroll-2.13.2/style/mobiscroll.2.13.2.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/order.css?v=<?php echo STATIC_VERSION; ?>" />
<link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/cart.css?v=<?php echo STATIC_VERSION; ?>" />
<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>
    <div id="content" class="pg-page checkout-page" pg-name="checkout">
        <!-- 公共头开始 -->
        <?php echo $header?>
        <!-- 公共头结束 -->
        <input name="verified" type="hidden" id="verified" value="1" >
        <div id="warnning"></div>
        <div class="checkout">
     	 <?php if ($shipping_required) { ?>
         <div id="shipping-method">
            <!--配送信息 START--> 
            <div class="order_distribution">    
                <div class="o_d_title fl fm-18">配送信息：</div>
                <div class="o_d_information">
                <?php if($address){ ?>
                    <div class="o_address fm-16">
                        <span><?php echo $entry_customer."&nbsp;&nbsp;".$address['firstname'];?></span>
                        <span class="mobile"><strong><?php echo $address['mobile'];?></strong></span>
                        <span><?php echo $entry_address."&nbsp;&nbsp;".$address['address_1'].$address['address_2'];?></span>
                    </div>
                    <div class= "o_right_arrow order_in_right icon icon-right-open-big col-green">
                       <!--  <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" style="margin-top:15%;" class="order_in_right"/>--> 
                    </div>
                <?php } else{ ?>
                    <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/gps.png" class="order_in_img"/>
                    <span style="line-height: 1.6rem;margin-left:10px;">请添加您的配送信息</span>
                    <div class= "o_right_arrow order_in_right icon icon-right-open-big col-green"></div>
                    <!--  <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" class="order_in_right"/>-->
                <?php }?>
                </div>
                <ul id="mapBox" style="display:none;">
            <div id="map" class="pull-left"></div>
            <div id="mapPanel" class="pull-right">
                <div id="mapListWrap">
                    <table class="table table-hover">
                        <tbody id="mapList">
                        </tbody>
                    </table>
                </div>
                <div id="mapPager" class="pagination text-center"></div>
            </div>
        </ul>
            </div>
            <!--配送信息 END-->
    
            <!--配送时间 START-->
            <div class="order_time show-overlay" id="m-result">
            	<span class="order_t_title fl fm-18">配送时间：</span>
             <span data-role="fieldcontain" class="result demo demo-select-opt" style="line-height: 0.5rem;">
               <select id="pickupdate" name="pickupdate" class="demo-test-select-opt" data-role="none" onchange="updateAdditionalDate();">
               <?php foreach($dates as $dkey=> $date){?>	 
                <optgroup label="<?php echo $date['title'];?>">
                <?php foreach($date[times] as $ttkey=> $tt){?>	 
                    <option value="<?php echo $ttkey;?>"><?php echo $tt;?></option>
                 <?php }?>
                </optgroup>
                 <?php }?>	
            </select>	
                	</span>
            	<div class= "o_right_arrow order_in_right icon icon-right-open-big col-green"></div>
            	<!--  <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" class="order_in_right" style="margin-top: 0.3rem;"/>-->
            </div>
            <!--配送时间 END-->
            
             <div class="cart-module">
            	<?php if($shipping_code=='flat.flat') {?>
     			<?php echo $this->getChild('total/shipping_time'); ?>
     			<?php } else if($shipping_code=='free.free') {?>
     			<?php echo $this->getChild('total/point'); ?>
     			<?php } ?>
             </div>
    	     
    	 </div>
    	<?php } ?>
        
        <!-- 订单详情 START -->
        <div id="confirm" class="order_pay">
            <div class="order_p_way fm-18">
                <?php echo $text_checkout_product; ?>  
            </div>
           
            <?php echo $order_confirm;?>
            
        </div>
        
        <!-- 订单详情 END -->
        <div class="cart-module">
          <?php foreach ($modules as $module) { ?>
          <?php echo $module; ?>
          <?php } ?>
        </div>
    
        <!-- 结算 START -->
        <div class="checkout-block">
            <?php echo $checkout_detail;?>
    	</div>
    	<!-- 结算 END -->
    
    </div>
</div>
    
    <?php if($order_pay) {?>
    <div class="pg-page payment">
        <?php echo $order_pay?>
        <div class="fz-16"><?php echo $checkout_token; ?></div>
    </div>
    <?php }?>

<script type="text/javascript">
<!--
var pages;
$(function(){
	pages=new PageSwitch();

    // MODULE备注绑定
	$('.cart-module #comment .module-heading').bind('click', function() {
		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
		} else {
			$(this).addClass('active');
		}
	
		$(this).parent().find('.module-content').slideToggle('slow');
	});
  
});
 

	// 地址选择
	$('.order_distribution .o_d_information').bind('click', function() {
		window.location = "index.php?route=checkout/checkout_group/changeShippingMethod";
	});


	// 订单校验
	$('#button-confirm').live('click', function() {
	 	$.ajax({ 
    		url: 'index.php?route=checkout/checkout_group/validate&token=<?php echo $token; ?>',
    		type: 'post',
    		data: '', 		
    		dataType: 'json',
    		beforeSend: function() {
				$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>');
      		},	
    		complete: function() {
    			$('.waiting').remove();
    		},	
    		success: function(json) {
    			$('.waiting').remove();
    			if (json['error']) {
    				if (json['error']['warning']) {
                        _.toast(json['error']['warning'], 3000);
    				}
    			}else{     			
    				pages.switchTo(1);
    			}
    		},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError);
			}		
    	});
	});

	// 支付方法
	$("input[name='balance']").live('click', function() {
		changePayment();
	});
	
	$("input[name='payment_method']").live('click', function() {
		changePayment();
	});
	

	// 更换支付方式
	function changePayment(){
		var balance = '0'; 
    	var ba = document.getElementsByName("payment_method"); 
    	if( $("input[name='balance']").attr('checked')){
    		balance = '1';
    	}
        var paymethods =document.getElementsByName("payment_method");
        var payment_method;
        for(var i = 0; i < paymethods.length; i++)
        {
            if(paymethods[i].checked){
    	        payment_method=paymethods[i].value;
            }
        };
    	
    	$.ajax({ 
    		url: 'index.php?route=checkout/payment/changemethod4group&token=<?php echo $token; ?>',
    		type: 'post',
    		data: 'payment_method='+payment_method+'&balance='+balance, 		
    		dataType: 'json',
    		beforeSend: function() {
    			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/<?php echo $this->config->get("config_template"); ?>/images/waiting.png"/></span>');
      		},	
    		complete: function() {
    			$('.waiting').remove();
    		},	
    		success: function(json) {
    			$('.waiting').remove();
    			if (json['error']) {
    				if (json['redirect']) {
    					 window.location = json['redirect'];
    					}
    				if (json['error']['warning']) {
                        _.toast(json['error']['warning'], 3000);
    				}
    			}else{
        			
        			if(json['payinfo']['balance']['valid']){
            			$('#balance-check').removeClass('blk-disabled');
            			
            			if(json['payinfo']['balance']['selected']){
            				$('#balance-pay').show();
             			}
            			else{
            				$('#balance-pay').hide();
            			}
        			}
        			else{
            			$('#balance-check').addClass('blk-disabled');
            			$('#balance-pay').hide();
        			}
   	
        			if(json['payinfo']['otherpay']['valid']){
            			$('#pay-select').removeClass('blk-disabled');
        			}
        			else{
            			$('#pay-select').addClass('blk-disabled');
        			}
        			$('#balance-value').html(json['payinfo']['balance']['value']);
       				$('#balancepay-value').html(json['payinfo']['balance']['pay']);
        			$('#pay-value').html(json['payinfo']['otherpay']['pay']);
    			}
    		}		
    	});
	}
	
	// 立即支付
    $('#submit-pay').live('click', function () {
    	var paytype = $("input[name='payment_method']:checked").val();
    	
    	$('#submit-pay').attr({"disabled":"true"});
     	$.ajax({
    		url: 'index.php?route=checkout/checkout_group/paysubmit&token=<?php echo $token; ?>',
    		type: 'post',
    		data: '',
    		dataType: 'json',
    		beforeSend: function() {
    			$('body').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/mobilev35/images/waiting.png"/></span>'); 			
    		},
    		complete: function() {
    			$('.waiting').remove(); 

    		},
    		success: function(json) {
    			$('.waiting').remove(); 
    			console.log(json);

				if (json['error']) {					
					if (json['error']['warning']) {
						
						$('#submit-pay').removeAttr('disabled');
						_.toast(json['error']['warning'], 3000);
					}
					if (json['redirect']) {			
						window.location = json['redirect'];
					}
				} else {
					_.toast('提交成功跳转中...');
					if (json['redirect']) {									
						window.location = json['redirect'];
					}
					if(json['payment']){
						$("#submit-pay").html(json['payment']);	
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError);
			}		
		});
    });

	
//-->
</script>

<script>
$(document).ready(function(){	
      var aaa=$('#pickupdate').mobiscroll('destroy').mobiscroll( {
    	  preset: 'select',
          group: true,
          width: 50,
          theme: 'android-holo light',
          groupLabel: '#showdate',
          mode: 'scroller',
          display: 'modal',
          animate: ''
      });
      $('#m-result').bind('click',function(){
    	  $.mobiscroll.instances.pickupdate.show();    	  
      });

    //console.log('$.mobiscroll',$.mobiscroll);

   
    //  checkoutComfirm();

	
});  

function updateAdditionalDate(){
console.log('date='+$('#pickupdate').val());		
  $.ajax({
        url: 'index.php?route=checkout/checkout_group/updateAdditionalDate',
        type: 'post',
        cache:false,
        data: 'date='+$('#pickupdate').val(),
        dataType: 'text',
        success: function (json) {
        	console.log(json);
        }
    });

}
</script>
<!-- E 日期控件 -->
<script src="assets/libs/mobiscroll-2.13.2/script/mobiscroll.2.13.2.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/confirm_order_popup.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<script type="text/javascript" src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/pageswitch.js"></script>
<?php echo $footer35; ?>