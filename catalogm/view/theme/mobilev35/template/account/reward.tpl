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
    <div id="content" class="text-center bg-white fz-13 col-gray uc-order-tab">
        <p><?php echo $text_total; ?>
        <b class="col-red"> <?php echo $total; ?>
        </b></p>
    </div>
    
	<?php if ($rewards){ ?>
	<div class="uc-body col-gray">
	<?php foreach ($rewards as $reward) { ?>
     	<ul>
        	<li class="fz-16 ">
            	<span><?php echo $reward['date_added']; ?></span>
            	<?php if($reward['order_id']){?>
            	<span class="pull-right price">订单号:#<?php echo $reward['order_id']; ?></span>
            	<?php }?>
          	</li>
      		<li class="fz-13 plist">
      		
     			<span>
     				<?php echo $reward['description'];?>
     			</span>
     			<span class="pull-right price col-red"><?php echo $reward['points']; ?></span>
         		
      		</li>
      	</ul>
        <?php } ?>
		  
	</div>
    <?php } else { ?>
    <div class="uc-body col-gray">
        <br/><br/>
        <span><?php echo $text_empty; ?></span>
    </div>
    <?php } ?>?>
</div>

  <?php if($rewards) {?>
  <div class="pagination fz-13"><?php echo $pagination; ?></div>
  <?php }?>
	

<?php echo $footer35; ?> 