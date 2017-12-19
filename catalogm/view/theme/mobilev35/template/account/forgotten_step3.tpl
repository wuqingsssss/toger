<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/cart.css" rel="stylesheet"/>
<div class="module bg-body text-center" id="m-empty">
    <img src="<?php echo HTTP_CATALOG.$tplpath;?>images/empty-girl.png"/>

    <div class="col-gray fz-15">修改成功,请重新登录</div>
    <div class="text-center">
        <a href="<?php echo HTTP_CATALOG?>index.php?route=account/login" class="btn btn-normal btn-green fz-15">重新登录</a>
    </div>
</div>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>
<script src="<?php echo HTTP_CATALOG.$tplpath;?>js35/cart.js"></script>