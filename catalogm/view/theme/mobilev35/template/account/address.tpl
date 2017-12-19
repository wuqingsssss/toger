<?php echo $headersimple; ?>
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/general.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/address.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/component.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/animations.css?v=<?php echo STATIC_VERSION; ?>" />
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>'<a class="return" href="javascript:_.go();"></a>',
       'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
       'right'=>''
)));?>
<style>
#l-map{height:30%;overflow:hidden; width:100%;}
</style>
<div id="pt-main" class="pt-perspective">
    <div id="content" class="pt-page shipping-method-page">
       <?php if ($addresses) { ?>
        <div class="module bg-body" id="m-addresses">
            <?php foreach ($addresses as $address) { ?>
            <div class="address" data-id="<?php echo $address['address_id'];?>">
                <div class="content"><a>
                 <?php if($address['location']){?>
                     <div class="radio"> <i class="icon icon-radio<?php if($address_id == $address['address_id']){?> checked<?php }?>"></i></div>
                    <div class="poi hidden">{"lng":"<?php echo $address['location']['lng'];?>","lat":"<?php echo $address['location']['lat'];?>"}</div>
                    <div class="shipping_data hidden"><?php echo $address['shipping_data'];?></div>
                    <div class="shipping_code hidden"><?php echo $address['shipping_code'];?></div>
                     <?php }?>
                     <div class="info">                     
                        <div class="clearfix">
                        <span class="name pull-left"><?php echo $address['firstname'];?></span>
                        <span class="mobile pull-right"><?php echo $address['mobile'];?></span>
                        </div>
                        <div class="detail">收货地址：<font class="address_1"><?php echo $address['address_1'];?></font><font class="address_2"><?php echo $address['address_2'];?></font></div>
                    </div>
                     </a>
                    <div class="buttons">
                    <div class="modify-address button"><i class="icon icon-edit col-green fm-14"></i><span>
      <?php if($address['location']){ echo $button_modify;}else{ echo '升级';}?>
         </span></div>
                        <div class="delete-address button"><i class="icon icon-trash-empty col-green fm-14"></i><span><?php echo $button_delete?></span></div>
                    </div>
                   
                </div>    
            </div> 
            <?php }?>
        </div>
        <?php }else{ ?>
        <!-- 没有收货地址 -->
        <span> 请添加收货地址</span>   
        <?php }?>
        <div class="module bg-body" id="add-new-address">
            <div class="new_save_address btn"><button>新增收货地址</button></div>
        </div>
    </div>
    <div id="add_address" class="pt-page  shipping-method-page">
        <form id="new_address_form">
        	<ul class="address_div">
        		<li>
        			<span>联系人</span>
        			<input type="text" name="firstname" placeholder="您的姓名"/>
        		</li>
        		<li>
        			<span>联系电话</span>
        			<input type="text" name="mobile" placeholder="您的手机号"/>
        		</li>
        		<li>
        			<span>收货地址</span>
        				<span style="width:80%;position: relative;"><input type="text" id="address_1" class="fz-14" name="address_1" placeholder="小区、写字楼、学校、街道" value=""/>
        				 <input type="hidden" id="address_1_poi" name="address_1_poi" value="" />
                   </span>
                    
               </li>
        		<li>
                    <span></span>
                    <input type="text" name="address_2" placeholder="楼层、门牌号" style="margin-left: 100px;"/>
                    <input type="hidden" id="address_id" name="address_id" value="0" />
                    <input type="hidden" id="default_shipping_method" value="meishisong" />
                    <input type="hidden" id="shipping_code" name="shipping_code" value="meishisong" />
                    <input type="hidden" id="shipping_data" name="shipping_data" value="" />
                    <input type="hidden" id="shipping_city" name="shipping_city" value="北京市" />
        		</li>
        		
        	</ul>
    	</form>
    	<div class="new_save_address">
    		<button id="save_address">保存地址</button>
    		<button id="save_cancel" class="cancel" style="background-color: #a0a0a0;">取消</button>
    	</div>
    </div>
<div id="map_address" class="pt-page shipping-method-page">
	<!--配送地址超出范围start-->
	<div style="display: none;">
		<div class="mask"></div>
		<div class="coupon_succeed" style="display: block;">
			<p>您的收货地址超出配送范围，请重新输入！</p>
			<div class="coupon_ok">好的</div>
		</div>
	</div>
	<!--配送地址超出范围end-->
	        <div class="search-input">
	            <input id="inputkey" name="inputkey" class="fz-14" type="text" name="wd" placeholder="小区、写字楼、学校、街道"/>
	            <div id="searchResultPanel"></div>
	        </div>
	<li id="l-map" class="o_map_img ofh">
    </li>
	<div class="o_map_add">
	</div>
	<ul id="localsearchresult" class="o_map_select ofh">
	</ul>
	<div class="new_save_address">
    		<button class="cancel" style="background-color: #a0a0a0;">取消</button>
 </div>
	
	</div>
</div>
<script src="http://api.map.baidu.com/api?v=2.0&ak=<?php echo BDYUN_WEB_AK;?>" type="text/javascript"></script>
<script>var Util={ config:{
	 'geotable_id': '',
     'ak'         : '' 
}};
Util.config.ak='<?php echo BDYUN_WEB_AK;?>';
Util.config.geotable_id='<?php echo GEOTABLE_ID;?>';
</script>
<script type="text/javascript" src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/modernizr.custom.js"></script>
<script type="text/javascript" src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/common.js"></script>
<script src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/pagetransitions.js"></script>
<script src="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/js35/select_address.js"></script>

<?php echo $footer35; ?>