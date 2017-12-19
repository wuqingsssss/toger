<?php echo $header35; ?>
<link rel="stylesheet" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/common.css"/>
<link rel="stylesheet" rel="stylesheet" href="<?php echo HTTP_CATALOG.$tplpath;?>css/category.css"/>
<?php if($header_type =='app'){?>
 <style>
    #m-header>#header {
    height: 57px;
    }
    #m-header>#header>div {
    top: 13px;
    }
    
#m-category {
  padding-top: 57px;
}
#m-category > .cate-list > ul {
  padding: 57px 0 50px;
}
#m-category > .fixed-cate {
  position: fixed;
  z-index: 498;
  top: 57px;
  left: 0;
  width: 100%;
}
    </style>
<?php }?>
<?php if (($this->detect->is_weixin_browser ())){?>
<style>
#m-category {
  padding-top: 0px;
}
#m-category > .cate-list > ul {
  padding: 0px 0 50px;
}
#m-category > .fixed-cate {
  position: fixed;
  z-index: 498;
  top: 0px;
  left: 0;
  width: 100%;
}
</style>
<?php }?>

<!-- 公共头结束 -->
<?php echo $content_top;?>
<?php /*echo $this->getChild('module/navtop',array('navtop'=>array(
       'left'=>'',
       'center'=>'<a class="locate fz-18">'.$this->document->getTitle().'</a>',
       'right'=>'<a class="search" href="javascript:"></a>
                  <a class="message has-new" href="javascript:"></a>'
)));*/?>
<?php //echo $this->getChild('module/product_list_cat');?>
<?php //echo $this->getChild('module/navbar');?>
<?php echo $content_bottom;?>
<script src="<?php echo HTTP_CATALOG.DIR_DIR.'view/theme/';?>mobilev35/js35/common.js"></script>
<?php echo $footer35; ?>