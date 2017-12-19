<?php echo $header35; ?>
<link href="<?php echo HTTP_CATALOG.$tplpath;?>css/home.css" rel="stylesheet"/>
<?php echo $this->getChild('module/navtop');?>
<div id="content">
  <?php echo $this->getChild('common/breadcrumb'); ?>
  
  <div class="section">
  	<div class="top"><h1><?php echo $heading_title; ?></h1></div>
  	<div class="content">
  <?php if (!$products) { ?>
  <?php // echo $text_empty; ?>
  <?php } ?>
  <a id="share1">
  <?php if ($promotion&&$promotion['page_header']){
echo $promotion['page_header'];
}?>
</a>
<?php echo $this->getChild('module/sharebtn',array('btn_hide'=>'#share1','callback'=>'share_promotion_success'));?>
<?php echo $this->getChild('module/product_list');?>
 </div> 

 <?php echo $content_bottom; ?>
 <?php if ($promotion&&$promotion['page_footer']){
echo $promotion['page_footer'];
}?>
 </div>
 
 </div>
<script type="text/javascript">
 $(document).ready(function(){
	$('.display .grid').click(function(){ display('grid');});

	$('.display .list').click(function(){ display('list'); });
	
	if($('.product-grid').length > 0){
		view = $.cookie('display');
		
		if (view=='list') {
			display(view);
		}
	}
});
 //分享成功后处理
 function share_promotion_success(obj){
	 console.log(obj);
	 var url='index.php?route=promotion/promotion/share_success';
	 $.getJSON(url,
			   obj,
			 function(e) {
		 console.log(e);
		 if(!e.error)
			 {
			   if (e.redirect) {
				 window.location = e.redirect;
				}	    
			 }
    });    	
 }

</script>
<?php echo $this->getChild('module/navbar');?>
<?php echo $footer35; ?>