<?php if (isset($error_warning)&&$error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (isset($success)&&$success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
  <thead>
    <tr>
      <td class="left span2"><b><?php echo $column_date_added; ?></b></td>
      <td class="left span2" ><b><?php echo $column_discount_total; ?></b></td>
      <td class="left"><b><?php echo $column_discount_comment; ?></b></td>
      <td class="left span1"><b><?php echo $column_action; ?></b></td>
      
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr>
      <td class="left"><?php echo $history['date_added']; ?></td>
      <td class="left"><?php echo $history['total']; ?></td>
      <td class="left"><?php echo $history['comment']; ?></td>
      <td class="left">
      	<?php foreach ($history['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
         <?php } ?>
      </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>