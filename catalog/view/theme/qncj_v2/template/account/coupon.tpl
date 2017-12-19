<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  
   <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="coupon">
	  <table class="form">
		<tr>
	  		<td class="left"><?php echo $text_add_coupon; ?></td>
	  		<td ><input type="text" name="coupon" value="" />
            </td>
	  		<td ><div class="left"><a onclick="$('#coupon').submit();" class="button"><span><?php echo $button_add_couopon; ?></span></a></div></td>
	  	</tr>
	  </table>
   </form>
   
    <?php if ($coupons) { ?>
	<table class="list">
		<thead>
				<tr >
					<td class="left"><?php echo $column_name; ?></td>
					<td ><?php echo $column_discount; ?></td>
					<!-- td ><?php echo $column_usage; ?></td--->
					<td ><?php echo $column_date_start; ?></td>
					<td ><?php echo $column_date_end; ?></td>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($coupons as $coupon) { ?>
				<tr>
					<td class="left"><?php echo $coupon['name']; ?></td>
					<td ><?php echo $coupon['discount']; ?></td>
					<!-- td ><?php echo $coupon['usage']; ?></td-->
					<td ><?php echo $coupon['date_add']; ?></td>
					<td ><?php echo $coupon['date_limit']; ?></td>
				</tr>
		<?php } ?>
	 </tbody>
	</table>
	<div class="pagination"><?php echo $pagination; ?></div>
	<?php } else{ ?>
	<div class="content"><?php echo $text_no_results; ?></div>
	<?php } ?>
			
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
<script type="text/javascript">
$(document).ready(function() {
  $(".list tr:even").addClass('even');
  $(".list tr:odd").addClass('odd');
});
</script>