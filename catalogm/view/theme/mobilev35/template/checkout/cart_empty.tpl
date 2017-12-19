<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/cart.css" rel="stylesheet"/>
<div class="module bg-body text-center" id="m-empty">
    <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/empty-girl.png"/>

    <div class="col-gray fz-15">您还没有添加商品哦，赶紧去逛逛吧</div>
    <div class="text-center">
        <a href="<?php echo HTTP_CATALOG?>index.php?route=common/home" class="btn btn-normal btn-green fz-15">去逛逛</a>
    </div>
</div>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/cart.js"></script>
