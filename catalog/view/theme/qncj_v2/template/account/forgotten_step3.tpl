<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
    <div id="forgotten" class="content">
     <div class="pass_title">
     	<h1><?php echo $heading_title; ?></h1>
     </div>
     
     <div class="pass_left lt">
     	<?php echo $this->getChild('account/forgotten/step',3);?>
     	
		<div class="success"><?php echo $text_success; ?></div>
      </div>
      <?php echo $this->getChild('account/forgotten/service');?>
    </div>
  <?php echo $content_bottom; ?>
  </div>
<?php echo $footer; ?>