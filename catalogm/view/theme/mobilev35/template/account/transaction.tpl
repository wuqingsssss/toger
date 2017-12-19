<?php echo $header35; ?>
 <link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/uc.css" rel="stylesheet"/>
 <link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/transaction.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
'left'=>'<a class="return" href="javascript:_.go()"></a>',
'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
'right'=>''
)));?>
        <!-- 公共头结束 -->
<div id="uc_body">
    <div id="content" <?php if($error) {echo 'style="display:none"';}?>>
        <div class="text-center bg-white fz-13 col-gray uc-order-tab"><p><?php echo $text_total; ?><b class="col-red"> <?php echo $total; ?></b></p>
        </div>
        <div class="module with-bottom" id="m-func">
            <ul class="hor-list">
                <li>
                    <a href="javascript:addTransCode();"><i class="icon icon-exchange"></i>&nbsp;&nbsp;菜君币兑换</a>
                </li>
                <li>
                    <a href="index.php?route=account/transaction/charge"><i class="icon icon-charge"></i>&nbsp;&nbsp;充值</a>
                </li>
            </ul>
        </div>
    	
    	<div class="uc-body col-gray">
           
        <?php if ($transactions){ ?>
    	<?php foreach ($transactions as $transaction) { ?>
         	<ul>
            	<li class="fz-16 ">
                	<span><?php echo $transaction['date_added']; ?></span>
                	<?php if($transaction['order_id']){?>
                	<span class="pull-right price">订单号:#<?php echo $transaction['order_id']; ?></span>
                	<?php }elseif($transaction['reference']){?>
                	<span class="pull-right price">储值码:#<?php echo $transaction['reference']; ?></span>
                	<?php }?>
              	</li>
          		<li class="fz-13 plist">
          		
         			<span>
         				<?php echo $transaction['description'];?>
         			</span>
         			<span class="pull-right price col-red"><?php echo $transaction['amount']; ?></span>
             		
          		</li>
          	</ul>
            <?php } ?>
            <?php } else { ?>
            <div class="uc-body col-gray">
                <br/><br/>
                <span><?php echo $text_empty; ?></span>
            </div>
            <?php } ?>		  
    	</div>
    </div>
    <div id="exchange" class="module bg-body" <?php if(!$error) {echo 'style="display:none"';}?>>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="transcode">
            <div class="form-group">
                <div class="input-group">
                    <input id="input-code"  name="trans_code"  class="input-block" placeholder="请输入兑换码" type="text">
                </div>
            </div>
            <div>
                <a onclick="$('#transcode').submit();" class="btn-submit btn-green"><?php echo $button_add_code; ?></a>
            </div>
        </form>
    </div>
</div>
<?php if($error){?>
<div class="overlay-container" id="filter-transaction">
    <div class="overlay-content-container">
         <div class="overlay-content bg-white col-gray fz-18 text-center uc-body cancel">
            <ul>
              <li class="fz-16 plist ">
                    <span><?php echo $error;?></span>
               </li><li>
                    <span class="col-red "><a onclick="$('#filter-transaction').hide();">确定</a></span>
              </li>
             </ul>
        </div>
    </div>
</div>
<?php }?>

<<script type="text/javascript">
<!--
function addTransCode()
{
	$('#content').hide();
	$('#exchange').show();
}
//-->
</script>
<?php echo $footer35; ?>