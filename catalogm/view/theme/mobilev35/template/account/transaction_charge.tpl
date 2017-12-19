<?php echo $header35; ?>
 <link href="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/css/transaction.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
'left'=>'<a class="return" href="javascript:_.go()"></a>',
'center'=>'<a class="locate fz-18">'.$heading_charge.'</a>',
'right'=>''
)));?>
        <!-- 公共头结束 -->

<div id="content">
    <div class="module" id="m-charge">
        <ul>
        <?php if($products){$active=1;  foreach ($products as $product){?>
        <?php if($active){$active=0; ?>
            <li class="active" data-id="<?php echo $product['product_id']?>">
        <?php }else{?>
            <li data-id="<?php echo $product['product_id']?>">
        <?php }?>
                <div><?php echo $product['price']?>元</div>
                <div>价值<?php echo $product['value']?>币</div>
            </li>
        <?php }}?>
        </ul>
        <div>
            <a href="javascript:" id="select-total" class="btn-submit btn btn-green">立即充值</a>
        </div>
        <div  id="info" class="col-gray fz-12">
            <div>使用提示：</div>
            <div>1.支付成功后，金额会直接充到您的余额中</div>
            <div>2.余额不支持提现</div>
            <div>3.一元等于一菜君币</div>
        </div>
    </div>

</div>

<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/charge.js"></script>
<?php echo $footer35; ?>