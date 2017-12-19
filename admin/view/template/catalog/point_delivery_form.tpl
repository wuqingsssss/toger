<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>

<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:modify'))) { ?>
                <button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button>
            <?php } ?>
            <button onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></button>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                 <tr>
                    <td><span class="required">*</span> 
                    <?php echo $entry_zone_name; ?>
                    </td>
                    <td>
                     <select name="country_id" id="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=localisation/city/zone&token=<?php echo $token; ?>&country_id=' + this.value);">
              <option value=""><?php echo $text_select; ?></option>
	      <?php foreach ($countries as $country) { ?>
              <?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select><select name="zone_id" id="zone_id">
            </select>
	       <?php if ($error_zone) { ?>
            <span class="error"><?php echo $error_zone; ?></span>
            <?php } ?>
                    </td>
                </tr>
                 <tr>
                    <td><span class="required">*</span> <?php echo $entry_shipping_code; ?></td>
                    <td>
                         <select name="code" class="span2" onchange="$('select[name=\'p_delivery_id\']').load('index.php?route=catalog/pointdelivery/point_delivery_form&token=<?php echo $token; ?>&code=' + this.value+'&zone_id=' + $('#zone_id').val()+'&p_delivery_id=<?php echo $p_delivery_id;?>' );">
                            <?php foreach ($delivery as $key => $item) { ?>
                                <option
                                    value="<?php echo $item['code']; ?>" <?php if ($item['code'] == $code) { ?> selected="selected" <?php } ?> ><?php echo $item['title']; ?></option>
                            <?php } ?>
                       </select>     
                        <?php if ($error_code) { ?>
                            <span class="error"><?php echo $error_code; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                 <tr>
                    <td><span class="required">*</span> <?php echo $entry_p_delivery_id; ?></td>
                    <td>
                         <select name="p_delivery_id" class="span2">
                         <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($p_pointdeliverys as $key => $item) { ?>
                                <option
                                    value="<?php echo $item['delivery_id']; ?>" <?php if ($item['delivery_id'] == $p_delivery_id) { ?> selected="selected" <?php } ?> ><?php echo $item['region_name']; ?></option>
                            <?php } ?>
                       </select>     
                        <?php if ($error_code) { ?>
                            <span class="error"><?php echo $error_code; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                    <td>
                        <input type="text" name="name" value="<?php echo $name; ?>" maxlength="200" class="span6"/>
                        <?php if ($error_name) { ?>
                            <span class="error"><?php echo $error_name; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                 <tr>
                    <td><span class="required">*</span> <?php echo $entry_region_name; ?></td>
                    <td>
                        <input type="text" name="region_name" value="<?php echo $region_name; ?>" maxlength="200" class="span6"/>
                        <?php if ($error_region_name) { ?>
                            <span class="error"><?php echo $error_region_name; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                 <tr>
                    <td><span class="required">*</span> <?php echo $entry_region_id; ?></td>
                    <td>
                        <input type="text" name="region_id" value="<?php echo $region_id; ?>" maxlength="200" class="span6"/>
                        <?php if ($error_region_id) { ?>
                            <span class="error"><?php echo $error_region_id; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                   <tr>
                    <td><span class="required">*</span> <?php echo $entry_region_code; ?></td>
                    <td>
                        <input type="text" name="region_code" value="<?php echo $region_code; ?>" maxlength="200" class="span6"/>
                        <?php if ($error_region_code) { ?>
                            <span class="error"><?php echo $error_region_code; ?></span>
                        <?php } ?>
                    </td>
                </tr>
                  <tr>
                    <td><?php echo $entry_address; ?></td>
                    <td>
                        <input type="text" name="address" value="<?php echo $address; ?>" maxlength="200"
                               class="span6"/>
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_telephone; ?></td>
                    <td>
                        <input type="text" name="telephone" value="<?php echo $telephone; ?>"/>
                    </td>
                </tr> <tr>
                    <td><?php echo $entry_business_hour; ?></td>
                    <td>
                        <input type="text" name="business_hour" value="<?php echo $business_hour; ?>"/>
                    </td>
                </tr>
                 <tr>
                    <td><?php echo $entry_smodel; ?></td>
                    <td>

           <input type="radio" name="smodel" value="1" <?php if($smodel=='1') echo' checked="checked"';?> />地理围栏&nbsp&nbsp&nbsp&nbsp
          <input type="radio" name="smodel" value="2" <?php if($smodel=='2') echo' checked="checked"';?> />附近检索&nbsp&nbsp&nbsp&nbsp
         
                        </td>
                </tr> 
                 <tr>
                    <td><?php echo $entry_region_coord; ?></td>
                    <td>
                        <textarea id="region_coord" name="region_coord" cols="80" rows="5"
                                  class="span6"><?php echo $region_coord; ?></textarea>
                                   <?php if ($error_region_coord) { ?>
                            <span class="error"><?php echo $error_region_coord; ?></span>
                        <?php } ?>
                        
             <div id="l-map" style="overflow: hidden;width:640px;height:640px; position: relative; z-index: 0; color: rgb(0, 0, 0); text-align: left; background-color: rgb(243, 241, 236);">
   </div>
                        
                    </td>
                </tr>
                 <tr>
                    <td><?php echo $entry_poi; ?></td>
                    <td>
                        <input type="text" name="poi" value="<?php echo $poi; ?>"/><?php echo $poihash; ?>
                        <?php if ($error_poi) { ?>
                            <span class="error"><?php echo $error_poi; ?></span>
                        <?php } ?>
                    </td>
                </tr>             
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td>
                         <?php foreach (EnumPointStatus::getOptions() as $key => $value) { ?>
                           <input type="radio" name="status" value="<?php echo $value['value'];?>" <?php if($status==$value['value']) echo' checked="checked"';?> /><?php echo $value['name']; ?>&nbsp&nbsp&nbsp&nbsp
                          <?php } ?>
                        </td>
                </tr>  
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1"/></td>
                </tr>
            </table>
            <script type="text/javascript"><!--
		  $('select[name=\'zone_id\']').load('index.php?route=localisation/city/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
	//--></script> 
        </form>
    </div>
</div>
<script src="http://api.map.baidu.com/api?v=2.0&ak=pUQv6G1P9uhBINarLQoOliVz" type="text/javascript"></script>
<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
<script>var Util={ config:{
     'ak'         : '' 
}};
Util.config.ak='pUQv6G1P9uhBINarLQoOliVz';
var map,points,polygon;
map = new BMap.Map("l-map");
//map.centerAndZoom("北京",14);                   // 初始化地图,设置城市和地图级别。

map.addControl(new BMap.NavigationControl());       // 添加比例尺控件

map.centerAndZoom(new BMap.Point('116.88','39.10'),14);                   // 初始化地图,设置城市和地图级别。

//console.log("北京",map.getCenter(),map.getZoom());

$(function(){
	
  points=disposePoints('<?php echo $region_coord; ?>');
 
  polygon = new BMap.Polygon([]
	   , {strokeColor:"blue"
	   , strokeWeight:2
	   , strokeOpacity:0.8
	   ,fillColor:'#00A99C'
	   ,fillOpacity:0.4
	   ,strokeStyle:'dashed'
	   ,enableMassClear:false,enableEditing:true});  //创建多边形
	   
	    //centre:椭圆中心点,X:横向经度,Y:纵向纬度点距围栏显示
	   var point=disposePoints('<?php echo $poi; ?>');
	   var oval = new BMap.Polygon(add_oval(point.obj[0],0.004,0.003), {strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5,enableMassClear:false,enableEditing:false});
		map.addOverlay(oval);
	  
		
		//增加地理围栏显示
	   runderPolygon();
	   runderLabel();
	   
       polygon.addEventListener("lineupdate",function(e){
	 
	 var pois=polygon.getPath();
	 var pointsarray=[];
	 var lastpoi='';
	 for(i in pois)
	 { 
		 pointsarray.push(pois[i].lng+' '+pois[i].lat);
		 lastpoi=pois[i].lng+' '+pois[i].lat;
	 }
	 
	 if(pointsarray[0]!=lastpoi)
		 {
		 pointsarray.push(pointsarray[0]);
		 }
	 
	     //console.log(polygon.getPath());
         $('#region_coord').html(pointsarray.join(",")); 
         
         points=disposePoints($('#region_coord').html());

         runderLabel();

});
 
});


function runderPolygon(){
	 polygon.setPath(points.obj);
	 
	 
	 map.addOverlay(polygon);   //增加多边形
	 
	 var centroid = d3.geom.polygon(points.array).centroid(); 
	 map.centerAndZoom(new BMap.Point(centroid[0],centroid[1]),map.getZoom());//,14
	
	 //console.log(new BMap.Point(centroid[0],centroid[1]),map.getZoom());
	 /* var opts = {
			  position : new BMap.Point(centroid[0],centroid[1]),    // 指定文本标注所在的地理位置
			  offset   : new BMap.Size(-20, 0),    //设置文本偏移量
			  enableMassClear:true
			};
			
  			
var label = new BMap.Label('<?php echo $region_name; ?>', opts);  // 创建文本标注对象
				label.setStyle({
					 color : "red",
					 fontSize : "12px",
					 height : "20px",
					 lineHeight : "20px",
					 fontFamily:"微软雅黑",
				     opacity: 0.7
				 });	  
map.addOverlay(label); 
	 */
}

function runderLabel(){

	 map.clearOverlays();    //清除地图上所有覆盖物

	 for (var i=0;i<points.obj.length;i++){
		 
		 var opts = {
	 			  position : new BMap.Point(points.obj[i].lng,points.obj[i].lat),    // 指定文本标注所在的地理位置
	 			  offset   : new BMap.Size(-20, 0),    //设置文本偏移量
	 			  enableMassClear:true
	 			};
		 if(i>1&&i<points.obj.length-1){
		 var label = new BMap.Label('x'+i, opts); 
			label.setStyle({
				 color : "red",
				 fontSize : "12px",
				 height : "20px",
				 lineHeight : "20px",
				 fontFamily:"微软雅黑",
			     opacity: 0.7
			 });
			
			label.index=i;

			label.addEventListener('click',function(e){

				points.obj.splice(this.index,1);
				points.array.splice(this.index,1);
				
				runderPolygon();
			});
			
         map.addOverlay(label); 
         }
		 
	 }
}
function addPoint2Points(point,points){
	points.obj.push(point);
	points.array.push([point.lng, point.lat]);
	return points;
}


function disposePoints(points)
{
	var pointsObjects = new Array();
	var temppoints = new Array();

	var pointsArray = points.split(",");
	for (var i=0;i<pointsArray.length;i++)
	{
		coordinate = pointsArray[i].split(' ');
		tbp = new BMap.Point(coordinate[0],coordinate[1]);
		pointsObjects.push(tbp);
		temppoints.push([tbp.lng, tbp.lat]);
	}
	
	return {'obj':pointsObjects,'array':temppoints};
}

function add_oval(centre,x,y)
{
	var assemble=new Array();
	var angle;
	var dot;
	var tangent=x/y;
	for(i=0;i<36;i++)
	{
		angle = (2* Math.PI / 36) * i;
		dot = new BMap.Point(centre.lng+Math.sin(angle)*y*tangent, centre.lat+Math.cos(angle)*y);
		assemble.push(dot);
	}
	return assemble;
}

</script>