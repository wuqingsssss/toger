<!-- 公共头开始 -->
<?php if ($header_type != 'weixin'){?>
<?php if ($header_type=='app'){?>
<div class="bg-green" style="height:15px;">
</div>
<?php }?>
<div id = "m-header">
<div id="header">
    <div class="pull-left">
    <?php if(isset($navtop['left'])) {echo $navtop['left'];}else{ ?>
    <a class="return" href="javascript:_.go();"></a>
    <?php }?>
    </div>
    <div class="pull-right">
    <?php if(isset($navtop['right']) && $right_show == 1) {echo $navtop['right']; }?>
    </div>
    <div class="text-center">
    <?php if(isset($navtop['center'])) echo $navtop['center']; else{ ?>
<a class="locate fz-18"><?php echo $this->document->getTitle();?></a>
        <?php }?>
    </div>
</div>
</div>
<?php }?>
<!-- 公共头结束 -->