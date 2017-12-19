<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
    <ul class="order_p_list" id="payment-method">
        <?php foreach ($payment_methods as $payment_method) { ?>
        <li>
            <img class="o_pay_img" src="<?php echo HTTP_CATALOG.$tplpath;?>images/<?php echo $payment_method['code']; ?>.png" alt="<?php echo $payment_method['code']; ?>"/>
            <span><?php echo $payment_method['title']; ?></span>
        
            <?php if ($payment_method['code'] == $payment_code &&  $payment_code!='') { ?>
            <input class="o_pay_state" type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
            <?php } else { ?>
            <input class="o_pay_state" type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
            <?php } ?>
         </li>
        <?php } ?>
    </ul>
<?php } ?>



