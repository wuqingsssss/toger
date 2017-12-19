﻿<?php echo $headersimple; ?>
<link rel="stylesheet" type="text/css" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/order.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/general.css?v=<?php echo STATIC_VERSION; ?>" />
<link rel="stylesheet" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/cart.css?v=<?php echo STATIC_VERSION; ?>" />


<div id="content" class="checkout-page" ng-controller="CheckoutCtrl">
  <input name="verified" type="hidden" id="verified" value="1" >
  <div id="warnning"></div>
  <div class="checkout">
 	 <?php if ($shipping_required) { ?>
     <div id="shipping-method">
        <!--配送信息 START-->
        <div class="order_distribution">
         
            <div class="o_d_title fl">配送信息：</div>
            <div class="o_d_information">
                <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/gps.png" class="order_in_img"/>
                <span style="line-height: 1.6rem;margin-left:10px;">请添加您的配送信息</span>
                <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" class="order_in_right"/>
            </div>
        </div>
        <!--配送信息 END-->
	        
        <!--配送时间 START-->
        <div class="order_time">
        	<span class="order_t_title fl">配送时间：</span>
        	<img src="<?php echo HTTP_CATALOG.$tplpath;?>images/right.png" class="order_in_right" style="margin-top: 0.3rem;"/>
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
	
	<!-- 支付方式  START-->
    <div class="order_pay">
        <div class="order_p_way">支付方式：</div>
       	<div class="order_p_con">		
            <?php echo $payment_methods; ?>    		 
        </div>
    </div>
    
    <!-- 订单详情 START -->
    <div id="confirm" class="order_pay">
        <div class="order_p_way">
            <?php echo $text_checkout_product; ?>  
            <a href="<?php echo $cart; ?>" class="button button-slim"><?php echo $text_modify_cart;?></a>
        </div>
       
        <?php echo $order_confirm;?>
        
    </div>
    
    <!-- 订单详情 END -->
    <div class="cart-module">
      <?php foreach ($modules as $module) { ?>
      <?php echo $module; ?>
      <?php } ?>
    </div>

     <div id="payment-button">
	    <div class="checkout-content" style="padding:0; background-color:#fff;">
	     <div class="columns">
  		<div class="checkout-total">
  			<?php foreach ($totals as $total) { ?>
		      <div class="<?php echo $total['code']; ?>">
		      	<span class="price"><?php echo $total['title']; ?><?php if($total['text']){?>:<?php }?></span>
		      	<span class="number"><b><?php echo $total['text']; ?></b></span>
		      </div>
		    <?php } ?>
  		</div>
  		<div class="clear"></div>
  		<div id="payment" class="payment">
			<?php echo $payment; ?>
		</div>
		<div class="clear"></div>
  		</div>
	    </div>
    </div>
 </div>

</div>

<script type="text/javascript" src="js/laydate/laydate.js"></script>
<script type="text/javascript">
<!--
$('.cart-module .cart-heading').bind('click', function() {
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
	} else {
		$(this).addClass('active');
	}

	$(this).parent().find('.cart-content').slideToggle('slow');
});

$('#checkout-comment .checkout-heading').bind('click', function() {
	$('#checkout-comment .checkout-content').slideDown('slow');
});

$(document).ready(function () {      
    <?php if ($switch==1){ ?>
        $.layer({
            type: 1,
            title: false, //不显示默认标题栏
            shade: [0.5, '#000'],
            area: ['300', 'auto'],
            closeBtn: [0, true],
            page: {html: "<?php echo $tan;?>"}

        });
        <?php }?>
});


//问卷调查
function testabc(){
    var datas='';
    var ret = 0;
    $("[name^='question_value']").each(function(i, o){
       //alert($(o).attr("name"));
       //alert($(o).val());
    
       //datas=$(o).attr("name")+"="+$(o).val()+"&"+datas;
       if($(o).attr('type')=='radio') {
           if ($(o).attr('checked')) {
               datas=$(o).attr("name")+"="+$(o).val()+"&"+datas;
               ret++;
           }
       }else{
           if($(o).val() != ''){
               datas=$(o).attr("name")+"="+$(o).val()+"&"+datas;
               ret++;
           }
       }
    });
    
    if( ret==5 )
    {
       $.ajax({
           type: 'POST',
           url: 'index.php?route=campaign/question/add',
           data: datas,
           dataType: 'json',
           beforeSend: function() {
               $('.success, .warning').remove();
               $('#button-question').attr('disabled', true);
               $('#button-question').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
           },
           complete: function() {
               //$('#button-question').attr('disabled', false);
               $('.wait').remove();
           },
           success: function(json) {
               if(json['success']){
                   $('#question_comment').prepend('<div class="success" style="display: none;">' + json['success'] + '</div>');
                   $('.success').fadeIn('slow');
                   //$('#button-question').attr('disabled', true);
                   //$('#comment .cart-heading').click();
                   $('#button-question').attr('disabled', true);
                   checkoutComfirm();
    
               }
    
           }
    
    
       });
       layer.closeAll();
    }
    else{
       alert('答卷未完成！');
    }
}


//显示节日   
function datetimes(){
    laydate({
        elem: '#birday',
        format: 'YYYY/MM',
        festival: true                 
    });
}



    $("input[name='payment_method']").live('click', function() {
	if($("#alipaybank").attr("checked")=='checked'){
		$("#bank").show();
		checkoutComfirm();
	}else{
		$("#bank").hide();

		$.ajax({
			url: 'index.php?route=checkout/payment',
			type: 'post',
			data: $('#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
			dataType: 'json',
			beforeSend: function() {
				$('#button-payment').attr('disabled', true);
				$('#button-payment').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
			},
			complete: function() {
				$('#button-payment').attr('disabled', false);
				$('.wait').remove();
			},
			success: function(json) {
				$('.warning').remove();

				if (json['redirect']) {
					window.location = json['redirect'];
				}

				if (json['error']) {
					if (json['error']['warning']) {
						$('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');

						$('.warning').fadeIn('slow');
					}
				} else {
					checkoutComfirm();
				}
			}
		});
	}
});

function payment_alibank_change(){
	$("#bank").show();
}

function payment_alibank_click(){
	var banks= $('input:radio[name="pay_bank"]:checked').val();
     if(banks==null){
        alert('请选择您的网上银行，然后保存。');
        return false;
     }
	$("#bank").hide();
	$.ajax({
		url: 'index.php?route=checkout/payment&type=json',
		type: 'post',
		async: false,
		data: $('input[name=\'pay_bank\']:checked,#payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment').attr('disabled', true);
			$('#button-payment').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-payment').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning').remove();
			checkoutComfirm();
		}
	});
}
function shipping_function(targ,selObj,restore){
	$.ajax({
		url: 'index.php?route=checkout/shipping',
		type: 'post',
		data: "shipping_method=" + selObj.options[selObj.selectedIndex].value,
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping').attr('disabled', true);
			$('#button-shipping').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-shipping').attr('disabled', false);
			$('.wait').remove();
		},
		success: function(json) {
			$('.warning').remove();

			if (json['redirect']) {
				window.location = json['redirect'];
			}

			if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');

					$('.warning').fadeIn('slow');
				}
			} else {
				
				   $("#ztd-btn").html('自提'+selObj.options[selObj.selectedIndex].text);
	            	$("#ztd-info").html('自提'+selObj.options[selObj.selectedIndex].text);
				
				loadShippingDetail();
				checkoutComfirm();
			}
		}
	});
}
function checkoutComfirm(){
	$.ajax({
		url: 'index.php?route=checkout/confirm/payment',
		dataType: 'json',
		success: function(json) {
			console.log(json);
			if (json['redirect']) {
				window.location = json['redirect'];
			}

			if (json['output']) {
				$('#payment-button').html(json['output']);

			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		}
	});

}

function loadShippingDetail(){
	$('#shipping-method .cart-module').load('index.php?route=checkout/shipping/detail');
}
//-->
</script>


<script src="http://api.map.baidu.com/api?v=1.5&ak=<?php echo BDYUN_WEB_AK;?>" type="text/javascript"></script>
<script src="http://api.map.baidu.com/components?ak=<?php echo BDYUN_WEB_AK;?>&v=1.0" type="text/javascript"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
<script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/jquery.pager.js"></script>
<script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/GeoUtils.js"></script>
<script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/main.js"></script>
<script>
var filterData=<?php echo $filterData;?>;
//var lbspointhome=<?php echo $lbspointhome;?>;
var meiregionarea={};
Util.config.ak='<?php echo BDYUN_WEB_AK;?>';
Util.config.geotable_id='<?php echo GEOTABLE_ID;?>';
</script>
<?php if($this->config->get('pickupaddr_status')){?>
<script>
var meiregionarea={};

Util.geoSearch.group='[0,<?php echo $this->customer->getCustomerGroupId();?>]';

iTofilterBox(filterData,'#filterBox');

var keyword     = "",   //检索关键词
page        = 0,    //当前页码
points      = [];   //存储检索出来的结果的坐标数组

$(document).ready(function(){	
	setMeishiregion();	
	searchAction();
});  
</script>
<?php }?>
<script>
$(document).ready(function(){	

	$("#order-listaddress .order-address").bind("click",upodateShippingAddress);	
});  
function checkaddress(address)
{
	
if(!address)return;
<?php if($this->config->get('pickupaddr_status')){?>
	setPointByAddress(address);
<?php }?>

	var url='apiv3/index.php?route=lbs/shipping/hgetByAddress&callback=?';
	 $.getJSON(url,
			 {
             'address' : address,  
            },
			 function(e) {
		 console.log(e);
		 if(e.status==1)
			 {
		     	$("#msg_address_1").html('');
			    $("#address_1").css('color','green');
			    
			   $('#shipping_city').val(e.data.city);
		       $('#shipping_code').val(e.data.shippingcode);
		       $('#shipping_data').val(e.data.region_name);
			    
			 }
		 else
			 {$("#address_1").css('color','black');
		        $("#msg_address_1").html(e.message);
		        $("#msg_address_1").css('color','red');
		        $('#shipping_city').val('');
			    $('#shipping_code').val('');
			    $('#shipping_data').val('');
			 }
    });    	
	    	
}

function upodateShippingAddress(){
	  var address_id=$(this).attr('data');
	  var object=$(this);	  
	  $("#order-listaddress .order-address").removeClass('active');
	  $.ajax({
      url: 'index.php?route=checkout/checkout/shipping_method_load',
      type: 'post',
      cache:false,
      data: 'address_id='+address_id,
      dataType: 'json',
      success: function (json) {
           console.log(json);
         if(json.status=='0'){
      	   object.addClass('active'); 	
           $('#shipping-method .shipping-method-list').html(json.data.shipping_method);
           turnbox($('#order-listaddress'),$('#page'),'right');
   	       updateAdditionalDate();
   	       checkoutComfirm();	
          }
         else
          {
          	for(id in json.error_warning){
                  $('#msg_'+id).val(json.error_warning.id);
               	}
         }
      }
  });	  
	  
}

function saveShippingAddress(){   		
	console.log('firstname='+$('#firstname').val()+'&mobile='+$('#mobile').val()+'&address_1='+$('#address_1').val()+'&address_1_poi='+$('#address_1_poi').val()
            +'&address_2='+$('#address_2').val()
            +'&shipping_city='+$('#shipping_city').val()
            +'&shipping_code='+$('#shipping_code').val()
            +'&shipping_data='+$('#shipping_data').val());

	if($('#shipping_data').val()==''){	
		$("#msg_address_1").html('配送地址无效，请检查您的地址');
        $("#msg_address_1").css('color','red');
		$('#shipping_data').focus();
		return false;
	}
	//else if($('#address_2').val()=='')
	{
		//$("#msg_address_2").html('请填写您的楼宇门牌号');
		//$('#address_2').focus();
		//return false;
	}
	
	  $.ajax({
            url: 'index.php?route=checkout/checkout/shipping_method_load',
            type: 'post',
            cache:false,
            data: 'address_id=0&firstname='+$('#firstname').val()+'&mobile='+$('#mobile').val()+'&address_1='+$('#address_1').val()+'&address_1_poi='+$('#address_1_poi').val() +'&address_2='+$('#address_2').val()+'&shipping_city='+$('#shipping_city').val()+'&shipping_code='+$('#shipping_code').val()+'&shipping_data='+$('#shipping_data').val(),
            dataType: 'json',
            success: function (json) {
                console.log(json);
                if(json.status=='0'){
                 $('#shipping-method .shipping-method-list').html(json.data.shipping_method);
                         
                 
            var tpl='<ul id="order-address-'+json.data.shipping_address_id+'" data="'+json.data.shipping_address_id+'" class="order-address active">\
            <li>\
                        <span class="orderp s1">收货人</span>\
 <span>'+$("#firstname").val()+'</span>\
 </li>\
 <li>\
 <span class="orderp s1">电  话</span>\
                 <span>'+$("#mobile").val()+'</span>\
                 </li>\
          <li>\
          <span class="orderp s1">收货地址</span>\
                  <span>'+$("#address_1").val()+$("#address_2").val()+'\
                  </span>\
                 </li>\
          </ul>';         
          $('#firstname').val('');
          $('#mobile').val('');
          $('#address_1').val('') ;
          $('#address_2').val('');
          //$('#shipping_city').val('');
          // $('#shipping_code').val('');
          $('#shipping_data').val('');
                 $('#addresslist').prepend(tpl);
                 $('#order-address-'+json.data.shipping_address_id).bind('click',upodateShippingAddress);
                 turnbox($('#order-listaddress'),$('#page'),'right');
                 $('#order-adaddress,#addresslist').toggleClass('hide');
         	     updateAdditionalDate();
         	     checkoutComfirm();	
                }
                else
               {
                	$('.msg').html('');
                	for(id in json.error_warning){
                       $('#msg_'+id).html(json.error_warning[id]);
                	}
               }
            }
        });
	
}
function deleteShippingAddress(obj,address_id){   	
      $(obj).parent().parent().parent().unbind();

  $.ajax({
      url: 'index.php?route=checkout/checkout/shipping_method_load',
      type: 'post',
      cache:false,
      data: 'act=delete&address_id='+address_id,//&firstname='+$('#firstname').val()+'&mobile='+$('#mobile').val()+'&address_1='+$('#address_1').val(),
      dataType: 'json',
      success: function (json) {
          console.log(json);
          if(json.status=='0'){
           $('#shipping-method .shipping-method-list').html(json.data.shipping_method);
           $(obj).parent().parent().parent().remove();
           turnbox($('#order-listaddress'),$('#page'),'right');
   	       updateAdditionalDate();
   	       checkoutComfirm();	
          }
          else
         {
        	  for(id in json.error_warning){
                  $('#msg_'+id).val(json.error_warning.id);
               	}
         }
      }
  });

}
function updateAdditionalDate(){
console.log('date='+$('#pickupdate').val());
  $.ajax({
        url: 'index.php?route=checkout/checkout/updateAdditionalDate',
        type: 'post',
        cache:false,
        data: 'date='+$('#pickupdate').val(),
        dataType: 'text',
        success: function (json) {
        	console.log(json);
        }
    });

}
function turnbox(obj0,obj1,toto)
{
	if(typeof(toto)=="undefined")toto='left';
	
obj1.css('position','fixed');
obj0.css('position','fixed');
$('body').css('overflow-x','hidden');
if(toto=="left"){
obj1.css('left','100%');
obj0.css('left','0');
obj1.show();
obj0.animate({left:'-100%'}, "slow",function(){
	obj0.hide();	 
 });
obj1.animate({left: "0px"}, "slow");
}
else
{
	obj1.css('left','-100%');
	obj0.css('left','0');
	obj1.show();
	obj0.animate({left:'100%'}, "slow",function(){
		obj0.hide();	 
	 });
	obj1.animate({ 
	    left: "0px"
	  }, "slow" );	
}

obj1.css('position','relative');
obj0.css('position','relative');
}
</script>
<div>
<?php echo $footer; ?>