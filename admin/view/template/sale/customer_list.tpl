<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2> <?php echo $heading_title; ?></h2>
      <div class="buttons"><a onclick="$('form').attr('action', '<?php echo $approve; ?>'); $('form').submit();" class="btn btn-default"><span><?php echo $button_approve; ?></span></a> <a onclick="location = '<?php echo $insert; ?>'" class="btn  btn-primary"><span><?php echo $button_insert; ?></span></a> <a onclick="$('form').attr('action', '<?php echo $delete; ?>'); $('form').submit();" class="btn btn-danger"><span><?php echo $button_delete; ?></span></a></div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td><?php echo $column_name; ?></td>
              <td class='left'>注册电话</td>
              <td class="left"><?php if ($sort == 'c.email') { ?>
                <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                <?php } ?>
              </td>
               <td>用户名</td>
              <td class="left"><?php if ($sort == 'customer_group') { ?>
                <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'c.approved') { ?>
                <a href="<?php echo $sort_approved; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_approved; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_approved; ?>"><?php echo $column_approved; ?></a>
                <?php } ?></td>
              <!-- >td class="left"><?php if ($sort == 'c.ip') { ?>
                <a href="<?php echo $sort_ip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_ip; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_ip; ?>"><?php echo $column_ip; ?></a>
                <?php } ?></td -->
              <td class="left"><?php if ($sort == 'c.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?>
               </td>
              <td><?php echo $column_date_latest_login; ?></td>  
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
              <td><input type="text" name="filter_mobile" value="<?php echo $filter_mobile; ?>" /></td>
              <td><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" /></td>
              <td><input type="text" name="filter_username" value="<?php echo $filter_username; ?>"/></td>
              <td><select name="filter_customer_group_id" class="span2">
                  <option value="*"></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td><select name="filter_status" class="span2">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
              <td><select name="filter_approved" class="span2">
                  <option value="*"></option>
                  <?php if ($filter_approved) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_approved) && !$filter_approved) { ?>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select></td>
              <!--<td><input type="text" class="span2" name="filter_ip" value="<?php echo $filter_ip; ?>" /></td>-->
              <td><input type="text" class="span2" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" id="date" /></td>
              <td><input type="text" class="span2" name="filter_date_latest" value="<?php echo $filter_date_latest; ?>" size="12" id="date" /></td>
              <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
            </tr>
            <?php if ($customers) { ?>
            <?php foreach ($customers as $customer) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($customer['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
                <?php } ?></td>
              
              <td class="left"><?php echo $customer['name']; ?></td>
              <td class="left"><?php echo $customer['mobile']; ?></td>
              <td class="left"><?php echo $customer['email']; ?></td>
              <td class="left"><?php echo $customer['username']; ?></td>
              <td class="left"><?php echo $customer['customer_group']; ?>
              <?php if($customer['customer_group_id']==9) {?>
             	 ( <?php echo $customer['role']; ?> ) 
              <?php } ?>
              </td>
              <td class="left"><?php echo $customer['status']; ?></td>
              <td class="left"><?php echo $customer['approved']; ?></td>
<!--              <td class="left"><?php echo $customer['ip']; ?></td>-->
              <td class="left"><?php echo $customer['date_added']; ?></td>
              <td class="left"><?php echo $customer['date_latest_login']; ?></td>
              <td class="right">[ <a href="<?php echo $customer['login']; ?>" target="_blank"><?php echo $text_login; ?></a> ]
                <?php foreach ($customer['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/customer&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_mobile = $('input[name=\'filter_mobile\']').attr('value');
	
	if (filter_mobile) {
		url += '&filter_mobile=' + encodeURIComponent(filter_mobile);
	}
	
	var filter_email = $('input[name=\'filter_email\']').attr('value');
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').attr('value');
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').attr('value');
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	var filter_approved = $('select[name=\'filter_approved\']').attr('value');
	
	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}	
	
	var filter_ip = $('input[name=\'filter_ip\']').attr('value');
	
	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}
		
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

    var filter_date_latest = $('input[name=\'filter_date_latest\']').attr('value');
	
	if (filter_date_latest) {
		url += '&filter_date_latest=' + encodeURIComponent(filter_date_latest);
	}
	
	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
    $('#form input').keydown(function (e) {
        if (e.keyCode == 13) {
            filter();
        }
    });
	$('#date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
