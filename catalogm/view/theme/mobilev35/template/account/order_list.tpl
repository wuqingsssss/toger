<?php echo $header35; ?>
 <link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/uc.css" rel="stylesheet"/>
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>'<a class="return" href="javascript:_.go();"></a>',
       'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
       'right'=>''
))
);?>
	<div id="uc_body">
		<div class="text-center bg-white fz-13 col-gray uc-order-tab">
		    <ul<?php if(!$filter['filter_order_status']&&!$filter['filter_not_order_status_ids']) echo ' class="col-red"';?>>
		       <a href="<?php echo $this->url->link('account/order');?>"> <li>全部</li>
		        <li>(<?php echo $this->getTotalOrderCount(); ?>)</li>
		        </a>
		    </ul>
		    <ul<?php if($filter['filter_order_status']==EnumOrderStatus::UnPayment) echo ' class="col-red"';?>>
		        <a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::UnPayment);?>"><li> 待付款 </li>
		        <li> (<?php echo $this->getTotalOrderCount(EnumOrderStatus::UnPayment); ?>)</li></a>
		    </ul>
		    <ul<?php if($filter['filter_not_order_status_ids']) echo ' class="col-red"';?>><a href="<?php echo $this->url->link('account/order&filter_not_order_status_ids='.EnumOrderStatus::UnPayment.','.EnumOrderStatus::Refunded.','.EnumOrderStatus::Complete.','.EnumOrderStatus::Cancel);?>">
		        <li> 进行中</li>
		        <li>(<?php echo $this->model_account_order->getTotalOrders(array('filter_not_order_status_ids'=>array(EnumOrderStatus::UnPayment,EnumOrderStatus::Complete,EnumOrderStatus::Cancel))); ?>)</li>
		    </a>
		    </ul>
		    <ul<?php if($filter['filter_order_status']==EnumOrderStatus::Complete) echo ' class="col-red"';?>><a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Complete);?>">
		        <li> 已完成</li>
		        <li> (<?php echo $this->getTotalOrderCount(EnumOrderStatus::Complete); ?>) </li></a>
		    </ul>
            <ul<?php if($filter['filter_order_status']==EnumOrderStatus::Cancel) echo ' class="col-red"';?>><a href="<?php echo $this->url->link('account/order&filter_order_status='.EnumOrderStatus::Cancel);?>">
            	<li>已取消</li>
              	<li> (<?php echo $this->getTotalOrderCount(EnumOrderStatus::Cancel); ?>) </li></a>
            </ul>
		</div>
		<?php if ($orders){ ?>
		<div class="uc-body col-gray">
		<?php foreach ($orders as $order) { ?>
         	<ul>
	        	<li class="fz-16 ">
	            	<span><a href="<?php echo $order['view']; ?>"><?php echo $column_order_id; ?>#<?php echo $order['order_id']; ?></a></span>
	            	<span>
	            		<a href="<?php echo $order['view']; ?>" class="pull-right arrow-right"></a>
	            		<a href="<?php echo $order['view']; ?>" class="col-red pull-right"><?php echo $order['status']; ?></a>
	            	</span> 
	          	</li>
          		<li class="fz-13 plist">
          		<?php foreach($order['products'] as $product){?>
             		<div>
             			<span>
             				<a href="<?php echo $product['href'];?>"><?php echo $product['name'];?></a>
             			</span>
             			<span class="pull-right price"><?php if($product['promotion_price']){ echo $product['promotion_price'].'<font class="text-delete">'. $product['price'].'</font>';}else{echo $product['price'];}?></span>
             			<span class="pull-right">X<?php echo $product['quantity'];?></span>
             		</div>
             	<?php } ?>
          		</li>
          		<li><span class="fz-16 col-red"><?php echo $column_total; ?>：<?php echo $order['total']; ?></span><span class="fz-13 col-gray pull-right"><?php if($order[shipping_point_id]) echo '时间'; else echo '时间';?><?php if($order[shipping_point_id]) echo $order['pdate']; else echo '约'.date('Y-m-d H:i',strtotime($order['shipping_time'])-3600).'-'.date('H:i',strtotime($order['shipping_time']));?></span></li>
         	</ul>
         	<?php } ?>
		  
		</div>
		<?php }?>
		<div class="text-center uc-foot col-gray fz-13">
		    <span class="col-red"><?php echo $pagination; ?></span>
		</div>
	</div>

<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>