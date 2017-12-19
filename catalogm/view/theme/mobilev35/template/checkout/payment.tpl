<!-- 公共头开始 -->
<?php echo $header;?>
<!-- 公共头结束 -->
<div class="module fm-16" id="m-payment">
   <div class="balance <?php if(!$payinfo['balance']['valid']){ echo 'blk-disabled';} ?>" id="balance-check">
        <span>账户余额: <span class="col-red" id="balance-value"><?php echo $payinfo['balance']['value'];?></span> 
        <label>
            <?php if ( $payinfo['balance']['selected'] == true &&   $payinfo['balance']['selected']!='') { ?>
            <input type="checkbox" class="balance_select" name="balance" value="balance" id="balance" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" class="balance_select" name="balance" value="balance" id="balance" />
            <?php } ?>
            <i class="icon icon-ok-squared col-green" for="balance"></i>                
        </label>
        </span>
    </div>
    <div class="balance"  id="balance-pay" <?php if(!$payinfo['balance']['selected']){echo 'style="display:none"';}?>>
        <span>余额减扣: <span class="col-red" id="balancepay-value"><?php echo $payinfo['balance']['pay'];?></span> 
        </span>
    
    </div>
  	<!-- 支付方式  START-->
    <div class="payment-method">
        <div class="order_total">还需支付:<span class="col-red" id="pay-value"><?php echo $payinfo['otherpay']['pay'];?></span></div>
       	<div class="order_p_con <?php if(!$payinfo['otherpay']['valid']){ echo "blk-disabled";} ?>" id="pay-select">		
            <?php echo $payment_methods; ?>    		 
        </div>
    </div>   
    <!-- 支付方式  END-->
    
    <div id="payment">
        <input type="button" href="javascript:" class="btn-submit btn btn-green btn-long" id="submit-pay" value= "立即支付"></div>
    </div>
</div>
