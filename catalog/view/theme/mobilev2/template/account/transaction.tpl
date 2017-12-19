<?php echo $header; ?>
<div  id="header" class="bar bar-header bar-positive">
	<a href="#menu" class="button button-icon icon ion-navicon"></a>
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content"><?php echo $content_top; ?>
   <br/>
  <p><?php echo $text_total; ?><b> <?php echo $total; ?></b>.</p>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_description; ?></td>
        <td class="right"><?php echo $column_amount; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions  as $transaction) { ?>
      <tr>
        <td class="left"><?php echo $transaction['date_added']; ?></td>
        <td class="left"><?php echo $transaction['description']; ?></td>
        <td class="right"><?php echo $transaction['amount']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="center" colspan="5"><?php echo $text_empty; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
	<div class="pagination"><?php echo $pagination; ?></div>
	<?php echo $this->getChild('mobile/account/menu') ?>
	</div>		
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>