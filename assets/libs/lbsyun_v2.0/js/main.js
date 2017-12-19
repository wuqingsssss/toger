/*var map = new BMap.Map("map");                             // 创建Map实例
var myGeo = new BMap.Geocoder();   
!function(){ //初始化地图模块相关代码
   // map.enableScrollWheelZoom();                           // 启用滚轮放大缩小
   //map.enableContinuousZoom();                             // 启用地图惯性拖拽，默认禁用
   // map.enableInertialDragging();                           // 启用连续缩放效果，默认禁用。 
   //map.addControl(new BMap.NavigationControl());           // 添加平移缩放控件
   map.addControl(new BMap.ScaleControl());     

    // 添加比例尺控件
    // map.addControl(new BMap.OverviewMapControl());          // 添加缩略地图控件
    // map.addControl(new BMap.MapTypeControl());              // 添加地图类型控件
    map.addControl(new BMap.GeolocationControl());    //定位控件
    map.centerAndZoom(new BMap.Point(116.404, 39.915), 11); // 初始化地图,设置中心点坐标和地图级别
    map.setCurrentCity("北京");                             //由于有3D图，需要设置城市哦
}();
*/
/**
 * 一些常用的方法
 */
var Util = {
    /**
     * 根据类型的id获取相应的名称
     * @param Number typeid
     * @return String 名称
     */
    getLeaseNameByType: function(type) {
        var lease = {
            '1' : "自提点",
            '2' : "配送站",
        };
        return lease[type];
    },
    /**
     * 设置Map容器的高度
     */
    setMapHeight: function() {
        var mapBoxHeight = $(window).height()  - $('#pageHeader').height() - $('#pageMiddle').height() - 38;
        $('#mapBox').css({height: mapBoxHeight + 'px'});
    },
    keyword:'',
    tag:{},
    geoSearch:{
     'city':"北京市",
     'url ': "",
     'data':{},
     'point':{},
     'group':'[0]'
    },
    config:{
    	 'geotable_id': '',
         'ak'         : '' 
    },
    language:{
    	'error_no_zhaipei':'抱歉，菜君暂时还没解放这片疆土。你听，冲锋号已吹响，胜利就在前方！',
    	'error_no_points':'',
    	'error_no_searchres':'没有找到您输入的地址，请重新输入。',
    	'please_select_points':'请点选择与您最近的地点查看附近的菜小君',
    	
    	'geo_loading':'定位中请稍后..'
    }
};

/**
 * 根据类型的id获取相应的名称
 * @param IP ip
 * @return Point 定位点信息
 */
	function autoGeoIP(ip){
	     var url = "http://api.map.baidu.com/location/ip?callback=?";
    $.getJSON(url, {
        'ip'  : ip, //检索关键字
        'coor' : 'bd09ll', 
        'ak'         : Util.config.ak  //用户ak
    },function(e) {
    	console.log('e');	console.log(e);	
    	if(e.status==0){
    		$('#keyword').val(e.content.address);

    		var poi={location:{'lng':e.content.point.x,'lat':e.content.point.y}};
    	//autopointAction(poi);
    	//searchAllAction(e.content.address);
    	}
    });
		
	}
	/**
	 * 自动定位到当前地点兼容pcip定位
	 * @param IP ip 可为空
	 * @return Point 定位点信息
	 */
	function autoGeo(ip){
		var lbsGeo = document.createElement('lbs-geo');

		 $('#keyword').val(Util.language.geo_loading);
		
		//监听定位失败事件 geofail	
		lbsGeo.addEventListener("geofail",function(evt){
			if(ip)autoGeoIP(ip);//gps失败采用ip定位
		});
		//监听定位成功事件 geosuccess
		lbsGeo.addEventListener("geosuccess",function(evt){ 		
			console.log('evt.detail');console.log(evt.detail);
			var address = evt.detail.address;
			if($('#keyword').val()==Util.language.geo_loading){
			$('#keyword').val('[定位]'+address);

			var poi={'name':'','address':evt.detail.address,'location':evt.detail.coords};
			
			autopointAction(poi);
			}
		});
		}
	/**
	 * 自提点筛选选项渲染
	 * @param filterData 查询参数
	 * @return id 显示位置id
	 */
	function iTofilterBox(filterData,id){
	  for (var i in filterData) { //条件筛选的各个项
	    	
	        var item = filterData[i],
	            data = item.data,
	            dl = $('<dl id="' + i + '" class="dl-horizontal" value="' + i +'"><dt>' + item.name + '：</dt></dl>'),
	            ul = $('<ul class="inline"></ul>'),
	            dd = $('<dd></dd>');

	        $('#selectedValue div[type$=' + i + ']').remove();
	        
	        for(var j in data) { //各个项对应的各详细选项	  	

	            var subData = data[j];

	            $('<li><a value = "' + subData.value + '" child = \'' + (JSON.stringify(subData.child)) + '\'  >' + subData.name +'</a></li>').appendTo(ul);
	            
	        }
	        ul.appendTo(dd);
	
	        if(($('#'+i).length>0))
	       { 
	        	
	        	$('#'+i).detach();
	       }
	       
	        	dd.appendTo(dl);
	        	dl.appendTo($(id));
	        	
	      $('#'+i+' li a').bind('click',onselect);
		}
	  
	
}
/**
 * 自提点筛选列表渲染
 */
function onselect(){
    var type = $(this).parents('dl').attr('value');
    $('#' + type + " li a").removeClass('activate');
    if (!$(this).hasClass('activate')) { //点击的不是当前的选项
        $(this).addClass('activate');
   
        $('#selectedValue div[type$=' + type + ']').remove(); //当前条件之前选择过的条件删除
        
        
        if($(this).attr('child')&&$(this).attr('child')!='undefined')
        	{
        	var child=eval('('+$(this).attr('child')+')');
        	iTofilterBox(child,'#filterBox');
        	}
        
        var item = $('<div class="span1" value="' + $(this).attr('value') + '" type="' + type + '"><span>' + $(this).html() + '</span></div>');
        //添加删除按钮
        var deleteBtn = $('<i class="icon-remove"></i>').click(function(){
            $(this).parent().remove();
            $('#' + type + " li a").removeClass('activate');
          
            searchAction();
        });
        deleteBtn.appendTo(item); 
      
        item.appendTo('#selectedValue'); //添加当前的筛选条件 
        
        $('#selectedValue').hide();
        searchAction(); 
    }
}
/**
 * 通过地址查询自提点
 * @param address 要查询的地址
 * @return page 显示页码
 */
function setPointByAddress(address,page){
	  page = page || 0;
	// 将地址解析结果显示在地图上，并调整地图视野    
	myGeo.getPoint(address, function(point){      
	          if (point) {      
	             Util.geoSearch.url = "http://api.map.baidu.com/geosearch/v3/nearby?callback=?";
	             Util.geoSearch.data={
	                     'location'   : point.lng+','+point.lat, //检索关键字
	                     'radius':10000,
	                     'sortby':'distance:1',
	                     'page_index' : page,  //页码
	                     'geotable_id':Util.config.geotable_id,
	                     'ak'         :Util.config.ak  //用户ak
	                 };
	             console.log(Util);
	             geosearchAction();   
	             
	          }      
	      }, Util.geoSearch.city);
	
}

	function getPoi(address,callback,callback2)
	{   
		  //callback($(keyword).val());
		
		console.log(address);
		
		  var url = "http://api.map.baidu.com/geocoder/v2/?callback=?";
		     // url = "http://api.map.baidu.com/place/v2/suggestion?callback=?";
	      $.getJSON(url, {
	          'address'          :address, //检索关键字
	          'output'     :'json',
	          'region'     : '131',  //北京的城市id
	          'scope'      : '2',  //显示详细信息
	          'ak'         : Util.config.ak  //用户ak
	      },function(e) {
	    	console.log('getPoi');	
	      	console.log(e);	
	     // 	if(e.status=='0'){   		
	      	callback&&callback(address,e.result,callback2);              
	     // 	 };
	      });


	}

function AWaitingList(keyword,listbox,callback,tag)
{   
	//console.log(Util.keyword);
	if(!tag&&(!Util.tag.keyword)){
	
		Util.tag.keyword=keyword;

	    setInterval("AWaitingList('"+keyword+"','"+listbox+"',"+callback+",true)",1000);
	}
	
	if(Util.keyword==$(keyword).val().replace(/(^\s*)|(\s*$)/g, "")||!$(keyword).val()){
		return;
	}
	
	Util.keyword=$(keyword).val().replace(/(^\s*)|(\s*$)/g, "");
	
	getPoi(Util.keyword,callback);

	  $(keyword).parent().css('position','relative');
	 	//if(typeof(callback)=='function')
	 // callback(Util.keyword);
	  
	  //callback($(keyword).val());
	  var url = "http://api.map.baidu.com/place/v2/search?callback=?";
	     // url = "http://api.map.baidu.com/place/v2/suggestion?callback=?";
      $.getJSON(url, {
          'q'          :Util.keyword, //检索关键字
          'page_index' : 0,  //页码
          'page_size' : 5,  //页码
          'output'     :'json',
          'region'     : '131',  //北京的城市id
          'scope'      : '2',  //显示详细信息
          'ak'         : Util.config.ak  //用户ak
      },function(e) {
      	console.log(e);	
      	if(e.status=='0'){   		
			if(($(listbox).length>0))
			{ 
				$(listbox).html('');
			}
			var ul= $('<ul></ul>'),res=false,results={};
			if(e.result)results=e.result;
			if(e.results)results=e.results;
			if(results.length>1){
				$.each(results, function(i, item){
					if(!item.address)item.address='';
					$('<li poi=\''+JSON.stringify(item)+'\' home='+res+' >' 
							+ '<span class="ad_name">' + item.name.substring(0, 50) + '</span>' 
							+ '<span class="ad_detail">' + item.address + '</span>' + '</li>').appendTo(ul);
				});
		      		   
			   $(listbox).append(ul);
			   $(listbox).show();
			   $(document).bind("click",function(e){ 
				   var target = $(e.target); 
				   if(target.closest(listbox).length == 0){ 
				   $(listbox).hide(); 
				   } 
				   }) 
			   $(listbox+' li').bind('click',function(){
		  			if($(this).attr('poi'))
		           	{	 
		      			$(listbox).hide();
		               	var poi = $(this).attr('poi');
		               	var $name = $(this).find('.ad_name');
		               	$(keyword).val($name.html());
		                
		               	Util.keyword= $name.html();
		               	
		               	if(typeof(callback)=='function')
		               	callback($name.html(),JSON.parse(poi));
		           	}
			   });
	      	}
		}
		else
		{
		     $(listbox).html('');
		  //		console.log(e.message);
		}
	});

}

/**
 * 通过用户输入查询地址并根据地址返回附近的自提点
 * @param address 要查询的地址
 * @return page 显示页码
 */
function searchAllAction(keyword, page) {
	keyword = keyword || '';
        page = page || 0;
        var filter = []; //过滤条件
        
        $('#selectedValue').html('');
        $("#filterBox li a").removeClass('activate');
        
         var url = "http://api.map.baidu.com/place/v2/search?callback=?";
        $.getJSON(url, {
            'q'          : keyword, //检索关键字
            'page_index' : 0,  //页码
            'page_size' : 5,  //页码
            'output'     :'json',
            'region'     : '131',  //北京的城市id
            'scope'      : '2',  //显示详细信息
            'ak'         : Util.config.ak  //用户ak
        },function(e) {
        	//console.log(e);	
        	if(e.status=='0'){
        		
           		 if(($('#reslist').length>0))
         	       { 
         	        	$('#reslist').html('');
         	       }
 
        		   var div =$('<div id="reslist" class="pull-left"></div>');
        		   var ul= $('<ul></ul>');
                   var res=false;
        		   if(e.results.length>1){
        			$('#listBoby').html('<p style="border-top:1px solid #DDDDDD;padding-top:10px;text-align:center;text-align:center;font-size:13px;" class="text-warning">'+Util.language.please_select_points+'</p>');
        		 $.each(e.results, function(i, item){
        			 
        	          $('<li poi=\''+JSON.stringify(item)+'\' home='+res+' >' +item.name  + item.address + '</li>').appendTo(ul);
        	          
        	        });
        		   }
        		   else if(e.results.length==1)
        		{
        			   searchAction(e.results[0].name);
        	    }
        		   else
        	     {
        	    	 $('#listBoby').html('<p style="border-top:1px solid #DDDDDD;padding-top:10px;text-align:center;text-align:center;font-size:13px;" class="text-warning">'+Util.language.error_no_searchres+'</p>');
        	     }
        		   div.appendTo("#pageHeader .container");
        		   
        		   $('#reslist').append(ul);
        		   $('#reslist').show();
        		
        		   $("#filterBox").hide();
        		   
        		   $('#reslist li').bind('click',function(){
        			 if($(this).attr('poi'))
                 	{	 
        			$('#reslist').hide();
                 	var poi=eval('('+$(this).attr('poi')+')');
                 	autopointAction(poi);       	
                 	$('#keyword').val($(this).html());
                 	}
        		 }
        		 );
        		 
            }
        	else
        	{
        	 $('#reslist').html('');
			 $("#filterBox").show();
        		console.log(e.message);
            }
        });
        
        
    }

/**
 * 根据用户点选的gps搜索附件的点
 * @param poi 百度点对象
 */

   function autopointAction(poi) {
        page = page || 0;
        var point=new BMap.Point(poi.location.lng,poi.location.lat)

      /*var res=checkAddressArea(point);
  
        if(res)
		 { 
        	var item=$.extend(poi, res);
            console.log(item);
        	var item2={'title':'[宅配]'+item.city+item.region_name,'city':item.city,'address':item.address,'name':item.name,'region_name':item.region_name};
        	var jsonitem=JSON.stringify(item2).replace(/\s+/g,"");
        
		 var tpl='<li class="ztd act">\
		         <div class="ztd-title">当菜君来敲门，方便新鲜送到家</div>\
		         <div class="ztd-info"> \
		         <span>地址：'+item.city+item.region_name+item.address+'</span>\
		         <span>电话：400-882-1551</span>\
		         <span>送菜时间：16:30-20:30<input type="button" onclick=setzoon(\'\',\''+jsonitem+'\') class="lbs-body-sb" style="width:84px;" value="确定"></span>\
		         </div>\
		   </li>';
		 $('#listhome').html(tpl);
		 }
	 else
		 {
		 var tpl='<p style="border-top:1px solid #DDDDDD;padding-top:10px;text-align:center;text-align:center;font-size:13px;" class="text-warning">'+Util.language.error_no_zhaipei+'</p>';
			 $('#listhome').html(tpl);
		 }
        */
    
        Util.geoSearch.url = "http://api.map.baidu.com/geosearch/v3/nearby?callback=?";
        Util.geoSearch.data={
                'location'   : poi.location.lng+','+poi.location.lat, //检索关键字
                'radius':10000,
                'sortby':'distance:1',
                'page_index' : page,  //页码
                'geotable_id':Util.config.geotable_id,
                'ak'         :Util.config.ak  //用户ak
            };
        geosearchAction(); 
    }
   
   /**
    * 区域检索 暂未使用
    */
   function autoboundsAction() {
	   
	  var bounds=map.getBounds();
	  
	   
       page = page || 0;

       Util.geoSearch.url = "http://api.map.baidu.com/geosearch/v3/bound?callback=?";
       Util.geoSearch.data={
        	   'q':'',
        	   'location' : '116.373961,40.026476', //检索关键字
               'bounds'   : bounds.xc+','+bounds.wc+';'+bounds.uc+','+bounds.tc, //检索关键字
               'sortby':'distance:1',
               'page_index' : page,  //页码
               'geotable_id':Util.config.geotable_id,
               'ak'         : Util.config.ak  //用户ak
           };
       geosearchAction();
       
       
   }
    
    /**
     * 麻点图点击麻点的回调函数
     * @param 麻点图点击事件返回的单条数据
     */
    function hotspotclickCallback(e) {
        var customPoi = e.customPoi,
		    str = [];
		str.push("address = " + customPoi.address);
		str.push("phoneNumber = " + customPoi.phoneNumber);
        var content = '<p style="width:280px;margin:0;line-height:20px;">地址：' + customPoi.address + '</p>';
        //创建检索信息窗口对象
        var searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
            title: customPoi.title,  //标题
            width: 290,              //宽度
            height: 40,              //高度
            enableAutoPan : true,    //自动平移
            enableSendToPhone: false, //是否显示发送到手机按钮
            searchTypes :[
                 //周边检索 BMAPLIB_TAB_SEARCH, 
                BMAPLIB_TAB_TO_HERE,  //到这里去
                //从这里出发BMAPLIB_TAB_FROM_HERE 
            ]
        });
        var point = new BMap.Point(customPoi.point.lng, customPoi.point.lat);
        searchInfoWindow.open(point); //打开信息窗口
    }


    /**
     * 通过自提点筛选显示附近的自提点
     * @param 关键词
     * @param 当前页码
     */
    function searchAction(keyword, page) {
    	keyword = keyword || '';
        page = page || 0;
        var filter = []; //过滤条件
        var region='北京';
        $.each($('#selectedValue div'), function(i, item){ //将选中的筛选条件添加到过滤条件参数中
            var type  = $(item).attr('type'),
                value = $(item).attr('value');
            
            if (type == "location") {
                //keyword = value + " " + keyword;
                region=value;
            }
            else {
                filter.push(type + ':' + value);
            }
        });
        
      
        
        Util.geoSearch.url = "http://api.map.baidu.com/geosearch/v3/local?callback=?";
        Util.geoSearch.data={
                'q'          : keyword, //检索关键字
                'page_index' : page,  //页码
                'page_size' : 5,  //页码
                'filter'     : filter.join('|'),  //过滤条件
                'region'     : region,  //北京的城市id
                'output'     : 'json',  //北京的城市id
                'scope'      : '2',  //显示详细信息
                'geotable_id':Util.config.geotable_id,
                'ak'         : Util.config.ak  //用户ak
            };
        geosearchAction();
    }

    /**
     * 根据设定参数渲染自提点列表
     * @param 关键词
     * @param 当前页码
     */
    function geosearchAction()
    {   	
    	 // Util.geoSearch.data.filter+='|'+'group:'+Util.geoSearch.group;
        $.getJSON(Util.geoSearch.url,Util.geoSearch.data,function(e) {
        	console.log(e);
            	if(e.status=='0'){
               renderList(e, Util.geoSearch.data.page_index + 1);
               // renderMap(e, Util.geoSearch.data.page_index + 1);
                }
            	else
            	{
            		console.log(e.message);
                }
            });    	
    }

  
    /**
     * 渲染地图模式
     * @param result
     * @param page
     */
    function renderMap(res, page) {
        var content = res.contents;
       if(page=='0') $('#mapList').html('');
        map.clearOverlays();
        points.length = 0;

        if (content.length == 0) {
            $('#mapList').append($('<p style="border-top:1px solid #DDDDDD;padding-top:10px;text-align:center;text-align:center;font-size:13px;" class="text-warning">'+Util.language.error_no_points+'</p>'));
            return;
        }

        $.each(content, function(i, item){
        	
        	item=$.extend(item, checkAddressArea(new BMap.Point(item.location[0],item.location[1])));
        	var item2={'title':item.title,'city':item.city,'point_code':item.point_code,'device_code':item.device_code,'address':''};
        	var jsonitem=JSON.stringify(item2).replace(/\s+/g,"");
      
        	
            var point = new BMap.Point(item.location[0], item.location[1]),
                marker = new BMap.Marker(point);
            points.push(point);
            var tr = $("<tr><td width='75%'><a href='" + item.roomurl + "'>" + item.title + "<a/><br/>" + item.address + "</td><td width='25%'>" + item.pick_up_time + "<br/><span style='color:red;'></span></td></tr>").click(showInfo);
            $('#mapList').append(tr);
            marker.addEventListener('click', showInfo);
            function showInfo() {
                var content = "<img src='" + item.mainimage + "' style='width:111px;height:83px;float:left;margin-right:5px;'/>" +
                              "<p>名称：" + item.title + "</p>" +
                              "<p>地址：" + item.address + "</p>" +
                              "<p>营业时间：" +  +item.pick_up_time+"</p>"+
                '<p><input type="button" onclick=setzoon(\''+item.point_code+'\',\''+jsonitem+'\') class="lbs-body-sb" value="确定"></p>';
                
                //创建检索信息窗口对象
                var searchInfoWindow = new BMapLib.SearchInfoWindow(map, content, {
                    title  : item.title,       //标题
                    width  : 290,             //宽度
                    panel  : "panel",         //检索结果面板
                    enableAutoPan : true,     //自动平移
                    searchTypes   :[
                        BMAPLIB_TAB_SEARCH,   //周边检索
                        BMAPLIB_TAB_TO_HERE,  //到这里去
                        BMAPLIB_TAB_FROM_HERE //从这里出发
                    ]
                });
                searchInfoWindow.open(marker);
            };
            map.addOverlay(marker);
        });


        /**
         * 分页
         */
        var pagecount = Math.ceil(res.total / 10);
        if (pagecount > 76) {
            pagecount = 76; //最大页数76页
        }
 
        function PageClick (pageclickednumber) {
            pageclickednumber = parseInt(pageclickednumber);
            $("#mapPager").pager({ pagenumber: pageclickednumber, pagecount: pagecount, showcount: 3, buttonClickCallback: PageClick });
           	Util.geoSearch.data.page_index=pageclickednumber -1;
           	
           	geosearchAction();
        }
        
        if(pagecount>1){
            $("#mapPager").pager({ pagenumber: page, pagecount: pagecount, showcount:9, buttonClickCallback: PageClick });
            }
            else
            {
            $("#mapPager").html('');
            }
     
       // map.setViewport(points);//自动适配最佳视角
    };

    /**
     * 渲染列表模式
     * @param result
     * @param page
     */
    function renderList(res, page) {
    	
    	console.log(res);
    	
        var content = res.contents;
        if(page<=1) $('#listBoby').html('');

        if (content.length == 0) {
            $('#listBoby').append($('<p style="border-top:1px solid #DDDDDD;padding-top:10px;text-align:center;text-align:center;font-size:13px;" class="text-warning">'+Util.language.error_no_points+'</p>'));
            $("#pager").html('');
            return;
        }
var top=0;
var tpl="";

        $.each(content, function(i, item){ 

        	item=$.extend(item, checkAddressArea(new BMap.Point(item.location[0],item.location[1])));
var item2={'title':'[自提]'+item.title,'city':item.city,'point_code':item.point_code,'device_code':item.device_code,'address':''};
        	
        	var jsonitem=JSON.stringify(item2).replace(/\s+/g,"");
        	 //console.log(jsonitem);
        	tpl+='<li class="ztd">\
                <div class="ztd-title"> '+item.title;
                if(item.distance>0)
                	{if(item.distance>999)
                tpl+='<span class="ztd-km">'+item.distance/1000+'km</span>';
                	else
                tpl+='<span class="ztd-km">'+item.distance+'m</span>';
                	}
               tpl+='</div>\
                <div class="ztd-info"> \
                <span>地址：'+item.address+'</span>\
                <span>电话：400-015-0077</span>\
                <span>营业时间：'+item.pick_up_time+'<input type=button onclick=setzoon(\''+item.point_code+'\',\''+jsonitem+'\') style="width:84px;" class="lbs-body-sb" value="确定"></span>\
                </div>\
          </li>';
 
        });
       
        $('#listBoby').append(tpl);
        
        $('#listBoby .ztd').eq(0).addClass("act");
        
        $("#listBoby .ztd").bind("click",function(){
     	   $("#listBoby .ztd.act").removeClass("act");
     	   $(this).addClass("act");
        });
        
        /**
         * 分页
         */
        Util.geoSearch.data.listpagecount = Math.ceil(res.total / Util.geoSearch.data.page_size);
        if (Util.geoSearch.data.listpagecount > 76) {
        	Util.geoSearch.data.listpagecount = 76;
        }
        
        if(Util.geoSearch.data.listpagecount>1){      	
            $('#listBoby')[0].scrollTop=$('#listBoby')[0].scrollHeight; 
        	$("#pager").html('<a onclick="PageClick('+(Util.geoSearch.data.page_index+1)+')">点击查看更多</a>');
            //$("#pager").pager({ pagenumber: page, pagecount: pagecount, showcount:9, buttonClickCallback: PageClick });
            }
            else
            {
            $("#pager").html('');
            }
    }
    
    function PageClick (pageclickednumber) {
    	if(pageclickednumber<Util.geoSearch.data.listpagecount){
    	console.log();
        pageclickednumber = parseInt(pageclickednumber);
       // $("#pager").pager({ pagenumber: pageclickednumber, pagecount: pagecount, showcount:9, buttonClickCallback: PageClick });
        
        if(pageclickednumber<Util.geoSearch.data.listpagecount-1)
        $("#pager").html('<a href="#" onclick="PageClick('+(pageclickednumber+1)+')">点击查看更多</a>');
        else
        $("#pager").html('');
        
        //searchAction(keyword, pageclickednumber -1);
      	Util.geoSearch.data.page_index=pageclickednumber;
       	geosearchAction();
    	}
    	else
    	{
    		$("#pager").html('');
    	}
    }
    
  
    /*美食送接口*/
    /* 通过美食送接口判断gps点是否在可配送区域*/
    function  getMeishiregionByAddress(address){	
    	var url='apiv3/index.php?route=lbs/meishi/hgetByAddress';
    	 console.log(point);
    	 $.getJSON(url,
    			 {
                  'address' : address,  
                 },
    			 function(e) {
    		 console.log(e);
         });    	
    	
    }
    function  getMeishiregionByLng(point){	
    	var url='apiv3/index.php?route=lbs/meishi/hgetByLng';
    	 console.log(point);
    	 $.getJSON(url,
    			 {
                  'lng' : point.lng,  
                  'lat' : point.lat,
                 },
    			 function(e) {
    		 console.log(e);
         });    	
    	
    }
    
    function  hgetMeishiregionLng(point){	
    	var url='http://api.meishisong.cn/Getregion/getByAddress?callback=?';
    	 console.log(point);
    	 $.getJSON(url,
    			 {
                  'partner_id':'10000', 
                  'lng' : point.lng,  
                  'lat' : point.lat,
                  'sign': 'jkhhjgj'
                 },
    			 function(e) {
    		 console.log(e);
         });    	
    	
    }
    /* 通过接口设置可配送区域坐标*/
    		
    function  setMeishiregion(){	
    	var url='apiv3/index.php?route=lbs/meishi/getAllowarea';
    	 $.getJSON(url,{},
    		function(e) {
    		 meiregionarea=e;
         });    	
    	
    }
    /*检测是否在美食送可配送区域，并返回区域id*/
    function checkAddressArea(point){
    	
    	for(var j in meiregionarea) 
{
    	var zone_info=meiregionarea[j];

    	for (var i in zone_info)
    	{
    		if (zone_info[i].region_id==0)
    			continue;
    		else
    		{

    	var pts = disposePoints(zone_info[i]['region_coord']);

    	var polygon = new BMap.Polygon(pts, {strokeColor:"blue", strokeWeight:2, strokeOpacity:0.2});
    	map.addOverlay(polygon);
    	   	
    	var ply = new BMap.Polygon(pts);   
    	
		result = BMapLib.GeoUtils.isPointInPolygon(point,ply);

		if (result==true)
		{ 
			//console.log(result);
		    //	console.log(point);
  	        //console.log(pts);
			//console.log('可以配送'+i);
			return zone_info[i];
			break;
		}
       }
    	}
    }
    	return false;
 }
    
    function disposePoints(points)
    {
    	var pointsObjects = new Array();
    	
    	var pointsArray = points.split(";");
    	for (var i=0;i<pointsArray.length;i++)
    	{
    		coordinate = pointsArray[i].split(',');
    		tbp = new BMap.Point(coordinate[0],coordinate[1]);
    		pointsObjects.push(tbp);
    	}
    	
    	return pointsObjects;
    }
    
    function showPickPointsLbs()
    {
    	//$("#lbs").show();
    	var height=$(window).height();
    	layer.open({
        closeBtn:false,
        type: 1,
        shade:[0.5 , '#000' , true],
        title: false, //不显示标题
        area: ['100%','100%'],
        content:$('#lbs')//捕获的元素
    }); 

    }
    
    
    function showPickPointsLbs_pc(){
    	var height=$(window).height();
        layer.open({
        type: 1,
        shade:[0.5 , '#000' , true],
        title: false, //不显示标题
        area: ['640px','460px'],
        content:$('#lbs')//捕获的元素
    }); 
    }
    

    
    function setzoon(pointId,meishiareacode)
    {
    	var item=jQuery.parseJSON(meishiareacode);
    	
        $.cookie('select_point_id', pointId,{expires:7});
        $("#ztd-btn").html(item.title);
        console.log(item);

        $.ajax({
            url: 'index.php?route=checkout/checkout/shipping_method_load',
            type: 'post',
            cache:false,
            data: 'point_id='+pointId,
            dataType: 'json',
            success: function (json) { 
            	console.log(json);
            	 if(json.status=='0'){
            		   // $("#ztd-btn").html('');
            	    	//$("#ztd-info").html(item.title+'('+item.address+')');//+item.city
       		 
            		 if($('#shipping-method .shipping-method-list')){
                     $('#shipping-method .shipping-method-list').html(json.data.shipping_method);
                     
                 
                     turnbox($('#order-listaddress'),$('#page'),'right');
             	     updateAdditionalDate();
             	     checkoutComfirm();	
                     }
                    }
                    else
                   {
                    	console.log(json);
                    	//for(id in json.error_warning){
                        //   $('#msg_'+id).html(json.error_warning[id]);
                    	//}
                   }
            }
        });
        
        try{
        layer.closeAll();
        
        }catch(e){}
    }
    
$(document).ready(function(){
    Util.setMapHeight();
});