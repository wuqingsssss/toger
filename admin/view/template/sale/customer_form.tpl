<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
	 <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a> <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="htabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a>
        <?php if ($customer_id) { ?>
        <a href="#tab-transaction"><?php echo $tab_transaction; ?></a>
        <a href="#tab-reward"><?php echo $tab_reward; ?></a>
        <a href="#tab-coupon"><?php echo $tab_coupon; ?></a>
        <?php } ?>
        <?php if(false) {?>
        <a href="#tab-ip"><?php echo $tab_ip; ?></a>
        <?php } ?>
        </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <div id="vtabs" class="vtabs"><a href="#tab-customer"><?php echo $tab_general; ?></a>
            <?php $address_row = 1; ?>
            <?php foreach ($addresses as $address) { ?>
            <a href="#tab-address-<?php echo $address_row; ?>" id="address-<?php echo $address_row; ?>"><?php echo $tab_address . ' ' . $address_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#address-<?php echo $address_row; ?>').remove(); $('#tab-address-<?php echo $address_row; ?>').remove(); return false;" /></a>
            <?php $address_row++; ?>
            <?php } ?>
            <span id="address-add"><?php echo $button_add_address; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addAddress();" /></span></div>
          <div id="tab-customer" class="vtabs-content">
            <table class="form">
             <tr>
                <td><?php echo $entry_firstname; ?></td>
                <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                  <?php if ($error_firstname) { ?>
                  <span class="error"><?php echo $error_firstname; ?></span>
                  <?php } ?></td>
              </tr>
             <tr>
                <td> <?php echo $entry_lastname; ?></td>
                <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                  <?php if ($error_lastname) { ?>
                  <span class="error"><?php echo $error_lastname; ?></span>
                  <?php } ?></td>
              </tr>
     		 <tr>
                <td><?php echo $entry_email; ?></td>
                <td><input type="text" name="email" value="<?php echo $email; ?>" />
                  <?php if ($error_email) { ?>
                  <span class="error"><?php echo $error_email; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td> <?php echo $entry_mobile; ?></td>
                <td><input type="text" name="mobile" value="<?php echo $mobile; ?>" />
                  </td>
              </tr>
              <tr>
                <td> <?php echo $entry_telephone; ?></td>
                <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" />
                  </td>
              </tr>
              <tr>
                <td><?php echo $entry_fax; ?></td>
                <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_password; ?></td>
                <td><input type="password" name="password" value="<?php echo $password; ?>"  />
                  <br />
                  <?php if ($error_password) { ?>
                  <span class="error"><?php echo $error_password; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_confirm; ?></td>
                <td><input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                  <?php if ($error_confirm) { ?>
                  <span class="error"><?php echo $error_confirm; ?></span>
                  <?php  } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_newsletter; ?></td>
                <td><select name="newsletter">
                    <?php if ($newsletter) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_customer_group; ?></td>
                <td><select name="customer_group_id">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_status; ?></td>
                <td><select name="status">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <tr>
                <td><?php echo $entry_payment_transaction; ?></td>
                <td><select name="payment_transaction">
                    <?php if ($payment_transaction) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
            </table>
          </div>
          <?php $address_row = 1; ?>
            <script src="http://api.map.baidu.com/api?v=2.0&ak=pUQv6G1P9uhBINarLQoOliVz" type="text/javascript"></script>
          <script type="text/javascript">
          var map=[],
              ac =[],myValue=[], timer=[],poi=[];
          var Util={ config:{
        	     'ak'         : '' 
        	}};
        	Util.config.ak='pUQv6G1P9uhBINarLQoOliVz';
          </script>
          
          <?php 
           foreach ($addresses as $address) { ?>
          <div id="tab-address-<?php echo $address_row; ?>" class="vtabs-content">
            <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" />
                  <?php if (isset($error_address_firstname[$address_row])) { ?>
                  <span class="error"><?php echo $error_address_firstname[$address_row]; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $entry_lastname; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" />
                  <?php if (isset($error_address_lastname[$address_row])) { ?>
                  <span class="error"><?php echo $error_address_lastname[$address_row]; ?></span>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><span class="required"></span> <?php echo $entry_zone; ?></td>
                <td><select name="address[<?php echo $address_row; ?>][zone_id]" onchange="$('select[name=\'address[<?php echo $address_row; ?>][city_id]\']').load('index.php?route=common/localisation/city&token=<?php echo $token; ?>&zone_id=' + this.value);">
                  </select>
                  <?php if (isset($error_address_zone[$address_row])) { ?>
                  <span class="error"><?php echo $error_address_zone[$address_row]; ?></span>
                  <?php } ?></td>
              </tr>
         	  <tr>
                <td><span class="required"></span> <?php echo $entry_city; ?></td>
                <td><select name="address[<?php echo $address_row; ?>][city_id]" >
                  </select>
                  <?php if (isset($error_address_city[$address_row])) { ?>
                  <span class="error"><?php echo $error_address_city[$address_row]; ?></span>
                  <?php } ?></td>
              </tr>
               <tr>
                <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
                <td><input type="text" id="address_1_<?php echo $address_row; ?>" class="" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" />
                  <span id="msg_shipping_address_<?php echo $address_row; ?>" class="error"><?php echo $error_address_address_1[$address_row]; ?></span>
                    <div id="searchResultPanel_<?php echo $address_row; ?>" style="display:none;"></div>
                    <input type="hidden" id="address_1_poi_<?php echo $address_row; ?>" name="address[<?php echo $address_row; ?>][address_1_poi]" value="<?php echo $address['poi']; ?>" />
                   <input type="hidden" id="shipping_code_<?php echo $address_row; ?>" name="address[<?php echo $address_row; ?>][shipping_code]" value="<?php echo $address['shipping_code']; ?>" />
                   <input type="hidden" id="shipping_data_<?php echo $address_row; ?>" name="address[<?php echo $address_row; ?>][shipping_data]" value="<?php echo $address['shipping_data']; ?>" />
                   <div id="l_map_<?php echo $address_row; ?>" class="l-map" style="width: 250px;height: 250px;float: left;margin-left: 4px;overflow: hidden; position: relative;text-align: left; background-color: rgb(243, 241, 236);">
   </div>
               <script type="text/javascript">
               $(function(){
                  //百度地图API功能
                  /* 百度地图支持开始*/
                   poi[<?php echo $address_row; ?>]={};
                   poi[<?php echo $address_row; ?>].address='<?php echo $address['address_1']; ?>';  	
                  <?php if($address['location']){?>
                  poi[<?php echo $address_row; ?>].location = {lng:"<?php echo $address['location'][lng]; ?>",lat:"<?php echo $address['location'][lat]; ?>"};
                  <?php }?>
                  initMapAddress(<?php echo $address_row; ?>, poi[<?php echo $address_row; ?>]);
                  
                  $("#address_1_<?php echo $address_row; ?>").bind('copy cut paste',function(){
                		clearShippingData(<?php echo $address_row; ?>);
                		ac[<?php echo $address_row; ?>].search($("#address_1_<?php echo $address_row; ?>").val());
                	});
                  
               });
  
                  </script> 
                  </td>
              </tr>
              <tr>
                <td><?php echo $entry_address_2; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_postcode; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_mobile; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][mobile]" value="<?php echo $address['mobile']; ?>" /></td>
              </tr>
               <tr>
                <td><?php echo $entry_phone; ?></td>
                <td><input type="text" name="address[<?php echo $address_row; ?>][phone]" value="<?php echo $address['phone']; ?>" /></td>
              </tr>
             
			  <tr>
                <td><?php echo $entry_default; ?></td>
                <td>
	                <?php if ((isset($address['default']) && $address['default']) || count($addresses) == 1) { ?>
	                <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" /></td>
	                <?php } else { ?>
	                <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
					 <?php } ?>
				</td>
               </tr>
            </table>
            <script type="text/javascript"><!--
		    $('select[name=\'address[<?php echo $address_row; ?>][zone_id]\']').load('index.php?route=common/localisation/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $address['zone_id']; ?>');
            $('select[name=\'address[<?php echo $address_row; ?>][city_id]\']').load('index.php?route=common/localisation/city&token=<?php echo $token; ?>&zone_id=<?php echo $address['zone_id']; ?>&city_id=<?php echo $address['city_id']; ?>');
		    //--></script> 
          </div>
          <?php $address_row++; ?>
          <?php } ?>
        </div>
        <?php if ($customer_id) { ?>
        <div id="tab-transaction">
          <table class="form">
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="description" value="" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_amount; ?></td>
              <td><input type="text" name="amount" value="" /></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: right;"><a id="button-reward" class="btn btn-danger" onclick="addTransaction();"><span><?php echo $button_add_transaction; ?></span></a></td>
            </tr>
          </table>
          <div id="transaction"></div>
        </div>
        <div id="tab-reward">
          <table class="form">
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="description" value="" /></td>
            </tr>
            <tr>
              <td><?php echo $entry_points; ?></td>
              <td><input type="text" name="points" value="" /></td>
            </tr>
            <tr>
              <td colspan="2" style="text-align: right;"><a id="button-reward" class="button" onclick="addRewardPoints();"><span><?php echo $button_add_reward; ?></span></a></td>
            </tr>
          </table>
          <div id="reward"></div>
        </div>
        <div id="tab-coupon">
          <div id="coupon">
          <table class="list">
            <thead>
              <tr>
                <td class="right"><?php echo $column_coupon_name; ?></td>
                <td class="right"><?php echo $column_date_add; ?></td>
                <td class="right"><?php echo $column_used; ?></td>
                <td class="right"><?php echo $column_date_limit; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($coupons) { ?>
              <?php foreach ($coupons as $coupon) { ?>
              <tr>
                <td class="right"><?php echo $coupon['name']; ?></td>
                <td class="right"><?php echo $coupon['date_add']; ?></td>
                <td class="right"><?php echo $coupon['used']; ?></td>
                <td class="right"><?php echo $coupon['date_limit']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="pagination"><?php echo $pagination; ?></div>
          </div>
        </div>
        <?php } ?>
        <?php if(false) {?>
        <div id="tab-ip">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $column_ip; ?></td>
                <td class="right"><?php echo $column_total; ?></td>
                <td class="left"><?php echo $column_date_latest_login; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($ips) { ?>
              <?php foreach ($ips as $ip) { ?>
              <tr>
                <td class="left"><a onclick="window.open('http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>');"><?php echo $ip['ip']; ?></a></td>
                <td class="right"><a onclick="window.open('<?php echo $ip['filter_ip']; ?>');"><?php echo $ip['total']; ?></a></td>
                <td class="left"><?php echo $ip['date_added']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <?php } ?>
      </form>
    </div>
  </div>

<script>
/* 百度地图支持结束*/ 
  function initMapAddress(i,poi){
	           console.log(i,poi);
                   map[i] = new BMap.Map("l_map_"+i);
                   map[i].centerAndZoom("北京",12);                   // 初始化地图,设置城市和地图级别。
                   map[i].addEventListener("click"
                		   ,function(e){
                                     map[i].clearOverlays();    //清除地图上所有覆盖物
                                        var marker=new BMap.Marker(e.point);
                  		                var  pi={};
                  		                pi.location=marker.point;  
                  	                    marker.enableDragging();
                  	                    marker.addEventListener('dragend', function(){
                  	    	                  var  pi={};
                  			                  pi.location=marker.point;
                  	                           console.log('Listener_click',pi);
                  		                       getPoint(pi,checkpoi,i);
                  	                         });
                  	        map[i].addOverlay(marker);    //添加标注
                  	        getPoint(pi,checkpoi,i);
                    });
                  ac[i] = new BMap.Autocomplete(    //建立一个自动完成的对象
                  	{"input" : "address_1_"+i+""
                  	,"location" : map[i]
                  	,'onSearchComplete':function(e){
                		clearTimeout(timer[i]);
                		timer[i]=setTimeout(function(){
                    	if(e.getNumPois()==0){
                    	getPointByAddress(e.keyword,checkpoi,i);
                    	}
                    	else
                    	{
                    			var _value=e.getPoi(0);
                    			myValue[i] = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                            	$("#searchResultPanel"+i).html("onconfirm<br />index = " + 0 + "<br />myValue = " + myValue[i]);	
                            	setPlace(i);

                    	}
                    	}, 3000 );
                    }
                  });

                  ac[i].addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件 
       
                  var _value = e.item.value;

                  	myValue[i] = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                  	 console.log('i',i);
        		$("#searchResultPanel_"+i).html("onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue[i]);	

                  setPlace(i);

                  });	
                  
                  
                  if(poi&&poi.address)
                      ac[i].setInputValue(poi.address);//是否开始拖拽地址跟随
                  if(poi&&poi.location){
                	  
                   var point=new BMap.Point(poi.location.lng,poi.location.lat)
             
          	       map[i].clearOverlays();    //清除地图上所有覆盖物
          	       map[i].centerAndZoom(poi.location,12);
            	  console.log(i,'poi',poi,'point',poi.location);
            	  
          		  var marker=new BMap.Marker(point);
          		   marker.enableDragging();
          		   marker.addEventListener('dragend', function(){
          			   var pi={};
          			    pi.location=marker.point;
          			    getPoint(pi,checkpoi,i);
          		   });
          		  map[i].addOverlay(marker);    //添加标注
          		  checkpoi(poi.address,poi,'',i);
                  }
}

function setPlace(i){

	  map[i].clearOverlays();    //清除地图上所有覆盖物

	function myFun(e){
		var poi = local.getResults().getPoi(0);    //获取第一个智能搜索的结果
		poi.location=poi.point;
             console.log(i,'setPlace',poi);
		map[i].centerAndZoom(poi.point, 14);
		
		var marker=new BMap.Marker(poi.point);
		   marker.enableDragging();
		   
		   marker.addEventListener('dragend', function(){
			   var  pi={};
			    pi.location=marker.point;
			    getPoint(pi,checkpoi);
		   });
		   map[i].addOverlay(marker);    //添加标注
		   checkpoi(poi.address,poi,'',i);
		
	}
	var local = new BMap.LocalSearch(map[i], { //智能搜索
	  onSearchComplete:myFun,
	  onResultsHtmlSet:function (e){
			 //$('#shipping_city'+i).val('');
			 $('#shipping_code_'+i).val('');
			 $('#shipping_data_'+i).val('');
		}
	});
	local.search(myValue[i]);
}

function clearShippingData(i){

	 $('#shipping_code_'+i).val('');
	 $('#shipping_data_'+i).val('');
	 $('#address_poi_'+i).val('');
	 $("#msg_shipping_address_"+i).html('');
	 map[i].clearOverlays();    //清除地图上所有覆盖物
}
function getPoint(poi,callback,i)
{   
	//console.log(i,'getPoint',poi);

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
   		 console.log(i,'getPoint',poi);
   		 var point=new BMap.Point(poi.location.lng,poi.location.lat);
   	      map[i].centerAndZoom(point, 14);
   	   //if(!$('#'+ac.nc.input).val())
          ac[i].setInputValue(poi.formatted_address);//是否开始拖拽地址跟随
         if(callback)callback(poi.formatted_address,poi,'',i);
        }
     });
     

}


function getPointByAddress(address,callback,i)
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

   		 var point=new BMap.Point(poi.location.lng,poi.location.lat);

   		 map[i].clearOverlays();    //清除地图上所有覆盖物
 	    var marker=new BMap.Marker(point);
 		   var  pi={};
 		    pi.location=marker.point;  
 	       marker.enableDragging();
 	       marker.addEventListener('dragend', function(){
 	    	  var _m=this;
	    	   map.clearOverlays();    //清除地图上所有覆盖物
	    	   map.addOverlay(_m);    //添加标注
 	    	  var  pi={};
 			    pi.location=marker.point;
 		   getPoint(pi,checkpoi,i);
 	      });
 	     map[i].addOverlay(marker);    //添加标注
   		 
   		 
   	      map[i].centerAndZoom(point,map[i].getZoom());//,14
   	       //if(!$('#'+ac.nc.input).val())
          ac[i].setInputValue(address);//是否开始拖拽地址跟随
         if(callback)callback(address,poi,'',i);
     
        }
   	else
   		{
   		    // clearShippingData();
		    $("#msg_shipping_address"+i).html('地址不准确请重新输入');
	        $("#msg_shipping_address"+i).css('color','red');
   		}
     });
     

}


function checkpoi(address,poi,callback,i){
	
	//console.log('checkpoi',address,poi,callback,i);
	
	
if(!poi){
	$("#msg_shipping_address_"+i).html('位置查询失败，请修改地址!');
	return;	
}/*
else if(poi.confidence && poi.confidence < 60) {
	$("#msg_shipping_address_"+i).html('请提供更详细小区地址!');
	return;	
}*/




var url='/apiv3/index.php?route=lbs/shipping/hgetByLng&callback=?';

//console.log(url);

$.getJSON(url,
		 poi.location,
		 function(e){checkaddress_callback(e,callback,poi,i);});  

}

function checkaddress_callback(e,callback,poi,i){
//console.log(i,'checkaddress_callback',e);

	 if(e.status==1)
		 {
		  // _.toast('地址有效',2000);
		  // $("#address_1").css('color','green');	
		 //console.log(i,e.data.city+e.data.region_name);
		    $("#msg_shipping_address_"+i).html(e.message+'['+e.data.shippingcode+e.data.city+e.data.region_name+']');
		    $("#msg_shipping_address_"+i).css('color','green');
		    //$('#shipping_city'+i).val(e.data.city);
		    $('#shipping_code_'+i).val(e.data.shippingcode);
		    $('#shipping_data_'+i).val(e.data.region_name);

	        if(poi)
	   	     $("#address_1_poi_"+i).val(poi.location.lng+' '+poi.location.lat);
	       
	       // console.log("#address_1_poi_"+i,'checkaddress_callback', $("#address_1_poi_"+i).val(poi.location.lng+' '+poi.location.lat));
	        
	        
	       if(typeof(callback)=='function')
	    	   {
	    	   callback(e);
	    	   }
		    
		 }
	 else
		 { 

		    $("#msg_shipping_address_"+i).html(e.message);
	        $("#msg_shipping_address_"+i).css('color','red');
		 }

}
</script>
<script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {	
	html  = '<div id="tab-address-' + address_row + '" class="vtabs-content" style="display: none;">';
	html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';
	html += '  <table class="form">'; 
	html += '    <tr>';
    html += '	   <td><?php echo $entry_firstname; ?></td>';
    html += '	   <td><input type="text" name="address[' + address_row + '][firstname]" value="" /></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '	   <td><?php echo $entry_lastname; ?></td>';
    html += '	   <td><input type="text" name="address[' + address_row + '][lastname]" value="" /></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><?php echo $entry_zone; ?></td>';
    html += '      <td><select name="address[' + address_row + '][zone_id]" onchange="$(\'select[name=\\\'address[' + address_row + '][city_id]\\\']\').load(\'index.php?route=common/localisation/city&token=<?php echo $token; ?>&zone_id=\' + this.value );"><option value="false"><?php echo $this->language->get('text_none'); ?></option></select></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_city; ?></td>';
    html += '      <td><select name="address[' + address_row + '][city_id]" ><option value="false"><?php echo $this->language->get('text_none'); ?></option></select></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '    <tr>';
    html += '      <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>';
    html += '      <td>\
    <input type="text" id="address_1_' + address_row + '" class="" name="address[' + address_row + '][address_1]" value="" />\
    <span id="msg_shipping_address_' + address_row + '" class="error"></span>\
      <div id="searchResultPanel_' + address_row + '" style="display:none;"></div>\
      <input type="hidden" id="address_1_poi_' + address_row + '" name="address[' + address_row + '][address_1_poi]" value="" />\
     <input type="hidden" id="shipping_code_' + address_row + '" name="address[' + address_row + '][shipping_code]" value="" />\
     <input type="hidden" id="shipping_data_' + address_row + '" name="address[' + address_row + '][shipping_data]" value="" />\
     <div id="l_map_' + address_row + '" class="l-map" style="width: 250px;height: 250px;float: left;margin-left: 4px;overflow: hidden; position: relative;text-align: left; background-color: rgb(243, 241, 236);">\
</div>\
    </td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><?php echo $entry_address_2; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][address_2]" value="" /></td>';
    html += '    </tr>';
    html += '    <tr>';
    html += '      <td><?php echo $entry_postcode; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][postcode]" value="" /></td>';
    html += '    </tr>';
  	 html += '    <tr>';
    html += '      <td><?php echo $entry_mobile; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][mobile]" value="" /></td>';
    html += '    </tr>';
     html += '    <tr>';
    html += '      <td><?php echo $entry_phone; ?></td>';
    html += '      <td><input type="text" name="address[' + address_row + '][phone]" value="" /></td>';
    html += '    </tr>';
	html += '    <tr>';
    html += '      <td><?php echo $entry_default; ?></td>';
    html += '      <td><input type="radio" name="address[' + address_row + '][default]" value="1" /></td>';
    html += '    </tr>';
    html += '  </table>';
    html += '</div>';
	
	$('#tab-general').append(html);
	
	$('#address-add').before('<a href="#tab-address-' + address_row + '" id="address-' + address_row + '"><?php echo $tab_address; ?> ' + address_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address-' + address_row + '\').remove(); $(\'#tab-address-' + address_row + '\').remove(); return false;" /></a>');
		 
	$('.vtabs a').tabs();
	
	$('#address-' + address_row).trigger('click');
	
	$('select[name=\'address[' + address_row + '][zone_id]\']').load('index.php?route=common/localisation/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>');
	
	initMapAddress(address_row);
	console.log(address_row);
	address_row++;
	
	
}
//--></script> 
<script type="text/javascript"><!--
$('#transaction .pagination a').live('click', function() {
	$('#transaction').load(this.href);
	
	return false;
});			

$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

function addTransaction() {
	
	if(confirm('金额：'+ encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()) + '元\n说明：' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()))){	
    	$.ajax({
    		type: 'POST',
    		url: 'index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
    		dataType: 'html',
    		data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
    		beforeSend: function() {
    			$('.success, .warning').remove();
    			$('#button-transaction').attr('disabled', true);
    			$('#transaction').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
    		},
    		complete: function() {
    			$('#button-transaction').attr('disabled', false);
    			$('.attention').remove();
    		},
    		success: function(html) {
    			$('#transaction').html(html);
    			
    			$('#tab-transaction input[name=\'amount\']').val('');
    			$('#tab-transaction input[name=\'description\']').val('');
    		}
    	});
	}
}
//--></script> 
<script type="text/javascript"><!--
$('#reward .pagination a').live('click', function() {
	$('#reward').load(this.href);
	
	return false;
});			

$('#reward').load('index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

function addRewardPoints() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-reward').attr('disabled', true);
			$('#reward').before('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-reward').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(html) {
			$('#reward').html(html);
								
			$('#tab-reward input[name=\'points\']').val('');
			$('#tab-reward input[name=\'description\']').val('');
		}
	});
}
//--></script> 
<script type="text/javascript"><!--
$('.htabs a').tabs();
$('.vtabs a').tabs();
//--></script>
<script type="text/javascript"><!--
$('form input[type=radio]').live('click', function () {
	$('form input[type=radio]').attr('checked', false);
	$(this).attr('checked', true);
});
//--></script> 
