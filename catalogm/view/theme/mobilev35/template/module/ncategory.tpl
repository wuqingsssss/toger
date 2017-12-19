<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
  <?php if (isset($this->request->get['headlines'])) { ?>
	  <a href="<?php echo $headlines; ?>" style="font-weight: bold; color: #333; display: block; text-decoration: none; font-size: 13px; text-align: left; border-bottom: 2px solid #eee;"><?php echo $button_headlines; ?></a>
	  <?php } else { ?>
      <a href="<?php echo $headlines; ?>" style="font-weight: bold; color: #666; display: block; text-decoration: none; font-size: 13px; text-align: left; border-bottom: 2px solid #eee;"><?php echo $button_headlines; ?></a>
  <?php } ?>
  <?php if ($ncategories) { ?>
    <h4 style="color: #666; border-bottom: 2px solid #eee; margin-top: 5px; margin-bottom: 5px;"><?php echo $heading_ncat; ?></h4>
    <div class="box-category">
      <ul>
        <?php foreach ($ncategories as $ncategory) { ?>
        <li>
          <?php if ($ncategory['ncategory_id'] == $ncategory_id) { ?>
          <a href="<?php echo $ncategory['href']; ?>" class="active"><?php echo $ncategory['name']; ?></a>
          <?php } else { ?>
          <a href="<?php echo $ncategory['href']; ?>"><?php echo $ncategory['name']; ?></a>
          <?php } ?>
          <?php if ($ncategory['children']) { ?>
          <ul>
            <?php foreach ($ncategory['children'] as $child) { ?>
            <li>
              <?php if ($child['ncategory_id'] == $child_id) { ?>
              <a href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['name']; ?></a>
              <?php } else { ?>
              <a href="<?php echo $child['href']; ?>"> - <?php echo $child['name']; ?></a>
              <?php } ?>
            </li>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php } ?>
      </ul>
    </div>
<?php } ?>	
	<div id="artsearch">
   <h4 style="color: #666; border-bottom: 2px solid #eee; margin-top: 5px; margin-bottom: 5px;"><?php echo $head_search; ?></h4>
      <?php if ($filter_name) { ?>
      <input type="text" name="filter_artname" value="<?php echo $filter_name; ?>" />
      <?php } else { ?>
      <input type="text" name="filter_artname" value="<?php echo $artkey; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
      <?php } ?>
	  <a id="button-artsearch" class="button" style="margin-top: 4px;"><span><?php echo $button_search; ?></span></a>
  </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#artsearch input[name=\'filter_artname\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-artsearch').trigger('click');
	}
});

$('#button-artsearch').bind('click', function() {
	url = 'index.php?route=news/search';
	
	var filter_artname = $('#artsearch input[name=\'filter_artname\']').attr('value');
	
	if (filter_artname) {
		url += '&filter_artname=' + encodeURIComponent(filter_artname);
	}

	location = url;
});
//--></script> 