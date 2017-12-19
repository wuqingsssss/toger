  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title_rule; ?></h2>
      <div class="buttons"><button id="saveButton" class="btn btn-primary"><?php echo $button_save; ?></button> <button onclick="location = '<?php echo $cancel; ?>';" class="btn"><?php echo $button_cancel; ?></button></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a></div>
      <form action="<?php echo $action; ?>" enctype="multipart/form-data" method="post"  id="form">
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $text_start_date; ?></td>
              <td><input type="text" name="start_date" value="<?php echo $zerobuyInfo['start_date']; ?>" size="40" class="datetime"/>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $text_end_date; ?></td>
              <td><input type="text" name="end_date" value="<?php echo $zerobuyInfo['end_date']; ?>" size="40" class="datetime"/>
            </tr>
            
            <tr>
              <td><span class="required"></span> <?php echo $column_can_use_quantity; ?></td>
              <td><input type="text" name="can_use_quantity" value="<?php echo $product['quantity']; ?>" size="40" />
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $column_use_quantity; ?></td>
              <td><input type="text" name="use_quantity" value="<?php echo $zerobuyInfo['use_quantity']; ?>" size="40" />
            </tr>
             <tr>
              <td><span class="required"></span> <?php echo $column_group; ?></td>
              <td><input type="text" name="group" value="<?php echo $zerobuyInfo['group']; ?>" size="40" />
            </tr>
               <tr>
              <td><span class="required"></span> <?php echo $column_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $zerobuyInfo['sort_order']; ?>" size="40" />
            </tr>
          </table>
        </div>
        
       <input type="hidden" name="pr_id" value="<?php  echo $pr_id;?>" />
       <input type="hidden" name="product_id" value="<?php  echo $product_id;?>" />
       <input type="hidden" name="pr_code" value="<?php  echo $zerobuyInfo['pr_code'];?>" />
      </form>
    </div>
  </div>
 <script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 

<script type="text/javascript"><!--
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});

//--></script> 
<script type="text/javascript">
<!--
$(document).ready(
		function(){
			$('#saveButton').bind('click',function(){
				var reg = new RegExp("^[0-9]*$");
			    var values = $('input=[name=\"use_quantity\"]').val();
			    var canValues = $('input=[name=\"can_use_quantity\"]').val();
			 	if(!reg.test(values)){
			 	 	alert('<?php echo $text_num_type;?>');
				}else if(parseInt(values)>parseInt(canValues))
				{
					alert('<?php echo $text_num_compare;?>');
				}else{
					$('#form').submit();
				}

		});
		});
//-->
</script>

