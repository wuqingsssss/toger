<?php echo $header35; ?>
 <link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/uc.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
'left'=>'<a class="return" href="javascript:_.go()"></a>',
'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
'right'=>''
)));?>
<!-- 公共头结束 -->

<div id="uc_body">

<div class="uc-coupon col-gray">
             <ul> 
              <li class="fz-16"> <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="coupon">
                <span><input type="text" name="coupon" class="bod-gray1"  placeholder="输入兑换码"/></span>
                <span class="pull-right">
                <a onclick="$('#coupon').submit();" class="btn2 bg-red col-white"><?php echo $button_add_couopon; ?></a>
                </span> 
                </form>
              </li>
             </ul>
             
              <?php if ($success) { ?>
    <ul><li class="fz-13 col-red"><?php echo $success; ?></li></ul>
    <?php } ?>
  <?php if ($error_warning) { ?>
  <ul><li class="fz-13 col-red"><?php echo $error_warning; ?></li></ul>
  <?php } ?>

    <?php if ($coupons) { ?>
        <?php foreach ($coupons as $coupon) { ?>
        <ul>
            <li class="fz-13 text-center">
                <?php if(!$coupon['used']) {?>
                <span class="co-1 bg-red col-white">
                <div class="fz-20"><?php echo $coupon['discount']; ?></div>
                <div class="fz-13">未使用</div>
                </span>
                <span class="co-bg">
                <img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/ucco1.png">
                </span>
                <?php }else{?>
                <span class="co-1 bg-gray col-white">
                  <div class="fz-20"><?php echo $coupon['discount']; ?></div>
                  <div class="fz-13">已使用</div>
                </span>
                <span class="co-bg">
                <img src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/images/uc/ucco2.png">
                </span>
                <?php } ?>
                
                <span class="co-txt">
                 <div><span class="bg-white col-red"><?php echo $coupon['name']; ?></span></div>
                <div><span class="bg-white col-gray">有效期至<font class="col-red"><?php echo $coupon['date_limit']; ?></font></span></div>
                <div><span class="bg-white col-gray"><?php echo $coupon['usage']; ?></span></div>
                </span>
            </li>
        </ul> 
        <?php } ?>
    <?php } else{ ?>
    <div class="fz-13 text-center">
          <span class="co-txt">
                <div class="fz-20">没有可用优惠券</div>
          </span>
    </div>
    <?php } ?>

</div>

</div>

<div id="footer">
</div>
<div class="overlay-container hidden" id="filter-coupon">
    <div class="overlay-content-container">
         <div class="overlay-content bg-white col-gray fz-18 text-center uc-body cancel">
            <ul>
              <li class="fz-16 plist ">
                  <span>兑换码有误请重新输入</span>
               </li><li>
            <span class="col-red "><a onclick="$('#filter-coupon').hide();">确定</a></span>
              </li>
             </ul>
        </div>
    </div>
</div>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>