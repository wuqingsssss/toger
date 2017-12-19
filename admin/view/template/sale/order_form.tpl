<div class="box">
    <div class="heading">
        <h2><img src="view/image/order.png" alt=""/> <?php echo $heading_title; ?></h2>

        <div class="buttons">
            <a id="save-form-btn" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
            <a  onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
        </div>
    </div>
    <div class="content">
        <div class="vtabs">
            <a href="#tab-order"><?php echo $tab_order; ?></a>
            <a href="#tab-product"><?php echo $tab_product; ?></a>
            <a href="#tab-ztd">自提/宅配</a>
            <a href="#tab-order-note"><?php echo $tab_order_note; ?></a>
        </div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <div id="tab-order" class="vtabs-content">
                <table class="form">
                    <tr class="hidden">
                        <td><?php echo $entry_store; ?></td>
                        <td><select name="store_id">
                                <option value="0"><?php echo $text_default; ?></option>
                                <?php foreach ($stores as $store) { ?>
                                    <?php if ($store['store_id'] == $store_id) { ?>
                                        <option value="<?php echo $store['store_id']; ?>"
                                                selected="selected"><?php echo $store['name']; ?></option>
                                    <?php } else { ?>
                                        <option
                                            value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_customer; ?></td>
                        <td>
                        <?PHP if($operation == EnumOperation::INSERT){?>
                        <input type="text" name="customer" value="<?php echo $customer; ?>"/><?php }?>
                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>"/></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_firstname; ?></td>
                        <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" readonly="readonly"/>
                            <?php if ($error_firstname) { ?>
                                <span class="error"><?php echo $error_firstname; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required"></span> <?php echo $entry_telephone; ?></td>
                        <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" required="" readonly="readonly"/>
                            <?php if ($error_telephone) { ?>
                                <span class="error"><?php echo $error_telephone; ?></span>
                            <?php } ?></td>
                    </tr>
         
                     <tr>
                        <td>来源</td>
                        <td>
                           <input type="radio" name="partner_code" value="" checked="checked"/>内站&nbsp&nbsp&nbsp&nbsp  
                            <?php foreach ($partners as $key => $value) { ?>
                           <input type="radio" name="partner_code" value="<?php echo $key;?>" <?php if($partner_code==$key) echo' checked="checked"';?> /><?php echo $value; ?>&nbsp&nbsp&nbsp&nbsp
                            <?php } ?>
                        
                        </td>
                    </tr>
                    
                     <tr>
                        <td>第三方订单ID</td>
                        <td><input type="text" name="tp_order_id" value="<?php echo $tp_order_id; ?>" />
   </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_order_status; ?></td>
                        <td><?PHP if($operation == EnumOperation::INSERT){?>
                            <?php if ($orderaction=='insert') $default_status = 2;else $default_status=$order_status_id; ?>
                            <select name="order_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                    <?php if ($order_status['order_status_id'] == $default_status) { ?>
                                        <option value="<?php echo $order_status['order_status_id']; ?>"
                                                selected="selected"><?php echo $order_status['name']; ?></option>
                                    <?php } else { ?>
                                        <option
                                            value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <?php }else{ ?>
                                <?php echo $order_status_info['name']; ?> <input type="hidden" name="order_status_id" value="<?php echo $order_status_id; ?>"/>
                            <?php }?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_comment; ?></td>
                        <td><?PHP if($operation == EnumOperation::INSERT){?><textarea name="comment" cols="40" rows="5"><?php echo $order_comment; ?></textarea><?php }else{?>
                        <input type="hidden" name="comment" value="<?php echo $order_comment; ?>>
                        <?php }?></td>
                    </tr>
                    <tr class="hidden">
                        <td><?php echo $entry_affiliate; ?></td>
                        <td>
                            <input type="text" name="affiliate" value="<?php echo $affiliate; ?>"/>
                            <input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>"/></td>
                    </tr>
                </table>
            </div>
            <div id="tab-product" class="vtabs-content">
                <table id="product" class="list">
                    <thead>
                        <tr>
                            <td class="left"><?php echo $column_product; ?></td>
                            <td class="left"><?php echo $column_quantity; ?></td>
                            <td class="left"><?php echo $column_price; ?></td>
                            <td class="left"><?php echo $column_promotype;?></td>
                            <td class="left"><?php echo $column_promotion; ?></td>
                            <td class="left"><?php echo $column_total; ?></td>
                            <?php if($operation == EnumOperation::INSERT){?>
                            <td class="left"><?php echo $column_action; ?></td>
                            <?php }?>
                        </tr>
                    </thead>
                    <?PHP if($operation == EnumOperation::INSERT){?>
                    <?php foreach($order_products as $key=>$product){?>
                    <tbody id="product-row<?php echo $key;?>" >
                        <tr class="product-row">
                           <td class="left"><input required="" type="text" name="order_product[<?php echo $key;?>][name]" value="<?php echo $product['name'];?>" />
                                            <input type="hidden" name="order_product[<?php echo $key;?>][order_product_id]" value="<?php echo $product['order_product_id'];?>" />
                                            <input type="hidden" name="order_product[<?php echo $key;?>][product_id]" value="<?php echo $product['product_id'];?>" />
                                            <?php if ($error_order_product[$product['product_id']]) { ?>
                                                <span class="error" style="color:#FF0000"><?php echo $error_order_product[$product['product_id']]; ?></span>
                                            <?php } ?></td>
                                            <input type="hidden" required="" name="order_product[<?php echo $key;?>][product_id]" value="<?php echo $product['product_id'];?>" /></td>
                           <td class="left"><input required="" type="text" class="input-quantity"  name="order_product[<?php echo $key;?>][quantity]" value="<?php echo $product['quantity'];?>" size="3" /></td>
                           <td class="left"><input required="" type="text" class="input-price" name="order_product[<?php echo $key;?>][price]" value="<?php echo $product['price'];?>" size="4" /></td>
                           <td class="left"><input type="text" name="order_product[<?php echo $key;?>][promotion_code]" value="<?php echo $product['promotion_code'];?>" /></td>       
                           <td class="left"><input type="text" class="input-promotion_price" name="order_product[<?php echo $key;?>][promotion_price]" value="<?php echo $product['promotion_price'];?>" size="4" /></td>
                           <td class="left"><span id="order_product[<?php echo $key;?>][total]" class="total" ></span></td>                 
                           <td class="left"><a onclick="$('#product-row<?php echo $key;?>').remove();calcTotalPrice();" class="btn btn-warning"><span><?php echo $button_remove; ?></span></a></td>        
                        </tr>
                    </tbody>
                    <?php }?>

                    <tfoot>
                        <tr>
                            <td colspan="6"></td>
                            <td class="left"><a onclick="addProduct();"
                                                class="btn btn-success"><span><?php echo $button_add_product; ?></span></a>
                            </td>
                        </tr>
                    </tfoot>
                    <?PHP }
                    else
                    {?>
                    <?php foreach($order_products as $key=>$product){?>
                    <tbody id="product-row<?php echo $key;?>" >
                        <tr class="product-row">
                           <td class="left"><?php echo $product['name'];?></td>
                           <td class="left"><?php echo $product['quantity'];?></td>
                           <td class="left"><?php echo $product['price'];?></td>
                           <td class="left">
                                <?php if(!empty($product['promotion_code'])){?>
                                    <?php echo $product['promotion_price'];?>
                                <?php }?>
                           </td>
                            <td class="left">
                                <?php if(!empty($product['promotion_code'])){?>
                                    <?php echo $product['promotion_code'];?>
                                <?php }?>
                           </td>
                           <td class="left"><?php echo $product['total'];?></td>
                        </tr>
                    </tbody>
                    
                    <?php }?>
                    
                    <?PHP }?>
                </table>
                <div class="clearfix">
                    <input type="hidden" id="total" value="0" name="total" required=""/>
                    <h4 class="pull-right">总计：<span id="total-display"><?php echo $order_total;?></span> &nbsp;</h4>
                </div>
            </div>
            <div id="tab-ztd" class="vtabs-content">
               <input type="radio" name='stype' value='1'<?php if($shipping_point_id>0){?> checked="checked"<?php }?> onclick="changestype(1);">自提  &nbsp&nbsp&nbsp&nbsp
               <input type="radio" name='stype' value='2'<?php if($shipping_point_id==0){?> checked="checked"<?php }?> onclick="changestype(2);">宅配
                <?php if ($error_stype) { ?>
                                <span class="error"><?php echo $error_stype; ?></span>
                            <?php } ?>
                <style type="text/css">
                    #ztd {
                        background: #DCDCDC;
                        width: 600px;
                    }
                    #ztd .ztd-content {
                        display: block;
                        padding: 10px;
                        background: rgb(255, 255, 255);
                        overflow: hidden;
                    }

                    #ztd .ztd-content-right {
                    }

                    #ztd .ztd-content .tip {
                        font-size: 18px;
                    }

                    #ztd input {
                    }

                    #ztd a.active {
                        color: red;
                    }

                    #ztd dl {
                        padding-left: 80px;
                        float：left;
                        overflow: hidden;
                        clear: left;
                        position: relative;
                        margin-bottom: 20px;
                    }

                    #ztd dt {
                        width: 80px;
                        position: absolute;
                        top: 0;
                        left: 0;
                    }

                    #ztd dd {
                        display: inline-block;
                        margin-right: 20px;
                    }

                    .ztd-point {
                    }

                    .i-point-detail {
                        border-top: 1px dashed #DBE4EB;
                        background-color: #FAFAFA;
                        height: auto;
                    }

                    .point-detail {
                        padding: 10px;
                    }
                </style>
                <table class="form">
                  <tr>
                        <td><span class="required">*</span><?php echo $entry_shipping_firstname; ?></td>
                        <td><input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" class="requiredshipping"/>
                            <?php if ($error_shipping_firstname) { ?>
                                <span class="error"><?php echo $error_shipping_firstname; ?></span>
                            <?php } ?></td>
                    </tr>
                     <tr>
                        <td><span class="required">*</span><?php echo $entry_shipping_mobile; ?></td>
                        <td><input type="text" name="shipping_mobile" value="<?php echo $shipping_mobile; ?>" class="requiredshipping"/>
                            <?php if ($error_shipping_mobile) { ?>
                                <span class="error"><?php echo $error_shipping_mobile; ?></span>
                            <?php } ?></td>
                    </tr>
                </table>
                <div id="ztd" data-url="index.php?route=sale/order/initdata">
                    <!-- 自提点 -->
                    <div class="ztd-content">
                        <div class="ztd-content-right">
                            <p class="tip" style="margin-top:10px; margin-bottom:20px;">请选择附近的自提点</p>
                            <span class="required">*</span><span>取菜时间：</span><input type="date" name="pdate" required="" value="<?php echo $pdate; ?>" class="requiredztd"/>
                                <?php if ($error_pdate) { ?>
                                <span class="error"><?php echo $error_pdate; ?></span>
                            <?php } ?>
                            <br/>
                            <span class="required">*</span><span>自提点：   </span><input type="text" name="shipping_method" value="<?php if($shipping_point_id>0) echo $shipping_method; ?>" style="width:400px"/>
                            <input type="hidden" name="shipping_point_id" value="<?php if($shipping_point_id>0) echo $shipping_point_id; ?>"  required="" class="requiredztd" />
                            <div class="step1" style="margin-top:10px;">
                                <div class="i-area ztd-area">
                                    <dl>
                                        <dt>所在区域:</dt>
                                    </dl>
                                </div>
                                <div class="i-cbd ztd-cbd">
                                    <dl>
                                        <dt>所在商圈:</dt>
                                    </dl>
                                </div>
                                <div class="i-point ztd-point">
                                    <dl>
                                        <dt>自提点:</dt>
                                    </dl>
                                </div>
                                <div class="i-point-detail" style="display:none;">
                                    <div class="point-detail"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table id="shipping" style="display: none;" class="form">
                     <tr>
                        <td><span class="required">*</span> 小区地址</td>
                        <td><input type="text" id="shipping_address_1" name="shipping_address_1" value="" class="requiredshipping" />
                            <input type="hidden" id="shipping_code" name="shipping_code" value="<?php echo $shipping_code; ?>" />
                            <input type="hidden" id="shipping_data" name="shipping_data" value="<?php echo $shipping_data; ?>" class="requiredshipping" />
                             <input type="hidden" id="shipping_poi" name="shipping_poi" value="<?php echo $shipping_poi; ?>"   class="requiredshipping" />
                            <input type="hidden" id="shipping_method" name="shipping_method" value="<?php echo $shipping_method; ?>" class="requiredshipping"/>
                            <input type="hidden" id="shipping_city" name="shipping_city" value="<?php echo $shipping_city; ?>"/>
                            <span id="msg_shipping_address" class="error" style="color:green"><?php echo $shipping_code.$shipping_data; ?><?php echo $error_shipping_address_1; ?></span>
                            <div id="searchResultPanel" style="display:none;"></div>
                            <div id="l-map" style="width: 250px;height: 250px;float: left;margin-left: 4px;overflow: hidden; position: relative;text-align: left; background-color: rgb(243, 241, 236);">
   </div>
                  <div id="selectaddress">
                  <?php foreach($customer_addresses as $address){?>
                  <input type="radio" name="customer_addresses" value="<?php echo $address['address_id'];?>"<?php if($address['address_1']==$shipping_address_1){?> checked="checked"<?php }?> onclick="change_shipping_address(this);" data-firstname="<?php echo $address['firstname'];?>" data-mobile="<?php echo $address['mobile'];?>" data-poi="<?php echo $address['poi'];?>" data-address_1="<?php echo $address['address_1'];?>" data-address_2="<?php echo $address['address_2'];?>" /><?php echo $address['address_1'].$address['address_2'];?>
                 <br/>
                 </div>

<?php };?>
                          </td>
                    </tr>
                      <tr>
                        <td><span class="required">*</span>送菜时间</td>
                        <td>
                            <input type="date" name="shipping_date" onchange="set_business_hours();" class="datetime requiredshipping"  value="<?php echo $shipping_date; ?>"/>
                            <select id="shipping_time" name="shipping_time" class="requiredshipping">
                            </select>
  
                            <span id="error_shipping_date" class="error"><?php echo $error_shipping_date; ?></span>
          
                        </td>
                    </tr>
                     <tr>
                        <td> 门牌号</td>
                        <td><input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" />
                         <span id="msg_shipping_address2" class="error"><?php echo $error_shipping_address_2; ?></span>
                            </td>
                    </tr>
                        
                     <tr>
                        <td> 已开通区域</td>
                        <td> <span id="allowarea"></span>
                          </td>
                    </tr>
                </table>
            </div>
            <div id="tab-order-note" class="vtabs-content">
                <table class="list">
                    <thead>
                        <tr>
                          <td class="left"><b><?php echo $column_date_added; ?></b></td>
                          <td class="left"><b><?php echo $column_order_comment; ?></b></td>
                          <td class="left"><b><?php echo $column_operator; ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($shistories) { ?>
                        <?php foreach ($shistories as $shistory) { ?>
                        <tr>
                          <td class="left"><?php echo $shistory['date_added']; ?></td>
                          <td class="left"><?php echo $shistory['comment']; ?></td>
                          <td class="left"><?php echo $shistory['operator']; ?></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                          <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <table class="form">
                    <tr>
                        <td><?php echo $entry_order_comment; ?></td>
                        <td><textarea name="comment1" cols="40" rows="5"><?php echo $comment; ?></textarea></td>
                    </tr>
                </table>
            </div>
        </form>
    </div>
</div>
<script src="http://api.map.baidu.com/api?v=2.0&ak=pUQv6G1P9uhBINarLQoOliVz" type="text/javascript"></script>

<script type="text/javascript">
_.lpad = function (ori, len, char) {
    var str = String(ori),
        i,
        max = '';
    l = str.length;
    len = len || 2;
    char = char || '0';
    for (i = 0; i < len; i += char.length) {
        max += char;
    }
    return max.substr(0, len - l) + str;
};

_.formatTime = function (t, f) {
    var floor = Math.floor,
        size,
        i,
        char,
        next,
        out,
        oneDay = 24 * 3600e3,
        oneHour = 3600e3,
        oneMinute = 60e3,
        oneSecond = 1e3,
        day = floor(t / oneDay),
        hour = floor((t % oneDay) / oneHour),
        minute = floor((t % oneHour) / oneMinute),
        second = floor((t % oneMinute) / oneSecond),
        microSecond = t % oneSecond;
    return f.replace(/[\\a-z{}]+/g, function (format) {
        size = format.length;
        out = '';
        chkchar='';
        key=false;
        for (i = 0; i < size; i++) {
            char = format[i];
            if (char == '{') {
            	key=true;
            	chkchar='';
            	continue;
            }
            
            if(key){
            	 if (char == '}') {
                 	key=false;
                 }
            	 else{
            	 chkchar+=char;
            	 continue;
            	 }
            }
            else
            {
            	 out += char;
            	 continue;
            } 
           
            switch (chkchar) {
                case 'd':
                    out += day;
                    break;
                case 'dd':
                    out += _.lpad(day);
                    break;
                case 'h':
                    out += hour;
                    break;
                case 'hh':
                    out += _.lpad(hour);
                    break;
                case 'm':
                    out += minute;
                    break;
                case 'mm':
                    out += _.lpad(minute);
                    break;
                case 's':
                    out += second;
                    break;
                case 'ss':
                    out += _.lpad(second);
                    break;
                case 'i':
                case 'ii':
                    out += microSecond;
                    break;
                default :
                    out += format;
            }
        }
        return out;
    });
};

</script>
<script>
var Util={ config:{
     'ak'         : '' 
}};
Util.config.ak='pUQv6G1P9uhBINarLQoOliVz';

var business_hours=[];
</script>
<script>
//百度地图API功能
/* 百度地图支持开始*/
var map = new BMap.Map("l-map");
   map.centerAndZoom("北京",12);                   // 初始化地图,设置城市和地图级别。

    map.addEventListener("click",function(e){
	     map.clearOverlays();    //清除地图上所有覆盖物
	    var marker=new BMap.Marker(e.point);
		   var  pi={};
		    pi.location=marker.point;  
	       marker.enableDragging();
	       marker.addEventListener('dragend', function(){
	    	   var  pi={};
			    pi.location=marker.point;
	      //console.log('Listener_click',pi);
		   getPoint(pi,checkpoi);
	      });
	     map.addOverlay(marker);    //添加标注
	   getPoint(pi,checkpoi);
  });

   //console.log(console);
   var timer;
var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
	{"input" : "shipping_address_1"
	,"location" : map
	,'onSearchComplete':function(e){
		clearTimeout(timer);
		timer=setTimeout(function(){
    	if(e.getNumPois()==0){
    	getPointByAddress(e.keyword,checkpoi);
    	}
    	else
    	{
    			var _value=e.getPoi(0);
            	myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
            	$("#searchResultPanel").html("onconfirm<br />index = " + 0 + "<br />myValue = " + myValue);	
            	setPlace();

    	}
    	}, 3000 );
    }
});


$("#shipping_address_1").bind('copy cut paste',function(){
	clearShippingData();
	ac.search($("#shipping_address_1").val());
});


ac.setInputValue('<?php echo $shipping_address_1; ?>');//是否开始拖拽地址跟随
var myValue;
ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
var _value = e.item.value;
	myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
	$("#searchResultPanel").html("onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue);	
	setPlace();
});	

<?php if($shipping_poi){?>
$(function(){
	var c =("<?php echo $shipping_poi;?>").split(' ');
	var poi={};
		 poi.location=new BMap.Point(c[0],c[1]);
	map.clearOverlays();    //清除地图上所有覆盖物
	map.centerAndZoom(poi.location, 14);

	var marker=new BMap.Marker(poi.location);
	  marker.enableDragging();
	  
	  marker.addEventListener('dragend', function(){
		   var  pi={};
		    pi.location=marker.point;
		    getPoint(pi,checkpoi);
	  });
	  //console.log(marker);
	 map.addOverlay(marker);    //添加标注
	 var pii={};
	    pii.location=marker.point;
	   // getPoint(pii,checkpoi);
	    
	    checkpoi('<?php echo $shipping_address_1; ?>',pii);
	 
});
<?php }else{?>
getPointByAddress('<?php echo $shipping_address_1; ?>',checkpoi);
<?php }?>

/* 百度地图支持结束*/ 
function setPlace(){
	map.clearOverlays();    //清除地图上所有覆盖物
	function myFun(){
		var poi = local.getResults().getPoi(0);    //获取第一个智能搜索的结果
		poi.location=poi.point;
		map.centerAndZoom(poi.point, 14);
		
		var marker=new BMap.Marker(poi.point);
		   marker.enableDragging();
		   
		   marker.addEventListener('dragend', function(){
			   var  pi={};
			    pi.location=marker.point;
			    getPoint(pi,checkpoi);
		   });
		  map.addOverlay(marker);    //添加标注
		  checkpoi(poi.address,poi);
		
	}
	var local = new BMap.LocalSearch(map, { //智能搜索
	  onSearchComplete: myFun,
	  onResultsHtmlSet:clearShippingData
	});
	local.search(myValue);
}

function clearShippingData(){
	 $('#shipping_city').val('');
	 $('#shipping_code').val('');
	 $('#shipping_data').val('');
	 $('#shipping_poi').val('');
	 $('#shipping_method').val('');
	 $("#msg_shipping_address").html('');
}


function getPointByAddress(address,callback)
{   
	  	//console.log(address);
	  var url = "http://api.map.baidu.com/geocoder/v2/?callback=?";
	     // url = "http://api.map.baidu.com/place/v2/suggestion?callback=?";
      $.getJSON(url, {
          'address'          :address, //检索关键字
          'output'     :'json',
          'region'     : '131',  //北京的城市id
          'ak'         : Util.config.ak  //用户ak
      },function(e) {
    	  //console.log(e);
   	if(e.status==0){
   	     poi=e.result;
            if(poi.confidence>60){
   		 var point=new BMap.Point(poi.location.lng,poi.location.lat);

   		 map.clearOverlays();    //清除地图上所有覆盖物
 	    var marker=new BMap.Marker(point);
 		   var  pi={}; 
 		    pi.location=marker.point;  
 	       marker.enableDragging();
 	       marker.addEventListener('dragend', function(){
 	    	  var  pi={};
 			    pi.location=marker.point;
 		   getPoint(pi,checkpoi);
 	      });
 	     map.addOverlay(marker);    //添加标注
   		 
   		 
   	      map.centerAndZoom(point,map.getZoom());//,14
   	       //if(!$('#'+ac.nc.input).val())
          ac.setInputValue(address);//是否开始拖拽地址跟随
         if(callback)callback(address,poi);
            }
        }
   	else
   		{
   		     clearShippingData();
		    $("#msg_shipping_address").html('地址不准确请重新输入');
	        $("#msg_shipping_address").css('color','red');
   		}
     });
     

}
function getPoint(poi,callback)
{   
	  //callback($(keyword).val());

	  var url = "http://api.map.baidu.com/geocoder/v2/?callback=?";
	     // url = "http://api.map.baidu.com/place/v2/suggestion?callback=?";
     $.getJSON(url, {
         'location'          :''+poi.location.lat+','+poi.location.lng+'', //检索关键字
         'output'     :'json',
         'region'     : '131',  //北京的城市id
         'pois'      : '0',  //显示详细信息
         'ak'         : Util.config.ak  //用户ak
     },function(e) {
   	if(e.status==0){
   	   poi=e.result;
   		 console.log('getPoint',poi);
   		 var point=new BMap.Point(poi.location.lng,poi.location.lat);
   	      map.centerAndZoom(point, 14);
   	   //if(!$('#'+ac.nc.input).val())
          ac.setInputValue(poi.formatted_address);//是否开始拖拽地址跟随
         if(callback)callback(poi.formatted_address,poi);
        }
     });
     

}



function checkpoi(address,poi,callback){
//console.log('checkpoi',poi);
if(!poi){
	_.alert('位置查询失败，请修改地址!');
	return;	
}
else if(poi.confidence && poi.confidence < 60) {
	_.alert('请提供更详细小区地址!');
	return;	
}

var url='<?php echo HTTP_CATALOG;?>apiv3/index.php?route=lbs/shipping/hgetByLng&callback=?';
$.getJSON(url,
		 poi.location,
		 function(e){checkaddress_callback(e,callback,poi);});  

}

function checkaddress_callback(e,callback,poi){
console.log('checkaddress_callback e',e);

	 if(e.status==1)
		 {
		// _.toast('地址有效',2000);
		  // $("#address_1").css('color','green');	
		 //console.log(e.data.city+e.data.region_name);
		    $("#msg_shipping_address").html(e.message+'['+e.data.shippingcode+e.data.city+e.data.region_name+']');
		    $("#msg_shipping_address").css('color','green');
		    $('#shipping_city').val(e.data.city);
		    $('#shipping_code').val(e.data.shippingcode);
		    $('#shipping_data').val(e.data.region_name);
		    $('#shipping_poi').val(poi.location.lng+' '+poi.location.lat);
		    $('#shipping_method').val(e.data.shippingcode+e.data.region_name);

		    
		    if(e.data.business_hours){
		    	business_hours=e.data.business_hours.t1;
		    	set_business_hours();
		    }else
		    	{
		    	business_hours=[];
		    	set_business_hours();
		    	}
		    
	      // if(poi)
	   	     //$("#address_1_poi").val(JSON.stringify(poi));
	       
	       if(typeof(callback)=='function')
	    	   {
	    	   callback(e);
	    	   }
		    
		 }
	 else
		 { 
		    business_hours=[];
	    	set_business_hours();
		    $("#msg_shipping_address").html(e.message);
	        $("#msg_shipping_address").css('color','red');
		 }

}

function set_business_hours(){
	
	//console.log('e.data.business_hours',e.data.business_hours);
	$('select[name="shipping_time"]').html('');
	if(business_hours.length<1){ 
		$('#error_shipping_date').html('请确认配送方式和日期是否正确才能选择时间');
		return;}else{
			$('#error_shipping_date').html('');
		}
	for(var i in business_hours){
		var nowDate = new Date();
		var inputDate = new Date($('input[name="shipping_date"]').val());
		var startDate = new Date(inputDate.toDateString()+' '+business_hours[i]['start']+' GMT');
		var endDate = new Date(inputDate.toDateString()+' '+business_hours[i]['end']+' GMT');
		//console.log(inputDate.toUTCString());
		
		for(var di=startDate.getTime();(di+business_hours[i]['setup']*60000)<=endDate.getTime();di+=business_hours[i]['setup']*60000)
		{
			var time=_.formatTime(startDate.setTime(di+business_hours[i]['setup']*60000),'{hh}:{mm}');
			var title=_.formatTime(startDate.setTime(di),'{hh}:{mm}')+'-'+time;
		     if(di>(nowDate.getTime()+(business_hours[i]['setup']+60+480)*60000))
		 $('select[name="shipping_time"]').append('<option value="'+time+'"'+(time=="<?php echo $shipping_time;?>"?' selected="selected"':'')+'>'+title+'</option>');
		}
	}
	if($('select[name="shipping_time"]').html()==''){ 
		$('#error_shipping_date').html('请确认配送方式和日期是否正确才能选择时间');
		return;}else{
			$('#error_shipping_date').html('');
		}
}

$(document).ready(function(){
	var url='<?php echo HTTP_CATALOG;?>apiv3/index.php?route=lbs/shipping/getAllowarea&callback=?';
	 $.getJSON(url,{},
			 function(e) {
		     //console.log(e);
		 if(e)
			 {
			 var tpl='';
			 for(shipping in e)
			 {
			   tpl+=shipping+'：'; 
			 for(city in e[shipping])
			 {
			  tpl+='<br/>';
               tpl+=city+'：';
               var ii=0;
               for(i in e[shipping][city])
               {ii++;
            	   tpl+=ii+e[shipping][city][i]['region_name']+',';
                 }
              
			 }
			  tpl+='<br/>';
			 }
			 $("#allowarea").html(tpl);
			 }
   });    	

});   
function change_shipping_address(_this){
	$("#msg_shipping_address").html('查询中请稍后');
	 $('#shipping_code').val('');
	 $('#shipping_data').val('');
	 $('#shipping_poi').val('');
	 
	 ac.setInputValue($(_this).data('address_1'));//是否开始拖拽地址跟随
	 
	  $("input[name='shipping_adddress_2']").val($(_this).data('address_2'));
	 
	  $('input[name=\'shipping_firstname\']').attr('value', $(_this).data("firstname"));
      $('input[name=\'shipping_mobile\']').attr('value', $(_this).data("mobile"));

	 
	 
	 var c =$(_this).data('poi').split(' ');

	 var poi={};
		 poi.location=new BMap.Point(c[0],c[1]);
	 
	 map.clearOverlays();    //清除地图上所有覆盖物
	 map.centerAndZoom(poi.location, 14);
	 
	 var marker=new BMap.Marker(poi.location);
	   marker.enableDragging();
	   
	   marker.addEventListener('dragend', function(){
		   var  pi={};
		    pi.location=marker.point;
		    getPoint(pi,checkpoi);
	   });
	  map.addOverlay(marker);    //添加标注
	 
	 checkpoi($(_this).data('address_1'),poi);

}
</script>
<!-- script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/jq.validate/1.13.1-pre/jquery.validate.js"
        type="text/javascript"></script-->
        
<script src="<?php echo HTTP_CATALOG;?>assets/js/jquery/jquery.validate.js"
        type="text/javascript"></script>
<script type="text/javascript">
    $('#save-form-btn').click(function () {
        var form$ = $('form');
        form$.submit();
//        return true;

    });

    jQuery.extend(jQuery.validator.messages, {
        required: "此项必填",
        email: "请输入正确的邮箱地址",
        telephone:"请输入正确的电话号码"
//        url: "Please enter a valid URL.",
//        date: "Please enter a valid date.",
//        dateISO: "Please enter a valid date (ISO).",
//        number: "Please enter a valid number.",
//        digits: "Please enter only digits.",
//        creditcard: "Please enter a valid credit card number.",
//        equalTo: "Please enter the same value again.",
//        accept: "Please enter a value with a valid extension.",
//        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
//        minlength: jQuery.validator.format("Please enter at least {0} characters."),
//        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
//        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
//        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
//        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
    });
    $("#form").validate({
        ignore: [],
        rules: {
            firstname: "required",
 //           email: {
 //               required: true,
 //               email: true
 //           },
            telephone: {
                required: true
            }
        }
//        messages: {
//            firstname: "请输入用户名",
//            email: "请输入正确的邮箱",
//            telephone: "请输入电话号码"
//        }
    });

    $.widget('custom.catcomplete', $.ui.autocomplete, {
        _renderMenu: function (ul, items) {
            var self = this, currentCategory = '';

            $.each(items, function (index, item) {
                if (item.category != currentCategory) {
                    ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

                    currentCategory = item.category;
                }

                self._renderItem(ul, item);
            });
        }
    });

    $('input[name=\'customer\']').catcomplete({
        delay: 0,
        source: function (request, response) {
        	//console.log(request);
            $.ajax({
                url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>',
                type: 'POST',
                dataType: 'json',
                data: 'filter_name=' + encodeURIComponent(request.term),
                success: function (data) {
                	//console.log(data);
                    response($.map(data, function (item) {
                        return {
                            category: item.customer_group,
                            label: item.name,
                            value: item.customer_id,
                            customer_group_id: item.customer_group_id,
                            firstname: item.firstname,
                            shipping_firstname: item.firstname,
                            telephone: item.telephone+item.mobile,
                            shipping_mobile:item.telephone+item.mobile,
                            fax: item.fax,
                            address: item.address
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('input[name=\'customer\']').attr('value', ui.item.label);
            $('input[name=\'customer_id\']').attr('value', ui.item.value);
            $('input[name=\'firstname\']').attr('value', ui.item.firstname);
            //  $('input[name=\'email\']').attr('value', ui.item.email);
            $('input[name=\'telephone\']').attr('value', ui.item.telephone);
           // $('input[name=\'fax\']').attr('value', ui.item.fax);

            $('input[name=\'shipping_mobile\']').attr('value', ui.item.telephone);
            
            html='';
            for (i = 0; i < ui.item.address.length; i++) {
                //html += '<option value="' + ui.item.address[i].address_id + '">' + ui.item.address[i].firstname + ', ' + ui.item.address[i].address_1 + ', ' + ui.item.address[i].city + ', ' + ui.item.address[i].country + '</option>';
               if(ui.item.address[i].default){
            	   ac.setInputValue(ui.item.address[i].address_1);//是否开始拖拽地址跟随
            	   
            	   $('input[name=\'shipping_firstname\']').attr('value', ui.item.address[i].firstname);
            	   $('input[name=\'shipping_mobile\']').attr('value', ui.item.address[i].mobile);
            	   $("input[name='shipping_adddress_2']").val(ui.item.address[i].address_2);
            	   
               }

                html += '<input type="radio" name="customer_addresses" value="' + ui.item.address[i].address_id + '"'+(ui.item.address[i].default ? 'checked="checked"':'')+' onclick="change_shipping_address(this);" data-firstname="' + ui.item.address[i].firstname + '" data-mobile="' + ui.item.address[i].mobile + '" data-poi="' + ui.item.address[i].poi + '" data-address_1="' + ui.item.address[i].address_1 + '" data-address_2="' + ui.item.address[i].address_2 + '\
                " />'+ ui.item.address[i].address_1  + ui.item.address[i].address_2 + '<br/>';
            }

            $('#selectaddress').html(html);
  

            return false;
        }
    })

    $('select[name=\'shipping_zone_id\']').load('index.php?route=sale/order/zone&token=<?php echo $token; ?>&country_id=<?php echo $shipping_country_id; ?>&zone_id=<?php echo $shipping_zone_id; ?>');

    var product_row = <?php echo count($order_products);?>;
    var takeTimeOptions = <?php echo json_encode(getTakeTimeOptions()); ?>

        function addProduct() {
//            var selectStr = '<select  style="width:110px;" name="order_product[' + product_row + '][pdate]">';
//            selectStr += _.map(takeTimeOptions, function (dateStr) {
//                return '<option value="' + dateStr + '">' + dateStr + '</option>';
//            }).join('');
//            selectStr += '</select>';


            html = '<tbody id="product-row' + product_row + '" >';
            html += '  <tr class="product-row">';
            html += '    <td class="left"><input required="" type="text" name="order_product[' + product_row + '][name]" value="" /><input type="hidden" name="order_product[' + product_row + '][order_product_id]" value="" /><input type="hidden" required="" name="order_product[' + product_row + '][product_id]" value="" /></td>';
            html += '    <td class="left"><input required="" type="text" class="input-quantity"  name="order_product[' + product_row + '][quantity]" value="1" size="3" /></td>';
            html += '    <td class="left"><input required="" type="text" class="input-price" name="order_product[' + product_row + '][price]" value="" size="4" /></td>';
            html += '    <td class="left"><input type="text" class="input-promotion_code" name="order_product[' + product_row + '][promotion_code]" value="" size="4" /></td>'          
            html += '    <td class="left"><input type="text" class="input-promotion_price" name="order_product[' + product_row + '][promotion_price]" value="" size="4" /></td>';
            html += '    <td class="left"><span id="order_product[' + product_row + '][total]" class="total"></span></td>';
            
            //            html += '    <td class="left">' + selectStr + '</td>';
            html += '    <td class="left"><a onclick="$(\'#product-row' + product_row + '\').remove();calcTotalPrice();" class="btn btn-warning"><span><?php echo $button_remove; ?></span></a></td>';
            html += '  </tr>';
            html += '</tbody>';

            $('#product tfoot').before(html);

            productautocomplete(product_row);

//            $("input[name^='order_product[" + product_row + "]']").each(function () {
//               $(this).rules("add", {
//                   required: true,
//                   messages: {
//                       required: "必须输入"
//                   }
//               });
//
//            });
            $("input[name*='order_product[" + product_row + "][name]']").rules("add", {
                required: true
            });
            $("input[name*='order_product[" + product_row + "][quantity]']").rules("add", {
                required: true
            });
            $("input[name*='order_product[" + product_row + "][price]']").rules("add", {
                required: true
            });

            product_row++;
        };

    $('.input-quantity,.input-price,.input-promotion_price').live('change', function () {
        calcTotalPrice();
    });
    
    <?php if($shipping_point_id>0)
 {
     echo ' changestype(1);';
    }
    else{
    	  echo ' changestype(2);';	  
    }
    ?> 
    
<?php if(!$order_products){?>
    addProduct();//add first row
<?php }?>
    function changestype(type)
    {
    	
    	if(type==1){
    		$('#ztd').show();$('#shipping').hide();
    		$('#ztd .requiredztd').attr('required',"");
    		$('#shipping .requiredshipping').removeAttr('required');
    	}
    	else
    	{
    		$('#ztd').hide();$('#shipping').show();
    		$('#ztd .requiredztd').removeAttr('required');
    		$('#shipping .requiredshipping').attr('required',"");
    	}
    	
    	
    }
    
    
    
    function checkaddress(address)
    {
    	var url='<?php echo HTTP_CATALOG;?>apiv3/index.php?route=lbs/shipping/hgetByAddress';
   	 $.getJSON(url,
   			 {
                 'address' : address,  
                },
   			 function(e) {
   		 //console.log(e);
   		 if(e.status==1)
   			 {
   			 $('#shipping_code').val(e.data.shippingcode);
   			 $('#shipping_data').val(e.data.region_name);
   			 $('#shipping_method').val(e.data.shippingcode+e.data.region_name);
   			 $('#shipping_city').val(e.data.city);
   			
   			$("#msg_shipping_address").html(e.message+'['+e.data.shippingcode+e.data.city+e.data.region_name+']');
   			$("#msg_shipping_address").css('color','green');
   			 }
   		 else
   			 {
   			 $('#shipping_code').val("meishisong");
   			 $('#shipping_data').val('');
   		     $('#shipping_method').val('');
			 $('#shipping_city').val('');
   		     $("#msg_shipping_address").html(e.message);
   		     $("#msg_shipping_address").css('color','red');
   			 }
        });    	
    	    	
    }
    
    function calcTotalPrice() {
        var total = 0;
        $('.product-row').each(function () {
            var row$ = $(this);
            var total_row   = 0;
            var num         = parseInt(row$.find('.input-quantity').val());
            var price       = parseFloat(row$.find('.input-price').val());
            var price_prom  = parseFloat(row$.find('.input-promotion_price').val());
            var total_r     =  row$.find('.total');

            if (!isNaN(price_prom)){
                if (!isNaN(num)) {
                    total_row  = num.mul(price_prom);
                    total     += total_row;

                    total_r.html(total_row);
                }
            }
            else{
         	   if (!isNaN(num) && !isNaN(price)) {
                   total_row  = num.mul(price);
                   total     += total_row;
                   
                   total_r.html(total_row);
               }
            }

            
           
        });
        $('#total-display').text(total);
        $('#total').val(total);

    }

    function productautocomplete(product_row) {
        $('input[name=\'order_product[' + product_row + '][name]\']').autocomplete({
            delay: 0,
            source: function (request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: 'filter_name=' + encodeURIComponent(request.term),
                    success: function (data) {
                        response($.map(data, function (item) {
                        	item.total = item.promotion_price * 2;
                            return {
                                label: item.name,
                                value: item.product_id,
                                pdate: item.pdate,
                                price: parseFloat(item.price).round(2),
                                promotion_price: parseFloat(item.promotion_price).round(2),
                                promotion_code: item.promotion_code,
                            }
                        }));
                    }
                });
            },
            select: function (event, ui) {
                var total;
                $('input[name=\'order_product[' + product_row + '][product_id]\']').attr('value', ui.item.value);
                $('input[name=\'order_product[' + product_row + '][name]\']').attr('value', ui.item.label);
                $('input[name=\'order_product[' + product_row + '][price]\']').attr('value', ui.item.price);            
                if("" != ui.item.promotion_code && null != ui.item.promotion_code){
             	    $('input[name=\'order_product[' + product_row + '][promotion_code]\']').attr('value', ui.item.promotion_code);
                    $('input[name=\'order_product[' + product_row + '][promotion_price]\']').attr('value', ui.item.promotion_price);
                    total = ui.item.promotion_price;
                }
                else{
                	total = ui.item.price;
                }
                var id = 'order_product[' + product_row + '][total]';
                document.getElementById(id).innerHTML = total;
             
                calcTotalPrice();

                return false;
            }
        }).bind("input.autocomplete", function () {
            $(this).autocomplete("search", this.value);
        });
    }

    $('#product tbody').each(function (index, element) {
        productautocomplete(index);
    });
    $('input[name=\'affiliate\']').autocomplete({
        delay: 0,
        source: function (request, response) {
            $.ajax({
                url: 'index.php?route=sale/affiliate/autocomplete&token=<?php echo $token; ?>',
                type: 'POST',
                dataType: 'json',
                data: 'filter_name=' + encodeURIComponent(request.term),
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.name,
                            value: item.affiliate_id
                        }
                    }));
                }
            });
        },
        select: function (event, ui) {
            $('input[name=\'affiliate\']').attr('value', ui.item.label);
            $('input[name=\'affiliate_id\']').attr('value', ui.item.value);

            return false;
        }
    });


</script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript">
    $('.date,[type=date]').datepicker({dateFormat: 'yy-mm-dd',
                                        minDate: new Date((new Date()).valueOf())
        });
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    $('.time').timepicker({timeFormat: 'h:m'});
    $('.vtabs a').tabs();
</script>
<script>
    $(document).ready(function () {
        //add func
        if (!String.format) {
            String.format = function (format) {
                var args = Array.prototype.slice.call(arguments, 1);
                return format.replace(/{(\d+)}/g, function (match, number) {
                    return typeof args[number] != 'undefined'
                        ? args[number]
                        : match
                        ;
                });
            };
        }

        function initChooseZtd() {


            var ztd$ = $('#ztd');
            var areas$ = ztd$.find('.ztd-area');
            var cbds$ = ztd$.find('.ztd-cbd');
            var points$ = ztd$.find('.ztd-point');
            var pointDetail$ = ztd$.find('.point-detail');

            var data;
            var selections = {
                areaId: null,
                cbdId: null,
                pointId: null
            };

            function init() {
                var url = ztd$.data('url');
                $.getJSON(url).then(function (result) {
                    data = result || [];
                    //console.log(data);
                    renderArea();
                });
            }

            init();

            function clearSelections(depth) {
                if (depth > 2) {
                    areas$.find('dd').remove();
                    selections.areaId = null;
                }

                if (depth > 1) {
                    cbds$.find('dd').remove();
                    selections.cdbId = null;
                }

                points$.find('dd').remove();
                pointDetail$.empty();
                selections.pointId = null;
            }

            function renderArea() {
                clearSelections(3);

                var itemTpl = '<dd><a data-id="{0}">{1}</a></dd>';
                var html = _.map(data, function (item) {
                    return String.format(itemTpl, item.city_id, item.name);
                }).join('');
                areas$.find('dl').append(html);

                areas$.find('dl dd a').click(function () {
                    var this$ = $(this);
                    selections.areaId = this$.data('id');
                    renderCbd();
                    //add active cls
                    areas$.find('dl dd a').removeClass('active');
                    this$.addClass('active');

                });
            }

            function findAreaData() {
                var areaId = selections.areaId;
                var area = _.filter(data, function (item) {
                    return item.city_id == areaId;
                })[0];
                return area;
            }

            function renderCbd() {
                //find parent
                var area = findAreaData();
                var cbds = area.cbds || [];

                //reload self
                clearSelections(2);

                var itemTpl = '<dd><a data-id="{0}">{1}</a></dd>';
                var html = _.map(cbds, function (item) {
                    return String.format(itemTpl, item.id, item.name);
                }).join('');
                cbds$.find('dl').append(html);

                cbds$.find('dl dd a').click(function () {
                    var this$ = $(this);
                    selections.cbdId = this$.data('id');
                    renderPoint();

                    //add active cls
                    cbds$.find('dl dd a').removeClass('active');
                    this$.addClass('active');
                });
            }

            function findCbdData() {
                var area = findAreaData();
                var cbds = area.cbds || [];
                var cbdId = selections.cbdId;
                return _.filter(cbds, function (item) {
                    return item.id == cbdId;
                })[0];
            }

            function renderPoint() {
                //find parent
                var cbd = findCbdData();
                var points = cbd.points || [];

                //reload self
                clearSelections(1);

                var itemTpl = '<dd><a data-id="{0}">{1}</a></dd>';
                var html = _.map(points, function (item) {
                    return String.format(itemTpl, item.point_id, item.name);
                }).join('');
                points$.find('dl').append(html);

                points$.find('dl dd a').click(function () {
                    var this$ = $(this);
                    var pointId = this$.data('id');
                    var point = findPoint(pointId);
                    selections.pointId = pointId;
                    var $shipping_method = $('[name="shipping_method"]');//取采点字段
                    var $shipping_point_id = $('[name="shipping_point_id"]');//取采点字段
                    var pointDisplay = point.name + '[' + point.address + ']';
                    $shipping_method.val(pointDisplay);
                    $shipping_point_id.val(pointId);


                    //add active cls
                    points$.find('dl dd a').removeClass('active');
                    this$.addClass('active');


                    console.log(selections);
                });

                function findPoint(id) {
                    var cbd = findCbdData();
                    var points = cbd.points || [];
                    var point = _.filter(points, function (item) {
                        return item.point_id == id;
                    })[0];
                    return point;
                }

                points$.find('dl dd a').mouseover(function () {
                    var this$ = $(this);
                    var id = this$.data('id');
                    var point = findPoint(id);
                    pointDetail$.html(renderPointDetail(point));
                    $('.i-point-detail').show();
                }).mouseout(function () {
                    pointDetail$.empty();
                    $('.i-point-detail').hide();
                });
            }

            function renderPointDetail(point) {
                var html = '<ul>';

                html += '<li>地址：' + point.address + '</li>';
                html += '<li>营业时间：' + point.business_hour + '</li>';
                html += '<li>联系电话：' + point.telephone + '</li>';

                html += '</ul>';
                return html;
            }

        }

        initChooseZtd();
    });
</script>
