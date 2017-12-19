<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title_store_setting; ?></h2>
      <div class="buttons"><button class="btn btn-primary" onclick="$('#form').submit();" ><?php echo $button_save; ?></button>   <button class="btn"  onclick="location = '<?php echo $cancel; ?>';" ><?php echo $button_cancel; ?></button></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
      <a href="#tab-general"><?php echo $tab_general; ?></a>
      <a href="#tab-store"><?php echo $tab_store; ?></a>
      <a href="#tab-local"><?php echo $tab_local; ?></a>
      <a href="#tab-option"><?php echo $tab_option; ?></a>
      <a href="#tab-order-setting"><?php echo $tab_order_setting; ?></a>
      <a href="#tab-image"><?php echo $tab_image; ?></a>
      <a href="#tab-website"><?php echo $tab_website; ?></a>
      <a href="#tab-custom"><?php echo $tab_custom; ?></a>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-custom">
        	<table class="form">
        	<tr>
              <td><?php echo $entry_donation_limit; ?></td>
              <td>
              <input type="text" name="config_donation_limit" value="<?php echo $config_donation_limit; ?>" />
              </td>
            </tr>
        	<tr>
              <td><?php echo $entry_register_reward; ?></td>
              <td>
              <input type="text" name="config_register_reward" value="<?php echo $config_register_reward; ?>" />
              </td>
            </tr>
                <tr>
                    <td>注册赠送优惠券<span class="help">请在右侧填写注册后要赠送的优惠券的代码</span></td>
                    <td>
                        <input type="text" name="config_register_coupon" value="<?php echo $config_register_coupon; ?>" />
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab-order-setting">
        <table class="form">
         <tr>
              <td><?php echo $entry_customer_order_captcha; ?></td>
              <td><?php if ($config_customer_order_captcha) { ?>
                <input type="radio" name="config_customer_order_captcha" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_order_captcha" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_order_captcha" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_order_captcha" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_order_vcode; ?></td>
              <td><?php if ($config_customer_order_vcode) { ?>
                <input type="radio" name="config_customer_order_vcode" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_order_vcode" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_order_vcode" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_order_vcode" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
        	<tr>
              <td><?php echo $entry_order_status; ?></td>
              <td><select name="config_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_nopay_status; ?></td>
              <td><select name="config_order_nopay_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_order_nopay_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_complete_status; ?></td>
              <td><select name="config_complete_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_complete_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>            
            <tr>
              <td><?php echo $entry_return_status; ?></td>
              <td><select name="config_order_refund_status_id">
                  <?php foreach ($order_statuses as $result) { ?>
                  <?php if ($result['order_status_id'] == $config_order_refund_status_id) { ?>
                  <option value="<?php echo $result['order_status_id']; ?>" selected="selected"><?php echo $result['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $result['order_status_id']; ?>"><?php echo $result['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_cancel_status; ?></td>
              <td><select name="config_order_cancel_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_order_cancel_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_shipped_status; ?></td>
              <td><select name="config_order_shipped_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_order_shipped_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_order_received_status; ?></td>
              <td><select name="config_order_received_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $config_order_received_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </td>
            </tr>      
            </table>
        </div>
        <div id="tab-website">
        	<table class="form">
        	<tr>
              <td><?php echo $entry_online_status; ?></td>
              <td>
                <input type="radio" name="config_online_status" value="1" <?php if ($config_online_status) { ?>checked="checked"<?php }?> />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_online_status" value="0" <?php if (!$config_online_status) { ?>checked="checked"<?php }?> />
                <?php echo $text_no; ?>
              </td>
            </tr>
            </table>
        </div>
        
        <div id="tab-general">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input type="text" name="config_name" value="<?php echo $config_name; ?>"  class="span6" />
                <?php if ($error_name){?>
                <span class="help-inline error"><?php echo $error_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_owner; ?></td>
              <td><input type="text" name="config_owner" value="<?php echo $config_owner; ?>" class="span6" />
                <?php if ($error_owner) { ?>
                <span class="help-inline error"><?php echo $error_owner; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_address; ?></td>
              <td><input type="text" class="span6" name="config_address" value="<?php echo $config_address; ?>"  />
                <?php if ($error_address) { ?>
                <span class="help-inline error"><?php echo $error_address; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_email; ?></td>
              <td><input type="text" name="config_email" value="<?php echo $config_email; ?>" />
                <?php if ($error_email) { ?>
                <span class="help-inline error"><?php echo $error_email; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
              <td><input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" />
                <?php if ($error_telephone) { ?>
                <span class="help-inline error"><?php echo $error_telephone; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_fax; ?></td>
              <td><input type="text" name="config_fax" value="<?php echo $config_fax; ?>" /></td>
            </tr>
          </table>
        </div>
        <div id="tab-store">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_title; ?></td>
              <td><input type="text" name="config_title" value="<?php echo $config_title; ?>" size="40" />
                <?php if ($error_title) { ?>
                <span class="help-inline error"><?php echo $error_title; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_meta_description; ?></td>
              <td><textarea name="config_meta_description" cols="40" rows="5"><?php echo $config_meta_description; ?></textarea></td>
            </tr>
             <tr>
	            <td><?php echo $entry_meta_keyword; ?></td>
	            <td><textarea name="config_meta_keyword" cols="40" rows="5"><?php echo $config_meta_keyword; ?></textarea></td>
            </tr>
            
             <tr>
              <td><?php echo $entry_share_title; ?></td>
              <td><textarea name="config_share_title" cols="40" rows="5"><?php echo $config_share_title; ?></textarea></td>
            </tr>
             <tr>
              <td><?php echo $entry_share_desc; ?></td>
              <td><textarea name="config_share_desc" cols="40" rows="5"><?php echo $config_share_desc; ?></textarea></td>
            </tr>
             <tr>
              <td><?php echo $entry_share_linkurl; ?></td>
              <td><textarea name="config_share_linkurl" cols="40" rows="5"><?php echo $config_share_linkurl; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_share_image; ?></td>
              <td><input type="hidden" name="config_share_image" value="<?php echo $config_share_image; ?>" id="share-image" />
                <img src="<?php echo $share_image; ?>" alt="" id="preview-share-image" class="image" onclick="image_upload('share-image', 'preview-share-image');" />
                <div>
                <a onclick="image_upload('share-image', 'preview-share-image');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  <a onclick="$('#preview-share-image').attr('src', '<?php echo $no_image; ?>'); 
                  $('#share-image').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
                </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_template; ?></td>
              <td><select name="config_template" onchange="$('#template').load('index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value));">
                  <?php foreach ($templates as $template) { ?>
                  <?php if ($template == $config_template) { ?>
                  <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td></td>
              <td id="template"></td>
            </tr>
            <tr>
              <td><?php echo $entry_layout; ?></td>
              <td><select name="config_layout_id">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $config_layout_id) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-local">
          <table class="form">
            <tr>
              <td><?php echo $entry_country; ?></td>
              <td><select name="config_country_id" onchange="$('select[name=\'config_zone_id\']').load('index.php?route=setting/setting/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $config_zone_id; ?>');">
                  <?php foreach ($countries as $country) { ?>
                  <?php if ($country['country_id'] == $config_country_id) { ?>
                  <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_zone; ?></td>
              <td><select name="config_zone_id">
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_language; ?></td>
              <td><select name="config_language">
                  <?php foreach ($languages as $language) { ?>
                  <?php if ($language['code'] == $config_language) { ?>
                  <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
         
            <tr>
              <td><?php echo $entry_currency; ?></td>
              <td><select name="config_currency">
                  <?php foreach ($currencies as $currency) { ?>
                  <?php if ($currency['code'] == $config_currency) { ?>
                  <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
          </table>
        </div>
        <div id="tab-option">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_catalog_limit; ?></td>
              <td><input type="text" name="config_catalog_limit" value="<?php echo $config_catalog_limit; ?>" size="3" />
                <?php if ($error_catalog_limit) { ?>
                <span class="help-inline error"><?php echo $error_catalog_limit; ?></span>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_tax; ?></td>
              <td><?php if ($config_tax) { ?>
                <input type="radio" name="config_tax" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_tax" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_tax" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_tax" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
          
            <tr>
              <td><?php echo $entry_customer_group; ?></td>
              <td><select name="config_customer_group_id">
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_price; ?></td>
              <td><?php if ($config_customer_price) { ?>
                <input type="radio" name="config_customer_price" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_price" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_price" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_price" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
             <tr>
              <td><?php echo $entry_customer_lr_captcha; ?></td>
              <td><?php if ($config_customer_lr_captcha) { ?>
                <input type="radio" name="config_customer_lr_captcha" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_lr_captcha" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_lr_captcha" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_lr_captcha" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
             <tr>
              <td><?php echo $entry_customer_lr_vcode; ?></td>
              <td><?php if ($config_customer_lr_vcode) { ?>
                <input type="radio" name="config_customer_lr_vcode" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_lr_vcode" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_lr_vcode" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_lr_vcode" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_customer_approval; ?></td>
              <td><?php if ($config_customer_approval) { ?>
                <input type="radio" name="config_customer_approval" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_approval" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_customer_approval" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_customer_approval" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
          
            <tr>
              <td><?php echo $entry_account; ?></td>
              <td><select name="config_account_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $config_account_id) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_checkout; ?></td>
              <td><select name="config_checkout_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($informations as $information) { ?>
                  <?php if ($information['information_id'] == $config_checkout_id) { ?>
                  <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_stock_display; ?></td>
              <td><?php if ($config_stock_display) { ?>
                <input type="radio" name="config_stock_display" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_display" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_stock_display" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_display" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_stock_checkout; ?></td>
              <td><?php if ($config_stock_checkout) { ?>
                <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_checkout" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="config_stock_checkout" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
             
            
          </table>
           <input type="hidden" name="config_guest_checkout" value="0"/>
        </div>
        <div id="tab-image">
          <table class="form">
            <tr>
              <td><?php echo $entry_logo; ?></td>
              <td><input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="logo" />
                <img src="<?php echo $logo; ?>" alt="" id="preview-logo" class="image" onclick="image_upload('logo', 'preview-logo');" />
                <div>
                <a onclick="image_upload('logo', 'preview-logo');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  <a onclick="$('#preview-logo').attr('src', '<?php echo $no_image; ?>'); 
                  $('#logo').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
                </td>
            </tr>
            <tr>
              <td><?php echo $entry_icon; ?></td>
              <td><input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="icon" />
                <img src="<?php echo $icon; ?>" alt="" id="preview-icon" class="image" onclick="image_upload('icon', 'preview-icon');" />
                <div>
                <a onclick="image_upload('icon', 'preview-icon');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  <a onclick="$('#preview-icon').attr('src', '<?php echo $no_image; ?>'); 
                  $('#icon').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
                </td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_thumb; ?></td>
              <td><input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" size="3" />
                x
                <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" size="3" />
                <?php if ($error_image_thumb) { ?>
                <span class="help-inline error"><?php echo $error_image_thumb; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_popup; ?></td>
              <td><input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" size="3" />
                x
                <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" size="3" />
                <?php if ($error_image_popup) { ?>
                <span class="help-inline error"><?php echo $error_image_popup; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_product; ?></td>
              <td><input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" size="3" />
                x
                <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" size="3" />
                <?php if ($error_image_product) { ?>
                <span class="help-inline error"><?php echo $error_image_product; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_category; ?></td>
              <td><input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" size="3" />
                x
                <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" size="3" />
                <?php if ($error_image_category) { ?>
                <span class="help-inline error"><?php echo $error_image_category; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_manufacturer; ?></td>
              <td><input type="text" name="config_image_manufacturer_width" value="<?php echo $config_image_manufacturer_width; ?>" size="3" />
                x
                <input type="text" name="config_image_manufacturer_height" value="<?php echo $config_image_manufacturer_height; ?>" size="3" />
                <?php if ($error_image_manufacturer) { ?>
                <span class="help-inline error"><?php echo $error_image_manufacturer; ?></span>
                <?php } ?></td>
            </tr>            
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_additional; ?></td>
              <td><input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" size="3" />
                x
                <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" size="3" />
                <?php if ($error_image_additional) { ?>
                <span class="help-inline error"><?php echo $error_image_additional; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_related; ?></td>
              <td><input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" size="3" />
                x
                <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" size="3" />
                <?php if ($error_image_related) { ?>
                <span class="help-inline error"><?php echo $error_image_related; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_compare; ?></td>
              <td><input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" size="3" />
                x
                <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" size="3" />
                <?php if ($error_image_compare) { ?>
                <span class="help-inline error"><?php echo $error_image_compare; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_wishlist; ?></td>
              <td><input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" size="3" />
                x
                <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" size="3" />
                <?php if ($error_image_wishlist) { ?>
                <span class="help-inline error"><?php echo $error_image_wishlist; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_image_cart; ?></td>
              <td><input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" size="3" />
                x
                <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" size="3" />
                <?php if ($error_image_cart) { ?>
                <span class="help-inline error"><?php echo $error_image_cart; ?></span>
                <?php } ?></td>
            </tr>
          </table>
         
        </div>
        </form>
 </div>
</div>
<script type="text/javascript"><!--
$('#template').load('index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent($('select[name=\'config_template\']').attr('value')));

$('select[name=\'config_zone_id\']').load('index.php?route=common/localisation/zone&token=<?php echo $token; ?>&country_id=<?php echo $config_country_id; ?>&zone_id=<?php echo $config_zone_id; ?>');
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script> 
