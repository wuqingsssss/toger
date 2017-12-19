<div id="lbs" class="lbs-box hide">
   <div class="box-title">选择您的区域<a class="close" onclick="layer.closeAll();">关闭</a></div>
   <div class="lbs-title">
      <div id="geoBtn" class="lbs-title-dw"></div>
      <div class="lbs-keyword">
      <input type="text" id="keyword" name="keyword" listbody="#reslist" autocomplete="off" placeholder="输入检索关键字">
       <div class="hide" id="selectedValue">
       </div>
      <div id="reslist" class="tab-list-keyword hide">
      <ul>
      </ul>
      </div>
      </div>
      <div id="searchBtn" class="lbs-title-sb"></div>
   </div> 
   <div id="filterBox" class="filterBox">
   </div>
   <div>
   <ul>
   <li class="lbs-body-title"><img src="assets/image/lbs/1_17.png">附近的自提点 </li>
   </ul>
   <ul id="listBoby" class="lbs-body">  
   </ul>
   <ul><li><div id="pager"></div></li></ul>
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
</div>
<script src="http://api.map.baidu.com/api?v=1.5&ak=<?php echo BDYUN_WEB_AK;?>" type="text/javascript"></script>
<script src="http://api.map.baidu.com/components?ak=<?php echo BDYUN_WEB_AK;?>&v=1.0"></script>
<script type="text/javascript" src="http://api.map.baidu.com/library/SearchInfoWindow/1.5/src/SearchInfoWindow_min.js"></script>
    <!--script type="text/javascript" src="../../assets/js/jquery/jquery-1.7.2.min.js"></script-->
    <script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/jquery.pager.js"></script>
    <script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/bootstrap.js"></script>
    <script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/GeoUtils.js"></script>
    <script type="text/javascript" src="assets/libs/lbsyun_v2.0/js/main.js"></script>
    <script>
    /**
     * 条件筛选模块相关代码 start
     * 条件筛选的数据
     */
    var filterData=<?php echo $filterData;?>;
    var lbspointhome=<?php echo $lbspointhome;?>;
    var meiregionarea={};
    Util.config.ak='<?php echo BDYUN_WEB_AK;?>';
    Util.config.geotable_id='<?php echo GEOTABLE_ID;?>';
    Util.geoSearch.group='[0,<?php echo $this->customer->getCustomerGroupId();?>]';
      iTofilterBox(filterData,'#filterBox');
    //自动定位模块
    autoGeo('<?php echo $this->request->server["REMOTE_ADDR"];?>');
    //条件筛选模块相关代码 end

    //检索模块相关代码
    var keyword     = "",   //检索关键词
        page        = 0,    //当前页码
        points      = [];   //存储检索出来的结果的坐标数组
 
   

$(document).ready(function(){
	$("#ztd-btn").bind('click',showPickPointsLbs);
	setMeishiregion();	
	 //绑定检索按钮事件
    $('#keyword').bind('click', function(){
    	$('#keyword').val('');
        $('#reslist').show();
    }).bind('keyup', function(){//change 	
 	   searchAllAction($('#keyword').val());
 });

	 
	 
    $('#searchBtn').bind('click', function(){
        keyword = $('#keyword').val();
        searchAllAction(keyword);
    });
    //绑定检索按钮事件
    $('#geoBtn').bind('click', function(){
    	$('#keyword').val('');
    	autoGeo();
    });
    
    $('#selectedValue').hide();   

       searchAction(keyword);
});
</script>