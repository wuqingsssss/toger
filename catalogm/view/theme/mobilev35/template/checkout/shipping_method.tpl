<?php echo $header40; ?>
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/select_address.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/map.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/component.css?v=<?php echo STATIC_VERSION; ?>" />
<link type="text/css" rel="stylesheet" href="catalogm/view/theme/<?php echo $this->config->get('config_template'); ?>/css/animations.css?v=<?php echo STATIC_VERSION; ?>" />

<style>
#l-map{height:30%;overflow:hidden; width:100%;}
</style>
<div id="pt-main" class="pt-perspective">
    <div id="content" class="pt-page shipping-method-page">
    <?php echo $navtop;?>
        <div class="module bg-body" id="m-addresses">
         <?php if ($addresses) { ?>
            <?php foreach ($addresses as $address) { ?>
            <div class="address" data-id="<?php echo $address['address_id'];?>">
                <div class="poi hidden">{"lng":"<?php echo $address['location']['lng'];?>","lat":"<?php echo $address['location']['lat'];?>"}</div>
                <div class="shipping_data hidden"><?php echo $address['shipping_data'];?></div>
                <div class="shipping_code hidden"><?php echo $address['shipping_code'];?></div>
                <div class="content">
                     <div class="info clearfix">                     
                        <span class="name pull-left"><?php echo $address['firstname'];?></span>
                        <span class="mobile pull-right"><?php echo $address['mobile'];?></span>
                     </div>
                     <div class="detail">收货地址：<font class="address_1"><?php echo $address['address_1'];?></font><font class="address_2"><?php echo $address['address_2'];?></font></div>
                 </div>  
                 <div class="buttons clearfix">
                    <?php if($address['status'] == 1){?>
                    <?php if($address_id == $address['address_id']){ ?>
                        <div class="radio checked"><b></b></div>
                    <?php }else{?>
                        <div class="radio"><b></b></div>
                    <?php }?>
                    <?php }?>
                        <a href="javascript:" class="modify-address"><span><?php if($address['status']==1){ echo $button_modify;}else{ echo '升级';}?></span></a>
                        <a href="javascript:" class="delete-address"><span><?php echo $button_delete;?></span></a>                 
                 </div>         
            </div>    
            <?php }?>
            <div id="address-empty" class="col-gray fm-13" style="padding:0.1rem 0 0 5%; display:none;">
                <span> 请添加收货地址</span>   
            </div>
            <?php }else{ ?>
            <!-- 没有收货地址 -->
            <div id="address-empty" class="col-gray fm-13" style="padding:0.1rem 0 0 5%">
                <span> 请添加收货地址</span>   
            </div>
            <?php }?>
        </div>
       
        <div class="module bg-body" id="add-new-address">
            <div class="new_save_address btn"><button>新增收货地址</button></div>
        </div>
    </div>
    <div id="add_address" class="pt-page  shipping-method-page">
     <?php echo $navtop2;?>
        <form id="new_address_form">
        	<div id = "Modify">
        		<section class= "Modify-box">
        		  <div>
        			 <label>联系人</label>
        			 <input type="text" name="firstname" placeholder="您的姓名"/>
        	      </div>
        		</section>
        		<section class= "Modify-box">
        		  <div>
        			<label>联系电话</label>
        			<input type="text" name="mobile" placeholder="您的手机号"/>
        	      </div>
        		</section>
        		<section class= "Modify-box">
        		   <div>
            			<label>收货地址</label>
            		    <input type="text" id="address_1" class="fm-13" name="address_1" readonly="readonly" placeholder="小区、写字楼、学校、街道" value=""/>
            	        <input type="hidden" id="address_1_poi" name="address_1_poi" value="" />
            	   </div>
            	   <div class="door-number">	    
            		    <input type="text" name="address_2" placeholder="楼层、门牌号" />
                        <input type="hidden" id="address_id" name="address_id" value="0" />
                        <input type="hidden" id="default_shipping_method" value="meishisong" />
                        <input type="hidden" id="shipping_code" name="shipping_code" value="meishisong" />
                        <input type="hidden" id="shipping_data" name="shipping_data" value="" />
                        <input type="hidden" id="shipping_city" name="shipping_city" value="北京市" />   
                   </div>              
                </section>
        	</div>
    	</form>
    	<div class="new_save_address">
    		<button id="save_address">保存地址</button>
    		<button id="save_cancel" class="cancel" style="background-color: #a0a0a0;">取消</button>
    	</div>
    </div>
<div id="map_address" class="pt-page shipping-method-page">
 <?php echo $navtop3;?>
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
	            <input id="inputkey" name="inputkey" class="fm-13" type="text" name="wd" placeholder="小区、写字楼、学校、街道"/>
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

<?php echo $footer; ?>
