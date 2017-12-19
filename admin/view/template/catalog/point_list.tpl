<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:modify'))) { ?>
                <button onclick="location = '<?php echo $insert; ?>';"
                        class="btn btn-primary"><?php echo $button_insert; ?></button>
                     <!-- button onclick="$('form').submit();" class="btn"><?php echo $button_delete; ?></button-->
                      <?php foreach($status_options as $option) {?>
                       <button onclick="$('form').attr('action','<?php echo $updates ?>&status=<?php echo $option['value']; ?>');$('form').submit();" class="btn"><?php echo $option['name']; ?></button>
                      <?php }  ?>|
                      <button onclick="$('form').attr('action','<?php echo $updates ?>&status=-1');$('form').submit();" class="btn">更新到百度云</button>
                      <button onclick="$('form').attr('action','<?php echo $updates ?>&status=-2');$('form').submit();" class="btn">下载数据到本地</button>
                      <!-- button onclick="location ='<?php echo $host2yun ?>' ;" class="btn">更新到百度云</button-->
                      <!-- button onclick="location ='<?php echo $yun2host ?>';" class="btn">下载数据到本地</button-->
            <?php } ?>
        </div>
    </div>
    <div class="content">
        <form action="" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;"><input type="checkbox"
                                                                     onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                    </td>
                    <td class="left"><?php echo $column_cbd?></td>
                    <td class="left"><?php echo $column_name; ?></td>
                    <td class="left"><?php echo $column_newcode; ?></td>
                    <td class="left"><?php echo $column_code; ?></td>
                    <td class="left"><?php echo $column_customer_group; ?></td>
                    <td class="left"><?php echo $column_status; ?></td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter">
                    <td></td>
                    <td>
                        <select name="filter_cbd" class="span2">
                            <?php if ($filter_cbd == '0') { ?>
                                <option value="*" selected="selected">所有</option>
                        <?php } else { ?>
                                <option value="*">所有</option>
                        <?php } ?>
                            <?php foreach($cbd_options as $option) {?>
                                <option value="<?php echo $option['value']; ?>" <?php if($option['value']==$filter_cbd) {?>selected="selected"<?php } ?>><?php echo $option['name']; ?></option>
                            <?php }  ?>
                        </select>
                    </td>
                    <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>"/></td>
                    <td><input type="text" name="filter_point_code_new" value="<?php echo $filter_point_code_new; ?>"/></td>
                    <td><input type="text" name="filter_point_code" value="<?php echo $filter_point_code; ?>"/></td>
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
                            <option value="*">所有</option>
                            <?php foreach($status_options as $option) {?>
                                <option value="<?php echo $option['value']; ?>" <?php if(isset($filter_status)&&$option['value']==$filter_status) {?>selected="selected"<?php } ?>><?php echo $option['name']; ?></option>
                            <?php }  ?>
                        </select>
                    </td>
                    <td align="right"><a onclick="filter();"
                                         class="button"><span><?php echo $button_filter; ?></span></a>
                    </td>
                </tr>
                <?php if ($points) { ?>
                    <?php foreach ($points as $result) { ?>
                        <tr>
                            <td style="text-align: center;"><?php if ($result['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $result['point_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]"
                                           value="<?php echo $result['point_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left"><?php echo $result['cbd']; ?></td>
                            <td class="left"><?php echo $result['name']; ?></td>
                            <td class="left"><?php echo $result['point_code_new']; ?></td>
                             <td class="left"><?php echo $result['point_code']; ?></td>
                             <td class="left"><?php echo $result['customer_group']; ?></td>
                            <td class="left"><?php echo $result['status']; ?><?php if(!empty($result['status_bd']))echo '<img src="view/image/bdonline.png" sytle-"float:left;" />'; ?></td>
                            <td class="right"><?php foreach ($result['action'] as $action) { ?>
                                    <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:detail'))) { ?>
                                        [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                    <?php } ?>
                                <?php } ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>

<script type="text/javascript"><!--
$(document).keypress(function(event){  
    var keycode = (event.keyCode ? event.keyCode : event.which);  
    if(keycode == '13'){  
    	filter();      
    }  
});  
    function filter() {
        url = 'index.php?route=catalog/point&token=<?php echo $token; ?>';

        var filter_name = $('input[name=\'filter_name\']').attr('value');

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_point_code = $('input[name=\'filter_point_code\']').attr('value');

        if (filter_point_code) {
            url += '&filter_point_code=' + encodeURIComponent(filter_point_code);
        }

        var filter_point_code_new = $('input[name=\'filter_point_code_new\']').attr('value');

        if (filter_point_code_new) {
            url += '&filter_point_code_new=' + encodeURIComponent(filter_point_code_new);
        }

        var filter_cbd = $('select[name=\'filter_cbd\']').attr('value');

        if (filter_cbd != '*') {
            url += '&filter_cbd=' + encodeURIComponent(filter_cbd);
        }
        var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').attr('value');

        if (filter_customer_group_id != '*') {
            url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
        }
        var filter_status = $('select[name=\'filter_status\']').attr('value');

        if (filter_status != '*') {
            url += '&filter_status=' + encodeURIComponent(filter_status);
        }


        location = url;
    }
    //--></script>
