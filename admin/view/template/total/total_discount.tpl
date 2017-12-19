  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></button> <button onclick="location = '<?php echo $cancel; ?>';" class="btn"><?php echo $button_cancel; ?></button></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="total_discount_status">
                <?php if ($total_discount_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="total_discount_sort_order" value="<?php echo $total_discount_sort_order; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_step1; ?></td>
            <td><input type="text" name="total_discount_step1" value="<?php echo $total_discount_step1; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_discount1; ?></td>
            <td><input type="text" name="total_discount_discount1" value="<?php echo $total_discount_discount1; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_step2; ?></td>
            <td><input type="text" name="total_discount_step2" value="<?php echo $total_discount_step2; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_discount2; ?></td>
            <td><input type="text" name="total_discount_discount2" value="<?php echo $total_discount_discount2; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_step3; ?></td>
            <td><input type="text" name="total_discount_step3" value="<?php echo $total_discount_step3; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_discount3; ?></td>
            <td><input type="text" name="total_discount_discount3" value="<?php echo $total_discount_discount3; ?>" size="1" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_start_date; ?></td>
            <td><input class="date" type="text" name="total_discount_start_date"
                                   value="<?php echo(isset($total_discount_start_date) ? date('Y-m-d', strtotime($total_discount_start_date)) : ''); ?>"
                                   required=""/></td>
          </tr> 
          <tr>
            <td><?php echo $entry_end_date; ?></td>
            <td><input class="date" type="text" name="total_discount_end_date"
                                   value="<?php echo(isset($total_discount_end_date) ? date('Y-m-d', strtotime($total_discount_end_date)) : ''); ?>"
                                   required=""/></td>
          </tr>            
        </table>
      </form>
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    })
  </script>
        
