<?php echo $header40; ?>

<!-- 页面自定义样式 -->
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/home.css" rel="stylesheet"/>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>css/group.css" rel="stylesheet"/>
<link href="<?php echo HTTP_ASSETS . DIR_DIR; ?>view/theme/mobilev35/css/pullToRefresh.css" rel="stylesheet"/>

<?php echo $header; ?>

<div class="buy-Group" id="m-foods">
<?php if($header_type=='app'){ ?>
     <img src="<?php echo HTTP_CATALOG . $tplpath; ?>images/groupapp.png" width="100%" />
<?php }else {?>
    <ul>
		<?php foreach ($list as $product) { ?>
			<li>
			    <div class="img">
    				<a href = "<?php echo $this->url->link('group/group/info', 'id='.$product['g_id'], 'SSL');?>" class="img-wrapper pull-left">
    					<img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" />
    				</a>
				</div>
				<div class="content">
					<p class="p-01 text-overflow"> <?php echo $product['name']; ?></p>
					<p class="p-02 text-overflow"><?php echo $product['desc']; ?></p>
					<p class="p-03 text-delete">￥<?php echo sprintf("%.2f",$product['price']); ?></p>
					<div class="Price">
					    <span class="box-01"><?php echo $product['quantity']; ?>份</span>
                        <span class="box-02">包邮</span>
                        <span class="box-03">￥<b><?php echo sprintf("%.2f",$product['sell_price']); ?></b></span>
					</div>
					<div class="Group-btn">
                      <span><?php echo $product['member_num'].'人团'; ?></span>
                      	<a href="<?php echo $this->url->link('group/group/info', 'id='.$product['g_id'], 'SSL');?>">
                      	   <b></b>发起拼团
                      	</a>
                    </div>
				</div>
			</li>
		<?php } ?>
    </ul>	
    <?php } ?>
</div>

<div id="footer">
<!-- 公共底部开始 -->
<?php echo $this->getChild('module/navbar'); ?>
<!-- 页面内容结束 -->
</div>
<script src="<?php echo HTTP_CATALOG . DIR_DIR . 'view/theme/'; ?>mobilev35/js35/common.js"></script>
<script src="<?php echo HTTP_CATALOG . DIR_DIR . 'view/theme/'; ?>mobilev35/js35/home.js"></script>
<?php echo $footer35; ?>