<div id="service">
  <div class="fore1 column">
	<dt>
		<b></b>
		<h3><?php echo get_information_group_name('001'); ?></h3>
	</dt>
	<?php $informations=getGroupInformationsByCode('001'); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li><a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
  <div class="fore2 column">
	<dt>
		<b></b>
		<h3><?php echo get_information_group_name('002'); ?></h3>
	</dt>
    <?php $informations=getGroupInformationsByCode('002'); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li><a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
  <div class="fore3 column">
	<dt>
		<b></b>
		<h3><?php echo get_information_group_name('003'); ?></h3>
	</dt>
    <?php $informations=getGroupInformationsByCode('003'); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li><a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
  <div class="fore4 column">
	<dt>
		<b></b>
		<h3><?php echo get_information_group_name('004'); ?></h3>
	</dt>
    <?php $informations=getGroupInformationsByCode('004'); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li>
        <a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>">
          <?php echo $result['name']; ?>
        </a>
      </li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
  <div class="fore5 column">
	<dt>
		<b></b>
		<h3><?php echo get_information_group_name('005'); ?></h3>
	</dt>
   <?php $informations=getGroupInformationsByCode('005'); if($informations) {?>
    <ul>
      <?php foreach($informations as $result) {?>
      <li><a href="<?php echo $result['href']; ?>" title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } ?>
  </div>
  
  <div class="clr"></div>
</div>