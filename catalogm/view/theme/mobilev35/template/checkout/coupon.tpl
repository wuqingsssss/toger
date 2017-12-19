<link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/uc.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<?php echo $header;?>
<!-- 公共头结束 -->
<div id="uc_body" style="width:100%">
    <div class="uc-coupon col-gray">  
        <?php if ($coupons) { ?>
        <?php foreach ($coupons as $coupon) { ?>
        <ul>
            <li class="fz-13 text-center coupon-select" data-id="<?php echo $coupon['coupon_customer_id']; ?>">
                <div>
                <?php if(!$coupon['used']) {?>
                <span class="co-1 bg-red col-white">
                    <div class="fz-20"><?php echo $coupon['discount']; ?></div>
                    <div class="fz-13">未使用</div>
                </span>
                <span class="co-bg">
                    <img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/ucco1.png">
                </span>
                <?php } ?>
                 </div>
                <span class="co-txt">
                    <div><span class="bg-white col-red"><?php echo $coupon['name']; ?></span></div>
                    <div><span class="bg-white col-gray">有效期至<font class="col-red"><?php echo $coupon['date_limit']; ?></font></span></div>
                    <div><span class="bg-white col-gray"><?php echo $coupon['usage']; ?></span></div>
                </span>
               
            </li>
        </ul> 
        <?php } ?>
        <?php } ?>
        <ul>
            <li class="fz-13 text-center coupon-select" data-id="0">
                <span class="co-1 bg-gray col-white">
                    <div class="fz-20">0</div>
                    <div class="fz-13">取消</div>
                </span>
                <span class="co-bg">
                    <img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/ucco2.png">
                </span>
                
                <span class="co-txt">
                   <div><span class="bg-white col-red"></span>&nbsp;</div>
                   <div><span class="bg-white col-gray"><font class="col-red">取消使用优惠券</font></span></div>
                   <div><span class="bg-white col-gray"></span>&nbsp;</div>
                </span>
            </li>
        </ul> 
    </div>
</div>

