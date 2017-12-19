<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" class="checkout-page"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="order-status"><img src="image/data/v2/cart-step2.jpg"></div>
  <input name="verified" type="hidden" id="verified" value="1" >
  <?php if ($error_warning) { ?>
     <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="order-title">填写核对订单信息：</div>
  <div class="checkout">
 	<?php if ($shipping_required) { ?>
     <div id="shipping-method">
	      <div class="checkout-content">
	      	 <div class="shipping-method-list">
	         <?php echo $shipping;?>
	         </div>
	         <div class="cart-module">
	        	 <?php if($shipping_code=='flat.flat') {?>
	 			<?php echo $this->getChild('total/shipping_time'); ?>
	 			<?php } else if($shipping_code=='free.free') {?>
	 			<?php echo $this->getChild('total/point'); ?>
	 			<?php } ?>
	         </div>
	        </div>
	 </div>
	<?php } ?>
    <div id="payment-method">
      <div class="checkout-heading"><?php echo $text_checkout_payment_method; ?></div>
      <div class="checkout-content">
      		 <?php echo $payment_methods; ?> 
      </div>
    </div>
	<div id="confirm">
	   <div class="checkout-heading">
	   <a href="<?php echo $cart; ?>"><?php echo $text_modify_cart;?></a>
      		 <?php echo $text_checkout_product;?>
       </div>
       <div class="checkout-content">
      		<?php echo $order_confirm;?>
      </div>
    </div>
    
    <div class="cart-module">
      <?php foreach ($modules as $module) { ?>
      	<?php echo $module; ?>
      <?php } ?>
    </div>
    
     <div id="payment-button">
	    <div class="checkout-content">
	     <div class="columns">
	    <div class="information">
		<div class="pay-info">
			<?php echo $text_checkout_payment_method;?> ： 
			<?php if($alipaybank!='') { ?>
			<b> <?php echo $alipaybank;?></b>  
			<?php } else{?>
			<b><?php echo $payment_method;?></b>  
			<?php } ?>
		</div>
		<div class="ship-info">
			<?php if ($shipping_required){ echo $shipping_method;} ?>
		</div>
  		</div>
  		
  		<div class="checkout-total">
  			<?php foreach ($totals as $total) { ?>
		      <div class="<?php echo $total['code']; ?>">
		      	<span class="price"><?php echo $total['title']; ?><?php if($total['text']){ ?> :<?php }?></span>
		      	<span class="number"><b><?php echo $total['text']; ?></b></span>
		      </div>
		    <?php } ?>
  		</div>
  		<div class="clear"></div>
  		<div class="payment">
			<?php echo $payment; ?>
		</div>
		
  		</div>
	    </div>
    </div>
 </div>
  <?php echo $content_bottom;?>
</div>

<script type="text/javascript" src="js/laydate/laydate.js"></script>

<script type="text/javascript"><!--
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
        shade: [0], //不显示遮罩
        area: ['360px', 'auto'],
        closeBtn: [0, true],
        page: {html: "<?php echo $tan;?>"}

    });

    laydate({
        elem: '#birday',
        format: 'YYYY-MM',
        festival: true                 
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
			//console.log(json);
			
			$('.warning').remove();
			checkoutComfirm();
		}
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
				
				//console.log(json);
				
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
		dataType: 'text',
		success: function(data) {
			  //console.log(data);
			 var json = null;
             try {
                 json = eval('(' + data + ')');
             } catch (e) {
                 alert("返回字符串不是json格式!");
                 return;
             }
             
			if (json['redirect']) {
				window.location = json['redirect'];
				return;
			}	
		
			if (json['error']) {
				if (json['error']['warning']) {
					alert(json['error']['warning']);
					
				}
				return;
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

//--></script>

</div>
<?php if ($shipping_required) { ?>
<div id="order-listaddress" class="hide">  
<ul>
   <li class="order-title1"><a onclick="layer.closeAll();"><img src="assets/image/lbs/1_07.png"></a>收货地址选择<span onclick="$('#order-adaddress,#addresslist').toggleClass('hide');" class="order-tleft"><a>新增/历史地址</a></span></li>
</ul>
<ul id="order-adaddress" class="order-adaddress<?PHP if($addresses) echo ' hide'; ?>">
        	   <li class="active">
        	         <span class="orderp s1">收货人</span>
        	         <span><input type="text" id="firstname" name="firstname" value="<?php echo $postdata['firstname'];?>" placeholder="请输入您的姓名"></span>
        	          <span id="msg_firstname" class='msg'></span>
        	   </li>
        	    <li> 
        	         <span class="orderp s1">电  话</span>  
        	         <span><input type="text" id="mobile" name="mobile" placeholder="请输入您的电话" value="<?php echo $postdata['mobile'];?>"></span>
        	         <span id="msg_mobile" class='msg'></span>
        	   </li>
        	    <li>
        	         <span class="orderp s1">小区地址</span>
        	         <span><input type="text" id="address_1" name="address_1" listbody="#reslist" value="<?php echo $postdata['address_1'];?>" onkeyup="AWaitingList('#address_1','#listbox',checkaddress);" placeholder="请填写您所在的小区地址">
                           <input type="hidden" id="address_1_poi" name="address_1_poi" value="" />
                      <div id="listbox"></div>
        	         </span>
        	         <span id="msg_address_1" class='msg'></span>  
        	   </li>
        	    <li>
        	         <span class="orderp s1">楼宇门牌号</span>
        	         <span><input type="text" id="address_2" name="address_2" value="<?php echo $postdata['address_1'];?>" placeholder="请写您家的楼宇门牌号"></span>
        	         <span id="msg_address_2" class='msg'></span>
        	   </li>
        	    <li class="order-btn">
        	         <input type="hidden" id="default_shipping_method" value="meishisong" />
        	         <input type="hidden" id="shipping_code" name="shipping_code" value="meishisong" />
        	         <input type="hidden" id="shipping_data" name="shipping_data" value="" />
        	         <input type="hidden" id="shipping_city" name="shipping_city" value="北京市" />
        	         <span><a onclick="saveShippingAddress();">保存收货地址</a></span>
        	   </li>
  </ul>
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
<?PHP if($addresses){ ?><div id="addresslist">

           <?PHP foreach($addresses as $key3=>$address){?>
            <ul id="order-address-<?php echo $address['address_id'];?> " data="<?php echo $address['address_id'];?>" class="order-address<?php if ($address['address_id']==$shipping_address_id){ echo ' active';}?>">
                 <li>
                     <span class="orderp s1">收货人</span>
                     <span><?php echo $address['firstname'] ;?></span>
                     <span class="orderp tt4"><?php if ($address['address_id']!=$shipping_address_id){?><a onclick="deleteShippingAddress(this,<?php echo $address['address_id'];?>)">删除</a><?php }?></span>
                </li>
                <li> 
                      <span class="orderp s1">电  话</span>   
                      <span><?php echo $address['mobile'] ;?></span>
               </li>
               <li>
                       <span class="orderp s1">小区地址</span>
                       <span><?php echo $address['address_1'] ;?>
                       </span>
                      </li>
                 <li>
                       <span class="orderp s1">楼宇门牌</span>
                       <span><?php echo $address['address_2'] ;?></span>
                </li>
      </ul>
<?php } ?>

</div>
<?php }?>
<?php if($this->config->get('pickupaddr_status')){?>
<ul>
   <li class="order-title1"><img src="assets/image/lbs/1_18.png">附近的自提点</li>
</ul>
 <div id="filterBox" class="filterBox">
</div>
<ul id="listBoby">
</ul>
<ul>
<li>
<div id="pager"></div></li>
</ul>
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
 <?php }?>
</div>
<script src="http://api.map.baidu.com/api?v=1.5&ak=<?php echo BDYUN_WEB_AK;?>" type="text/javascript"></script>
<script src="http://api.map.baidu.com/components?ak=<?php echo BDYUN_WEB_AK;?>&v=1.0"></script>
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

$("#order-listaddress .order-address").bind("click touchmove",function(){
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
             layer.closeAll();
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
	  
  });
  
});  

function checkaddress(address,poi)
{
	<?php if($this->config->get('pickupaddr_status')){?>
	setPointByAddress(address);
	<?php }?>
	var url='apiv3/index.php?route=lbs/shipping/hgetByLng&callback=?';
	 $.getJSON(url,
             //{'address' : address},  
		 poi.location,
			 function(e) {
		 console.log(e);
		 if(e.status==1)
			 {
		     	$("#msg_address_1").html(e.message+'['+e.data.city+e.data.region_name+']');
			    $("#msg_address_1").css('color','green');
			    
			    if(poi)
				   	   $('#address_1_poi').val(poi.location.lng+','+poi.location.lat);
			    console.log(poi);
			    
			    
			   $('#shipping_city').val(e.data.city);
		       $('#shipping_code').val(e.data.shippingcode);
		       $('#shipping_data').val(e.data.region_name);
			    
			 }
		 else
			 {
		        $("#msg_address_1").html(e.message);
		        $("#msg_address_1").css('color','red');
		        $('#shipping_city').val('');
			    $('#shipping_code').val('');
			    $('#shipping_data').val('');
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
	//	$("#msg_address_2").html('请填写您的楼宇门牌号');
	//	$('#address_2').focus();
	//	return false;
	}
	
	  $.ajax({
            url: 'index.php?route=checkout/checkout/shipping_method_load',
            type: 'post',
            cache:false,
            data: 'address_id=0&firstname='+$('#firstname').val()+'&mobile='+$('#mobile').val()+'&address_1='+$('#address_1').val() +'&address_2='+$('#address_2').val()+'&shipping_city='+$('#shipping_city').val()+'&shipping_code='+$('#shipping_code').val()+'&shipping_data='+$('#shipping_data').val(),
            dataType: 'json',
            success: function (json) {
                console.log(json);
                if(json.status=='0'){
                 $('#shipping-method .shipping-method-list').html(json.data.shipping_method);
                 layer.closeAll();
         	     updateAdditionalDate();
         	     checkoutComfirm();	
                }
                else
               {$('.msg').html('');
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
           layer.closeAll();
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
        	if(json.error){
        		alert(json.message);
        	}
        }
    });

}


function showPickAddress(obj){
	var height=$(window).height();
    layer.open({
    type: 1,
    shade:[0.5 , '#000' , true],
    title: false, //不显示标题
    area: ['640px','460px'],
    content:obj//捕获的元素
}); 
}

</script>
<?php }?>
<div>
<?php echo $footer; ?>