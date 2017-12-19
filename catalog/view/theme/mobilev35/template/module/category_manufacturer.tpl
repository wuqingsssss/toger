 <?php if($manufacturers) {?>
          <ul>
          	<?php foreach($manufacturers as $result) {?>
          	<li>
          	<a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>">
          		<img src="<?php echo $result['thumb']; ?>" alt="<?php echo $result['name']; ?>" />
          	</a>
          	</li>
          	<?php } ?>
          </ul>
<?php } ?>