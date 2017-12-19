<?php echo $header40; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>template/promotion/season/css/sl.css" rel="stylesheet"/>
<!-- 公共头结束 -->
<!-- 公共头开始 -->
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>"<a class='return' href='{$this->url->link('common/home')}'></a>",
       'center'=>'<a class="locate fz-18">'.$promotion['pb_name'].'</a>',
       'right'=>''
)));?>
<!-- 公共头结束 -->
<!-- 页面内容开始 -->
<div class="Template-con">
    <section>
       <div class="Template-banner">
           <p><?php echo $promotion['page_header']?></p>
       </div>
    </section>
	<?php foreach($productgroups as $g_info){?>
    <section>
       <div class="Template-list">
           <p><img src="<?php echo HTTP_IMAGE.$g_info['banner']?>"></p>
       </div>
		<?php foreach($g_info['data'] as $k => $p_info){?>
		<div class="Vegetables-f">
       <div class="Vegetables">
            <div class="Vegetables-name clearfix">
               <p class="f-l ">
                   <b class="origin <?php if($k%2 == 0){echo 'co-01';}else{echo 'co-02';}?>"></b><?php echo $p_info['name']?>
               </p>
               <p class="f-r">
                   <b class="Price"><?php echo $p_info['price']?></b>
               </p>
            </div>
            <div class="Vegetables-box" >
				<div class="<?php if($k%2 == 0){echo 'Vegetables-pic bg-01';}else{echo 'Vegetables-pic bg-02';}?>">
                    <p>
                        <?php echo $p_info['subtitle']?>
                    </p>
                </div>
                <div class="pic">
                    <a href="<?php echo $p_info['href']?>">
                    <img src="<?php echo $p_info['thumb']?>">
                    </a>
                </div>
                <div class="Price-btn">
                    <button class="round btn-add-cart in-list" data-id="<?php echo $p_info['product_id']?>" data-code="<?php echo $p_info[promotion][promotion_code];?>">立即购买</button>
                </div>
            </div>
       </div>
			</div>
		<?php } ?>
    </section>
	<?php } ?>
    <!--配置野味---------------->
</div>
<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar'); ?>
<!-- 页面内容结束 -->
<!-- 公共底部开始 -->
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/cart.js"></script>
<?php echo $footer35; ?>

