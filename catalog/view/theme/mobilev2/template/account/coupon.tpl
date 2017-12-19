<?php echo $header; ?>
<div  id="header" class="bar bar-header bar-positive">
	<a href="#menu" class="button button-icon icon ion-navicon"></a>
	<h1 class="title"><?php echo $heading_title; ?></h1>
</div>
<div id="content" class="content">
<div class="card">
  <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
 
   <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="coupon">
	  <table class="form">
		<tr>
	  		<td class="left"><?php echo $text_add_couopon; ?></td>
	  		<td ><input type="text" name="coupon" value=""  placeholder="请输入优惠券代码" />
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
					<!--td ><?php echo $column_usage; ?></td-->
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
	<?php echo $text_no_results; ?>
	<?php } ?>
	<?php echo $this->getChild('mobile/account/menu') ?>
	</div>		
</div>
<?php echo $footer; ?>
<script type="text/javascript">
$(document).ready(function() {
  $(".list tr:even").addClass('even');
  $(".list tr:odd").addClass('odd');
});
</script>