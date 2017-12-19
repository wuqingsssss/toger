<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
    <ul class="order_p_list" id="payment-method">
        <?php foreach ($payment_methods as $payment_method) { ?>
        <li><label>
            <div style="width:10%">
                <img class="o_pay_img" src="<?php echo HTTP_CATALOG.$tplpath;?>images/<?php echo $payment_method['code']; ?>.png" alt="<?php echo $payment_method['code']; ?>"/>
            </div>
            <div style="width:70%; display:inline-block;"><span class="fm-15"><?php echo $payment_method['title']; ?></span>
                 <span class="fm-10 col-gray"><?php echo $payment_method['description']; ?></span>
            </div>
            <div style="width:10%; float:right; display:inline-block;">
            <?php if ($payment_method['code'] == $payment_code &&  $payment_code!='') { ?>
            <input type="radio" class="payment_select" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" class="payment_select" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
            <?php } ?>
            <i class="o_pay_state icon icon-ok-circled" for="<?php echo $payment_method['code']; ?>"></i>            
            </div>
            </label>
         </li>
        <?php } ?>
    </ul>
<?php } ?> 
