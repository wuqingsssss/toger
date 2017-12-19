<?php if (count($languages) > 1) { ?>
	 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		<div id="language">
		  <?php foreach ($languages as $index => $language) { ?>
		  <a class="<?php echo $language['code']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>').submit(); $(this).parent().parent().submit();">
		  	<?php echo $language['name']; ?>
		  </a>	
	  	  <?php if($index < (count($languages)-1)) {?>
	  	  /
	  	  <?php } ?> 	
		  <?php } ?>
		  <input type="hidden" name="language_code" value="" />
		  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		</div>
	  </form>
<?php } ?>