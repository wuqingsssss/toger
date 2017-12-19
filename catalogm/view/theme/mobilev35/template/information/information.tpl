<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/information.css" rel="stylesheet"/>
<!-- 公共头开始 -->
<?php echo $this->getChild('module/navtop',array('navtop'=>array(
'left'=>'<a class="return" href="javascript:_.go()"></a>',
'center'=>'<a class="locate fz-18">'.$heading_title.'</a>',
'right'=>''
)));?>
<!-- 公共头结束 -->
<div class="information">
<?php echo $description; ?>
</div>

<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>