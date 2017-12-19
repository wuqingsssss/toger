<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  
  <div class="account-info">
  	<div class="username">
  	<strong><?php echo $this->customer->getDisplayName(); ?></strong>,
  	<span id="welcome">欢迎您！</span></div>
  	<div class="account">
  		<div class="rank r<?php echo $user_group_id; ?>">
        <s></s>
        <a target="_blank" href="<?php echo $this->url->link('information/information&information_id=20'); ?>"><?php echo $user_group_name; ?></a>
      </div>
  	</div>
  	
  	
    <div id="remind">
            <div class="oinfo">
                <dl class="fore">
                    <dt>订单提醒：</dt>
                    <dd><a class="order unpayment" href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::UnPayment);?>">未付款订单(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::UnPayment); ?></span>)</a></span></dd>
                    <dd><a class="order payment"  href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Payment);?>">进行中订单(<span class="count"><?php echo $this->getTotalOrderCount(EnumOrderStatus::Payment); ?></span>)</a></dd>
                </dl>
   			 </div>
   			 <div class="ainfo">
   			    <?php if(isset($transaction)){?>
                <dl class="fore">
                    <dt>账户余额：</dt>
					<dd><span id="divBalance"><strong class="ftx-03"><?php echo $total;?></strong></span></dd>
                </dl>
                <!--  <dl>
                 <dt class="score"><?php echo $text_reward?>：</dt>
                    <dd class="fore1">
                    	<a href="<?php echo $this->url->link('account/reward');?>" title="<?php echo $text_reward?>">
						   <span id="divContent" class="ftx-03"><?php echo $points;?></span>
						</a>
					</dd>	
                </dl>			 -->  
<!--                <dl>
                    <dt>优惠券：</dt>
                    <dd class="fore1"><a href=""><span id="CouponCount">0</span>张</a>
                    </dd>-->
                </dl>
                <?php }?>
            </div>
   </div>
  </div>
  
  
  <div id="tabs" class="htabs mgt20">
   	<a href="#tab-latest" title="最新订单">最新订单</a>
  </div>
  <div id="tab-latest" class="tab-content">
  
  <?php if ($orders) { ?>
  	<?php include dirname(__FILE__).'/ilex_order_list.php'; ?>
  <?php } else { ?>
    <div class="content"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
 <script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?> 