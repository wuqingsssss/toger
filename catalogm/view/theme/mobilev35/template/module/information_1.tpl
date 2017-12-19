<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/stylesheet/help.css" />

<div class="helpMenu">
<?php foreach($section as $code) {?>
	<p><?php echo get_information_group_name($code); ?></p>
	<?php $informations=getGroupInformationsByCode($code); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li><a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
<?php } ?>
</div>
