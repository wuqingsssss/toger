<!-- 公共头开始 -->
<?php if($header_type =='app'){?>
<div style="height: 15px;" class="bg-green"> </div>
<?php }?>
<div id="header" pg-name="<?php echo isset($setting['name'])?$setting['name']:'';?>"  <?php if($header_type =="weixin"){ echo "style='display:none;'";}?>>
  
    <div class="pull-left">
    <?php if(isset($setting['left']['href'])) { ?>
        <a class="return" href="<?php echo $setting['left']['href']; ?>"></a>
    <?php }else{ ?>
        <a class="return" href="javascript:_.go();"></a>
    <?php }?>
    </div>
    <div class="pull-right hidden">
    <?php if(isset($setting['right']['text'])) {echo $setting['right']['text']; }?>
    </div>
    <div class="text-center">
    <?php if(isset($setting['center']['text'])) { ?>
        <a id="title" class="locate fz-18"><?php echo $setting['center']['text']; ?> </a>
    <?php }else{ ?>
        <a id="title" class="locate fz-18"><?php echo $this->document->getTitle();?></a>
    <?php }?>
    </div>
   
</div>
<!-- 公共头结束 -->