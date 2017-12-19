<?php echo $header; ?>
<div id="header"  class="bar bar-header bar-positive">
	<a href="#menu" class="button button-icon icon ion-navicon"></a>
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
<div class="card">
 	 <div class="item item-divider">
	    <?php echo $text_total; ?><b> <?php echo $total; ?></b>
	  </div>
	  <div class="item item-text-wrap">
	   <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_description; ?></td>
        <td class="right"><?php echo $column_points; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($rewards) { ?>
      <?php foreach ($rewards  as $reward) { ?>
      <tr>
        <td class="left"><?php echo $reward['date_added']; ?></td>
        <td class="left"><?php if ($reward['order_id']) { ?>
          <a href="<?php echo $reward['href']; ?>"><?php echo $reward['description']; ?></a>
          <?php } else { ?>
          <?php echo $reward['description']; ?>
          <?php } ?></td>
        <td class="right"><?php echo $reward['points']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="center" colspan="5"><?php echo $text_empty; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php if($rewards) {?>
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php }?>
	  </div>
</div>
</div>

<?php echo $this->getChild('mobile/account/menu') ?>
<?php echo $footer; ?> 