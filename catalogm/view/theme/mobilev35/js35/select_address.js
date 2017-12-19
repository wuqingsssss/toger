/**
 * @file ${FILE_NAME}. Created by PhpStorm.
 * @desc ${FILE_NAME}.
 *
 * @author yangjunbao
 * @since 15/10/29 上午10:31
 * @version 1.0.0
 */
var pages,map,timer, myValue='',myPoint='北京',geolocationControl,overlay;
$(function () {
	
	pages=new PageTransitions('#pt-main');

	// 百度地图API功能
	/* 百度地图支持开始*/
	    map = new BMap.Map("l-map");
	        map.centerAndZoom("北京",18);                   // 初始化地图,设置城市和地图级别。
	        console.log('0','北京');
	        map.addControl(new BMap.NavigationControl());       // 添加比例尺控件
	        
	        var marker={};
	        var size=map.getSize();

	   	 $("#l-map").append('<div style="position:absolute;top:'+(size.height/2-53)+'px;left:'+(size.width/2-15)+'px;z-index:100000000;"><img src="catalogm/view/theme/mobilev35/images/lbs.png" class="map-indicator"/></div>');
	 	
	   	 /*
	   	map.addEventListener("click",function(e){
	   		var pi={};
				    pi.location=e.point;
			  
		   getPoint(pi,checkpoi);
	  });*/
		    map.addEventListener("dragend",function(e){
		    	var point=(map.getCenter());  
		    	var pi={};
		       pi.location=point;
		       
			   getPoint(pi);//,checkpoi
		   });

	    
		ac = new BMap.Autocomplete(    //建立一个自动完成的对象
			{"input" : "inputkey"
			,"location" : map
		    ,'onSearchComplete':function(e){
		    	myPoint='北京';
		    	clearShippingData();
		    	clearTimeout(timer);
		    	//console.log('myValue',e.keyword,timer);
		    	
		    	 ac.hide();
		    	myValue = e.keyword;
            	console.log('myValue',myValue);
            	//ac.setInputValue(myValue);
            	setPlace('renderList');
		    	
		    /*
		    	if(e.getNumPois()==0){
		   			
		    		timer=setTimeout(function(){
		 
		    	//getPointByAddress(e.keyword,checkpoi);
		            	myValue = e.keyword;
		            	//console.log('myValue',myValue);
		            	//ac.setInputValue(myValue);
		            	setPlace('renderList');
		    		}, 2000 );
		    	}
		    	  else
		    	{
		    		  
		    		  ac.hide();
		    		  timer=setTimeout(function(){
		    			var _value=e.getPoi(0);
		            	//myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		            	myValue=e.keyword;
		            	ac.setInputValue(myValue);
		            	setPlace('renderList');
		    	      }, 2000 );
		    		
		    		//clearTimeout(timer);
		    		//renderAcList();
		    	}
		    	*/
		    }
			});
		
		

		ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
		var _value = e.item.value;
			myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			setPlace();
		});	

		geolocationControl = new BMap.GeolocationControl();
		overlay=false;	
		 //console.log('geolocationControl', geolocationControl);

		 
	     geolocationControl.addEventListener("locationSuccess", function(poi) {
	/*
		var mk = new BMap.Marker(poi.point);
		mk.enableDragging();
		map.addOverlay(mk);
		map.panTo(poi.point);
		poi.location = poi.point;
		console.log('autoGeo', poi);*/
		if(overlay)overlay.destroy();
		var pi = {};
		pi.location = poi.point;
		map.centerAndZoom(poi.point,map.getZoom());
		console.log('1',poi.point);
		getPoint(pi);//, checkpoi

	});
	geolocationControl.addEventListener("locationError", function(e) {
		// 定位失败事件
		if(overlay)overlay.destroy();
		_.toast('定位失败' + e.message, 2000);
	});
	map.addControl(geolocationControl);
		
	
	$("#inputkey").bind('copy cut paste',function(){
		
		ac.search($("#inputkey").val());
		
	}).bind('blur',function(){
		
		var $poi=false;
		if($("#address_1_poi").val())
	    	$poi = JSON.parse($("#address_1_poi").val());
		    if($poi.confidence && $poi.confidence < 70) {
				_.confirm ("地址似乎不太准确,您可以拖动地图修改"
						, function(){
							clearShippingData();
						}
						, function(){ 
							return;
							}, '我要修改', '继续使用');
				return;
			}
		 
	});
	$("#address_1").bind('click',function(){
		
		if(!$("#address_1").val()){
			autoGeo();//开始自动定位
		}else
			{
		setPlace('renderList',true);
		}
		pages.nextPage('left',3);
//		$("#header .pull-left a").attr('href',"javascript:pages.nextPage('right',2);");
	});
	
	
	
    var $mAddresses = $('#m-addresses'),
        $mNewAddress = $('#add-new-address');


    $mAddresses.on('click', '.radio', function () {
        $mAddresses.find('.radio.checked').removeClass('checked');
        $(this).addClass('checked');
        
        // 切换地址选择后，自己做选择跳转
        setTimeout("selectAddress()",1000);
        
    }).on('click', '.modify-address', function () {
    	
    	var $addresscontent=$(this).parent().parent();
    	
    	
    	$("#new_address_form input[name='address_id']").val($addresscontent.attr('data-id'));
    	$("#new_address_form input[name='firstname']").val($addresscontent.find('.content .name').html());
    	$("#new_address_form input[name='mobile']").val($addresscontent.find('.content .mobile').html());
    	$("#new_address_form input[name='address_1']").val($addresscontent.find('.content .address_1').html());
    	$("#new_address_form input[name='address_2']").val($addresscontent.find('.content .address_2').html());
    	
    	var poi={};
    	poi.address=$addresscontent.find('.content .address_1').html();
	
    	ac.setInputValue(poi.address);
    	myValue=poi.address;

    	if($addresscontent.find('.poi').html()){

        	$("#new_address_form input[name='address_1_poi']").val('{"location":'+$addresscontent.find('.poi').html()+'}');
        	if($addresscontent.find('.shipping_code'))
        		$("#new_address_form input[name='shipping_code']").val($addresscontent.find('.shipping_code').html());
        	if($addresscontent.find('.shipping_data'))
        		$("#new_address_form input[name='shipping_data']").val($addresscontent.find('.shipping_data').html());
    		
        	
    	poi.location = JSON.parse($addresscontent.find('.poi').html());
    
    	var point=new BMap.Point(poi.location.lng,poi.location.lat);
    	
	    map.clearOverlays();    //清除地图上所有覆盖物
	    map.centerAndZoom(point,map.getZoom());
	    console.log('2',point,poi.location);
  	  /*
		var marker=new BMap.Marker(poi.location);
		   marker.enableDragging();
		   marker.addEventListener('dragend', function(){
			   var pi={};
			   pi.location=marker.point;
			    getPoint(pi,checkpoi);
		   });
		  map.addOverlay(marker);    //添加标注*/
		 // checkpoi(poi.address,poi);
    	}
   
    	
    	$("#add_address #header .locate").html('修改收货地址');
//    	document.title='修改收货地址';
    	pages.nextPage('left',2);
    	// $("#header .pull-left a").attr('href',"javascript:pages.nextPage('right',1);");
  	       
    }).on('click', '.delete-address', function () {
        var $this = $(this);
        _.confirm('确定删除？', function () {
        	var $address = $this.closest('.address');
        	var id = $address.attr('data-id');
        	removeAddress(id, $address);   	
        });
    });
        
 
    $mNewAddress.on('click', '.btn', function () {
    	$("#new_address_form input[name='address_id']").val(0);
    	$("#new_address_form input[name='firstname']").val('');
    	$("#new_address_form input[name='mobile']").val('');
    	$("#new_address_form input[name='address_1']").val('');
    	$("#new_address_form input[name='address_2']").val('');
    	$("#new_address_form input[name='shipping_data']").val('');
    	
    	$("#add_address #header .locate").html('新增收货地址');	
    	
    	myValue='';
 //   	document.title='新增收货地址';
 //   	$('#address-submit').hide();
    	
        pages.nextPage('left',2);
 //       $("#header .pull-left a").attr('href',"javascript:pages.nextPage('right',1);");
    });
    
    
	$('#map_address .cancel').bind('click', function () {
  //  	$("#header .locate").html('选择收货地址');
    	 pages.nextPage('right',2);
    	// $("#header .pull-left a").attr('href',"javascript:pages.nextPage('right',1);");
  //  	 document.title='选择收货地址';
 //   	 $('#header a.return').attr("href","javascript:_.go();");
 //   	 $("#address-submit").show();
    });

    
    

    
    /*
    $("#address_1").bind("keyup propertychange input",function(){
		
		//AWaitingList('#address_1','#listbox', updateAddress);//,checkaddress
	});
    
 */
    $('#save_cancel').bind('click', function () {
    	//$("#header .locate").html('选择收货地址');
    	//$('#header a.return').attr("href","javascript:_.go();");
    	 pages.nextPage('right',1);
    });
    
    $('#save_address').bind('click', function() {
    	var $poi=false;
    	//console.log("#address_1_poi",$("#address_1_poi").val());
    	if($("#address_1_poi").val())
    		$poi = JSON.parse($("#address_1_poi").val());
    	//console.log('$location',$poi);

    	if($('#shipping_data').val()==''&& $("#address_1").val()){
    		//checkpoi($("#address_1").val(), $poi,addAddress);
    		 _.toast('请修改地址',2000);
    		 pages.nextPage('left',3);
    		 }
    	else{
    		//console.log($poi);
    		if($poi.confidence && $poi.confidence < 70) {
    			_.confirm ("地址似乎不太准确，确认使用吗？", function(){  
	    	        }, function(){
	    	        	 addAddress();
	    	        }, '我要修改', '确认使用');
    		return;
    		}
    		else
	    	{
    			var id = $("#address_id").val();
    			// 删除旧地址
    			if(id)
				{
    				var obj = $("#m-addresses").find(".address[data-id="+id+"]");
    				obj.remove();
    				
				}
    			addAddress();
	    	}
    	}
    		
    });
   
	
});

function updateAddress(name, poinfo){
	$("#address_1_poi").val(JSON.stringify(poinfo));
}
function renderAcList() {
			
			   ac.hide();
			   $('#localsearchresult').html('');
			   
			   var localResult=ac.getResults(),tpl='';
			   
			   for(var i=0;i<localResult.getNumPois();i++)
			   { 
				   var poi=localResult.getPoi(i);
				   tpl='<li>\
					<div class="ofh">\
						<img src="catalogm/view/theme/mobilev35/images/gprs.png" class="fl o_map_gprs"/>'
					   tpl+='<span style="color: #FE912C;" class="onselect hidden">[当前]</span>';
					   tpl+='<div class="fl">{business}</div>\
					</div>\
					<div class="o_map_specific">{province}{city}{district}{street}{streetNumber}</div>\
				</li>'.format(poi);

				   $('#localsearchresult').append(tpl);  

			   }
			   $('#localsearchresult li').bind('click',function(){

				   $('#localsearchresult li .onselect').hide();
				   $(this).find('.onselect').show();
				   
				   var poi=localResult.getPoi($(this).index());
				   myValue= poi.province +  poi.city +  poi.district +  poi.street +  poi.business;
				   setPlace();
			   });
		   }
		
function autoGeo(ip){
		 overlay=_.toast('定位中请稍候···');		
			  // 添加定位控件
	geolocationControl.location();//

	}
	
	 /* 百度地图支持结束*/ 
function setPlace(type,resetmap){
	  //console.log('type',type,'myValue',myValue,'resetmap',resetmap,'myPoint',myPoint);
	    type=eval(type)||myFun;

	    resetmap=resetmap||false;
		map.clearOverlays();    //清除地图上所有覆盖物
		function myFun(){
			var i;
			if(type==myFun)
				{
				var poi = local.getResults()[0].getPoi(0); //获取第一个智能搜索的结果
				}
			else 
				{
				i=$(this).index();
				   $('#localsearchresult li .onselect').hide();
				   $(this).find('.onselect').show();
				 var poi = localResult[i]; //获取选定的搜索结果
				 ac.setInputValue(poi.title+poi.address);
				}


			poi.location=poi.point;
			//if(!resetmap)
				map.centerAndZoom(poi.point,map.getZoom());
			console.log('3',poi.point,resetmap);
			checkpoi(poi.address,poi,function(){pages.nextPage('right',2);});

		}
	    function renderList() {
	    	
			   $('#localsearchresult').html('');

			   var $localResult=local.getResults(),tpl='';
			   
			 //  console.log('renderList',$localResult,$localResult.length);

			   var tpli=0,setfirst=true;
			   
			   for(var j=0;j<$localResult.length;j++){
				   
			    if(tpli>=9)break;
				   //console.log('tpli',tpli);
				   
			   var lR=$localResult[j];
			  // console.log('lR',lR,lR.getCurrentNumPois(),lR.getPoi(0),$localResult,tpli);
			   
			   if(setfirst&&lR.getCurrentNumPois()>0){
				   setfirst=false;
			   var poi=lR.getPoi(0);
			   if(!resetmap){
				   map.centerAndZoom(poi.point,map.getZoom());
			   console.log('4',poi.point,resetmap);
			   }}
			   
			   console.log('uu');
			   for(var i=0;i<lR.getCurrentNumPois();i++)
			   { 
				   
				   
				   
				   poi=lR.getPoi(i);
				  
				   //console.log('poi'+i,poi);
				   
				   if(!poi.address||poi.address.indexOf(";")>-1){continue;}
				   tpl='<li>\
					<div class="ofh">\
						<img src="catalogm/view/theme/mobilev35/images/gprs.png" class="fl o_map_gprs"/>'
					   tpl+='<span style="color: #FE912C;" class="onselect hidden">[当前]</span>';
					   tpl+='<div class="fl">{title}</div>\
					</div>\
					<div class="o_map_specific">{address}</div>\
				</li>'.format(poi);

				   $('#localsearchresult').append(tpl);  
				   localResult.push(poi);
				   tpli++; 
				   if(tpli>9)break;

			   }
			   }
			   
			   //console.log('localResult',localResult);
			   
			   $('#localsearchresult li').bind('click',myFun);
		   }
	    
	    //console.log('type',type,'myValue',myValue);
		var local = new BMap.LocalSearch(map, { //智能搜索
			  onSearchComplete: type,
			  onResultsHtmlSet:clearShippingData
			}),localResult=[];
	    if(myValue||myPoint){
		//local.search(myValue,{forceLocal:true});
	    	
		//ac.setInputValue(myValue);
		local.searchNearby([myValue,'大厦','小区','楼'],myPoint,500);//以mypoint为中心，500米半径 依次搜索 附近的 myvalue 或 大厦 或 小区 或 楼
		
		
		}
		
	
	}
	
	function clearShippingData(){
		 $('#shipping_city').val('');
		 $('#shipping_code').val('');
		 $('#shipping_data').val('');
		 $('#address_1_poi').val('');
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

	    		 var point=new BMap.Point(poi.location.lng,poi.location.lat);
	    	    // map.centerAndZoom(point,map.getZoom());
	    	   //if(!$('#'+ac.nc.input).val())

               /// ac.setInputValue(poi.formatted_address);//是否开始拖拽地址跟随
                
            	myValue = '';//poi.formatted_address
            	//ac.setInputValue(myValue);
            	
            	myPoint=point;
            	setPlace('renderList',true);
                
              if(callback)callback(poi.formatted_address,poi);
             }
	      });
	      

	}
	function getPointByAddress(address,callback)
	{   
		  //	console.log(address);
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

	   		 map.clearOverlays();    //清除地图上所有覆盖物
	
	   	      map.centerAndZoom(point,map.getZoom());//,14
	   	   console.log('5',point);
	   	       //if(!$('#'+ac.nc.input).val())
	          ac.setInputValue(address);//是否开始拖拽地址跟随
	         if(callback)callback(address,poi);
	           
	        }
	   	else
	   		{
	   		     clearShippingData();
	   			_.alert('地址不准确请重新输入!');
	   		}
	     });
	     

	}

function checkpoi(address,poi,callback){
//console.log(address,poi);
	if(!poi){
		_.alert('位置查询失败，请修改地址!');
		return;	
	}
	/*else if(poi.confidence && poi.confidence < 70) {
		//_.alert('您的地址似乎不太准确请拖动地图确认!');
		_.confirm ("地址似乎不太准确,您可以拖动地图修改"
				, function(){}
				, function(){
					var url='apiv3/index.php?route=lbs/shipping/hgetByLng&callback=?';
					 $.getJSON(url,
							 poi.location,
							 function(e){checkaddress_callback(e,callback,poi);});  
					return;
					}, '我要修改', '继续使用');
		return;
	}
*/
	var url='apiv3/index.php?route=lbs/shipping/hgetByLng&callback=?';

	var toast=_.toast('地址验证中请稍候');
	
	 $.getJSON(url,
			 poi.location,
			 function(e){checkaddress_callback(e,callback,poi,toast);});  
	
}

function checkaddress_callback(e,callback,poi,toast){
if(toast)toast.destroy();

		 if(e.status==1)
			 {
			// _.toast('地址有效',2000);
			  // $("#address_1").css('color','green');	
			 console.log(e.data.city+e.data.region_name);
			 //_.toast(e.data.region_name,2000);
			 
			   $('#shipping_city').val(e.data.city);
		       $('#shipping_code').val(e.data.shippingcode);
		       $('#shipping_data').val(e.data.region_name);
		       //console.log(poi);
		       if(poi)
		   	   $("#address_1_poi").val(JSON.stringify(poi));
		       
			   $("#address_1").val($("#inputkey").val());

		       //console.log($("#inputkey").val());
		       
		       if(typeof(callback)=='function')
		    	   {
		    	   callback(e);
		    	   }
			    
			 }
		 else
			 {  $("#address_1").css('color','black');

			     _.toast(e.message,5000);
			     clearShippingData();
			 }

}



/**
 * 删除地址
 * @param id
 * @param obj
 */
function removeAddress(id, obj){
	$.ajax({
		url: 'index.php?route=checkout/shipping_method/delete',
		type: 'post',
		data: "data-id=" + id.toString(),
		dataType: 'json',
		beforeSend: function() {
		},
		complete: function() {
		},
		success: function(json) {
			if (json['error']) {
				if (json['error']['warning']) {
					   _.toast(json['error']['warning'],5000);
				}
			}
			else{
				obj.remove();
				
				var address = $("#m-addresses").find('.address');
				if(address.length <= 0){
					$('#address-empty').show();
				}
			}
		}
	});
}

/**
 * 增加地址
 * @param id
 * @param obj
 */
function addAddress(){
	
	if($('#shipping_data').val()==''){	
		  _.alert('配送地址无效，请检查您的地址',function(){
			 // $("#address_1").val('');
			  });
		$('#shipping_data').focus();
		return false;
	}
	//console.log($('#new_address_form').serialize());
	//console.log('#shipping_data',$('#shipping_data').val());
	
	$.ajax({
		url: 'index.php?route=checkout/shipping_method/insert',
		type: 'post',
		data: $('#new_address_form').serialize(),
		dataType: 'json',		
		beforeSend: function() {
			$('#save_address').attr('disabled', true);
			$('#save_address').after('<span class="waiting"><img class="icon-spin animate-spin" src="catalogm/view/theme/mobilev35/images/waiting.png"/></span>');
		},
		complete: function() {
			$('#save_address').attr('disabled', false);
			$('.waiting').remove();
		},
		success: function(json) {
			//console.log('addAddress',json);
			if (json['error']) {
				if (json['error']['warning']) {
					 _.toast(json['error']['warning'],5000);
				}
			}
			else{				
				if(json['output']!=null && json['output']!=''){
					$("#m-addresses .radio").removeClass('checked');
					$('#m-addresses').append(json['output']);
					
					$('#address-empty').hide();
				}
				
				
		        pages.nextPage('right',1);
		       //window.location.href='index.php?route=checkout/shipping_method&address_id='+json['address']['address_id'];
			}
		}
	});
}

function selectAddress(){
    var $selected = $("#m-addresses").find('.radio.checked');
    if($selected.length < 1){
        _.alert("请选择地址");
    }
    else{
    	var $address = $($selected[0]).closest('.address');
    	var id = $address.attr('data-id');
    	
    	window.location.href='index.php?route=checkout/shipping_method&address_id='+id;
    }
}
