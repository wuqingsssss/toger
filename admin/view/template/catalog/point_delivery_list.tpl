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
                      <?php foreach($status_options as $option) {?>
                       <button onclick="$('form').attr('action','<?php echo $updates ?>&status=<?php echo $option['value']; ?>');$('form').submit();" class="btn"><?php echo $option['name']; ?></button>
                      <?php }  ?>
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
                    <td class="left"><?php echo $column_zone_name; ?></td>
                    <td class="left"><?php echo $column_shipping_code?></td>
                    <td class="left"><?php echo $column_region_name; ?></td>
                    <td class="left"><?php echo $column_name; ?></td>
                    <td class="left"><?php echo $column_telephone; ?></td>
                    <td class="left"><?php echo $column_address; ?></td>
                     <td class="left"><?php echo $column_sort_order; ?></td>
                    <td class="left"><?php echo $column_status; ?></td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter">
                    <td></td>
                    <td>
                    <select name="filter_zone_id" id="zone_id">
            </select><input type="text" name="filter_zone_name" value="<?php echo $filter_zone_name; ?>"/></td>
                    <td>
                      <select name="filter_code" class="span2">
                            <option value="">全部</option>
                            <?php foreach ($delivery as $key => $item) { ?>
                                <option
                                    value="<?php echo $item['code']; ?>" <?php if ($item['code'] == $filter_code) { ?> selected="selected" <?php } ?> ><?php echo $item['title']; ?></option>
                            <?php } ?>
                       </select>
                    </td>
                    <td><input type="text" name="filter_region_name" value="<?php echo $filter_region_name; ?>"/></td>
                    <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>"/></td>
                    <td><input type="text" name="filter_telephone" value="<?php echo $filter_telephone; ?>"/></td>
                     <td><input type="text" name="filter_address" value="<?php echo $filter_address; ?>"/></td>
                      <td></td>
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
                <?php if ($deliverys) { ?>
                    <?php foreach ($deliverys as $result) { ?>
                        <tr>
                            <td style="text-align: center;"><?php if ($result['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $result['delivery_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]"
                                           value="<?php echo $result['delivery_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left"><?php echo $result['zone_name']; ?></td>
                            <td class="left"><?php echo $result['code']; ?></td>
                            <td class="left"><span style="cursor: pointer;width:14px;" id="d-<?php echo $result['delivery_id'];?>" onclick="$('.d-<?php echo $result['delivery_id'];?>.d-child').toggle(100,function(){if($('.d-<?php echo $result['delivery_id'];?>.d-child').is(':hidden'))$('#d-<?php echo $result['delivery_id'];?>').html('+');else $('#d-<?php echo $result['delivery_id'];?>').html('-');});">-</span><?php echo $result['region_name']; ?>[<?php echo $result['region_id'].']['.$result['region_code']; ?>]</td>
                            <td class="left"><?php echo $result['name']; ?></td>
                            <td class="left"><?php echo $result['telephone']; ?></td>
                            <td class="left"><?php echo $result['address']; ?></td>
                            <td class="left"><?php echo $result['sort_order']; ?></td>
                            <td class="left"><?php echo $result['status']; ?></td>
                            <td class="right"><?php foreach ($result['action'] as $action) { ?>
                                    <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:detail'))) { ?>
                                        [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                    <?php } ?>
                                <?php } ?></td>
                        </tr>
                        
                         <?php foreach ($result['children'] as $child) { ?>
                        <tr class="d-<?php echo $result['delivery_id'];?> d-child">
                            <td style="text-align: center;"><?php if ($child['selected']) { ?>
                                    <input type="checkbox" name="selected[]" value="<?php echo $child['delivery_id']; ?>"
                                           checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]"
                                           value="<?php echo $child['delivery_id']; ?>"/>
                                <?php } ?></td>
                            <td class="left"><?php echo $child['zone_name']; ?></td>
                            <td class="left"><?php echo $child['code']; ?></td>
                            <td class="left"><?php echo $child['region_name']; ?>[<?php echo $child['region_id'].']['.$child['region_code']; ?>]</td>
                            <td class="left"><?php echo $child['name']; ?></td>
                            <td class="left"><?php echo $child['telephone']; ?></td>
                            <td class="left"><?php echo $child['address']; ?></td>
                            <td class="left"><?php echo $child['sort_order']; ?></td>
                            <td class="left"><?php echo $child['status']; ?></td>
                            <td class="right"><?php foreach ($child['action'] as $action) { ?>
                                    <?php if ($this->user->permitOr(array('super_admin', 'self_help_points:detail'))) { ?>
                                        [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                                    <?php } ?>
                                <?php } ?></td>
                        </tr>
  
                    <?php } ?>

                    <?php } ?>
                    
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
              <script type="text/javascript"><!--
		  $('select[name=\'filter_zone_id\']').load('index.php?route=localisation/city/zone&token=<?php echo $token; ?>&country_id=44&zone_id=685');
	//--></script> 
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
        url = 'index.php?route=catalog/pointdelivery&token=<?php echo $token; ?>';

        var filter_name = $('input[name=\'filter_name\']').attr('value');

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_zone_name = $('input[name=\'filter_zone_name\']').attr('value');

        if (filter_zone_name) {
            url += '&filter_zone_name=' + encodeURIComponent(filter_zone_name);
        }

        var filter_code = $('select[name=\'filter_code\']').attr('value');

        if (filter_code) {
            url += '&filter_code=' + encodeURIComponent(filter_code);
        }

        var filter_region_name = $('input[name=\'filter_region_name\']').attr('value');

        if (filter_region_name) {
            url += '&filter_region_name=' + encodeURIComponent(filter_region_name);
        }
        
        
        var filter_telephone = $('input[name=\'filter_telephone\']').attr('value');

        if (filter_telephone) {
            url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
        }
        var filter_address = $('input[name=\'filter_address\']').attr('value');

        if (filter_address) {
            url += '&filter_address=' + encodeURIComponent(filter_address);
        }

        var filter_zone_id = $('select[name=\'filter_zone_id\']').attr('value');

        if (filter_zone_id != '*') {
            url += '&filter_zone_id=' + encodeURIComponent(filter_zone_id);
        }

        var filter_status = $('select[name=\'filter_status\']').attr('value');

        if (filter_status != '*') {
            url += '&filter_status=' + encodeURIComponent(filter_status);
        }


        location = url;
    }
    //--></script>
