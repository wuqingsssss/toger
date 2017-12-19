<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<div id="content"><?php echo $content_top; ?>
  <?php echo $this->getChild('common/breadcrumb'); ?>
  
  <h1><?php echo $heading_title;?></h1>
  <?php if (isset($success) && $success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
 <?php if($consulations) {?>
 <div id="consult">
 	<?php foreach($consulations as $result) {?>
 	<div class="item">
 		<dl class="ask">
 			<dt><b></b><?php echo $text_content;?></dt>
 			<dd>
 				<div class="content-ask"><?php echo $result['content']; ?></div>
 				<div class="date-ask"><?php echo $result['date_added']; ?></div>
 			</dd>
 		</dl>
 		<dl class="answer">
 			<dt><b></b><?php echo $text_reply;?></dt>
 			<dd>
 				<div class="content-answer">
 					<?php echo $result['reply']; ?>
 				</div>
 				<div class="date-answer"><?php echo $result['date_modified']; ?></div>
 			</dd>
 		</dl>
 	</div>
 	<?php } ?>
 	<div class="pagination"><?php echo $pagination; ?></div>
 </div>
 <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?> 