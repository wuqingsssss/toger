<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG . $tplpath; ?>/css/ticket.css" rel="stylesheet"/>
<?php $class = array('ticket-ture', 'get_roseo ticket-ture','get_blue ticket-ture')?>
<!-- 公共头开始 -->

<?php  echo $header;?>

<!-- 公共头结束 -->

<!-- 优惠券头图开始 -->
<div class="module banner banner-default">
    <img src="<?php echo HTTP_CATALOG . $tplpath; ?>image/ticket/ticket_top.jpg">
</div>
<!-- 优惠券头图结束 -->

<div class="fz-12" id="get-ticket">
    <ul class="ticket">
		<?php if($list){?>
		<?php foreach($list as $li){?>
		
		<?php if($li['info']['flag'] == -2){?>
		<li class="get_grey ticket-already">
		<?php }else if($li['info']['flag'] == -1){?>
		<li class="get_grey ticket-already get">
		<?php }else{?>
		<li class="<?php echo $class[rand(0, 2)]?>" onclick="get('<?php echo $li['info']['coupon_id']?>','<?php echo $li['info']['code']?>',$(this))">
			<?php } ?>
			<div class="ticket_dashed"></div>
			<div class="ticketbox">
				<div>
					<span class="money">¥<i><?php echo round($li['info']['discount'], 2)?></i></span>
					<span class="condition"><i>&nbsp;&nbsp;优惠券</i><br/>【<?php echo mb_substr($li['info']['name'],0,8,'utf-8')?>】</span>
					<div class="clearfix"></div>
				</div>
				<div class="time">有效期：<?php echo $li['start_time']?>至<?php echo $li['end_time']?></div>
				<div class="ticketget" ></div>
			</div>
		</li>
		<?php } ?>
		<?php }else{?>
		暂时没有可领取优惠券~
		<?php } ?>
    </ul>
</div>

<!-- 公共js库引入 -->
<?php echo $this->getChild('module/navbar'); ?>

<script src="<?php echo HTTP_CATALOG . $tplpath; ?>js35/ticket.js"></script>
<?php echo $footer35; ?>

<!-- 公共底部结束 -->

<!-- 页面js引入-->