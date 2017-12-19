<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
	<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  	</div>
  	  <div class="coda-slider-wrapper">
        <div id="tabs" class="coda-nav">
            <a href="#coda-nav-1" class="button-left">銀幕報道</a>
            <a href="#coda-nav-2" class="button-right">雜誌報道</a>
          </div>
        <div class="coda-slider" id="coda-slider-1">
          <div class="panel-container" >
          <div id="coda-nav-1"  class="panel">
            <div class="panel-wrapper">
		      <ul id="list" class="image-grid twocol">
		        <!--3 Column Portfolio-->
		        <?php foreach($media_banners as $result) {?>
		        <li> 
		          <!--Item-->
		          <h3 style="color:#000"><?php echo $result['title']; ?></h3>
		          <div class="hover" style="opacity: 1;">
		          <span>
		          <a href="<?php echo $result['link']; ?>" rel="fancybox" class="fancybox" title="">
		          <img src="<?php echo $result['image']; ?>" width="300" height="175" class="frame" alt="貴婦甜品PK賽" style="opacity: 1;">
		          </a>
		          </span>
		          </div>
<!--		          <h5></h5>-->
		        </li>
		        <?php } ?>
		        
		      </ul>
            </div>
          </div>
          <div id="coda-nav-2" class="panel"> 
            <!--Quote Panel-->
            <div class="panel-wrapper">
                    <ul id="list" class="image-grid twocol">
		        	<?php foreach($magazine_banners as $result) {?>
				        <li> 
				          <!--Item-->
				          <h3 style="color:#000"><?php echo $result['title']; ?></h3>
				          <div class="hover" style="opacity: 1;">
				          <span>
				          <a href="<?php echo $result['link']; ?>" rel="fancybox" title="" class="colorbox">
				          <img src="<?php echo $result['image']; ?>" width="300" height="175" class="frame" alt="貴婦甜品PK賽" style="opacity: 1;">
				          </a>
				          </span>
				          </div>
		<!--		          <h5></h5>-->
				        </li>
				      <?php } ?>
			        	<li> 
			      	</ul>
            </div>
          </div>
          </div>
          <!--Contact Panel-->
        </div>
      </div>
	  <div class="clear"></div>
	  
<?php echo $content_bottom; ?>
</div>
<script>
$(function() {
  $('#tabs a').tabs();
});
</script>


<script type="text/javascript"><!--
$(document).ready(function(){
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5
	});
});
//--></script> 

<?php echo $footer; ?> 