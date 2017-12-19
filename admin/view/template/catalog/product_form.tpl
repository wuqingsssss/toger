<style>div a{padding: 5px;} div a.selected{background-color:white;}</style>
<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
<div class="heading">
    <h2><?php echo $heading_title; ?></h2>
    <div class="buttons"><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a> 
        <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a><!-- <a  id="saveToTemplet" class="btn btn-primary"><span>保存为模板</span></a> -->
    </div>
</div>
<div class="content">
    <div id="tabs" class="htabs">
        <a href="#tab-general"><?php echo $tab_general; ?></a>
        <a href="#tab-description"><?php echo $tab_description; ?></a>
        <a href="#tab-data"><?php echo $tab_data; ?></a>
        <a href="#tab-seo"><?php echo $tab_seo_setting; ?></a>
        <a href="#tab-links"><?php echo $tab_links; ?></a>
        <a href="#tab-image"><?php echo $tab_image; ?></a>
        <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
        <a href="#tab-option"><?php echo $tab_option; ?></a>
        <!-- a href="#tab-discount"><?php echo $tab_discount; ?></a-->
        <a href="#tab-special"><?php echo $tab_special; ?></a>
        <a href="#tab-reward"><?php echo $tab_reward; ?></a>
        <a href="#tab-design"><?php echo $tab_design; ?></a>
        <!-- 
        <a href="#tab-templet"><?php echo $tab_templet; ?></a>
        -->
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
            <?php if(COUNT($languages)>1) {?>
            <div id="languages" class="htabs">
                <?php foreach ($languages as $language) { ?>
                <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
            </div>
            <?php } ?>
            <?php foreach ($languages as $language) { ?>
            <div id="language<?php echo $language['language_id']; ?>">
                <table class="form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_name; ?></td>
                        <td><input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" class="span8" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" />
                          <?php if (isset($error_name[$language['language_id']])) { ?>
                          <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                          <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_subtitle; ?></td>
                        <td>
                        	<input type="text" name="product_description[<?php echo $language['language_id']; ?>][subtitle]" class="span8" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['subtitle'] : ''; ?>" />
                        </td>
                    </tr>
             
                    <tr>
                        <td><?php echo $entry_delivery_time; ?></td>
                        <td>
                            <input type="text" name="product_description[<?php echo $language['language_id']; ?>][delivery]" class="span8" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['delivery'] : ''; ?>" />
                        </td>
                    </tr>

                    
                    <tr>
                        <td><?php echo $entry_tag; ?></td>
                        <td><input type="text" name="product_tag[<?php echo $language['language_id']; ?>]" value="<?php echo isset($product_tag[$language['language_id']]) ? $product_tag[$language['language_id']] : ''; ?>" size="80" /></td>
                    </tr>
                  
                    <tr style="display:none;">
                        <td><?php echo $entry_package; ?></td>
                        <td>
                            <textarea name="product_description[<?php echo $language['language_id']; ?>][package]" id="package<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['package'] : ''; ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <?php } ?>
            <div><hr style="height:1px;border:none;border-top:1px dashed #0066CC;" /></div>
            <table class="form">  
                                    <tr>
                        <td>商品类别</td>
                        <td>
                         <?php foreach(EnumProdType::getProdTypes() as $item){ ?>
                           <input type="radio" name="prod_type" value="<?php echo $item[value];?>"<?php if($prod_type==$item[value]){ echo ' checked="checked"';} ?> /><?php echo $item[name];?>&nbsp&nbsp&nbsp&nbsp  
                          <?php }?>
</td>
                    </tr> 
              <tr>
                    <td><?php echo $entry_upc; ?></td>
                    <td><input type="text" name="upc" value="<?php echo $upc; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_sku; ?></td>
                    <td><input type="text" name="sku" value="<?php echo $sku; ?>" /></td>
                </tr>

                <tr>
                    <td><?php echo $entry_price; ?></td>
                    <td><input type="text" name="price" value="<?php echo $price; ?>" /></td>
                </tr>          
                <tr>
                    <td><?php echo $entry_garnish; ?></td>
                    <td>
                        <input type="text" name="garnish"  value="<?php echo $garnish; ?>" />
                    </td>
                </tr>
	          
                <tr>
                    <td><?php echo $entry_cooking_time; ?></td>
                    <td>
                        <input type="text" name="cooking_time"  value="<?php echo $cooking_time; ?>" />
                    </td>
                </tr>

                <tr>
                    <td><?php echo $entry_calorie; ?></td>
                    <td>
                        <input type="text" name="calorie"  value="<?php echo $calorie; ?>" />
                    </td>
                </tr>
                
                <tr>
                    <td><?php echo $entry_follow; ?></td>
                    <td>
                        <input type="text" name="follow"  value="<?php echo $follow; ?>" />
                    </td>
                </tr>

              
	            <tr>
                    <td><?php echo $entry_image; ?></td>
                    <td><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
                        <img src="<?php echo $preview; ?>"  style="width:100px;height:100px;" alt="" id="preview" class="image" onclick="image_upload('image', 'preview');" />
                        <div>
                            <a onclick="image_upload('image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>'); 
                            $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
                        </div>
                    </td>
	            </tr>
	            <tr>
	               <td><?php echo $entry_model; ?></td>
	               <td>
	                   <input type="text" name="model" value="<?php echo $model; ?>" />
	               </td>
	            </tr>
	            <tr>
	               <td><?php echo $entry_date_available; ?></td>
	               <td><input type="text" name="date_available" value="<?php echo $date_available; ?>" size="12" class="date" /></td>
	            </tr>
	              <tr>
	               <td><?php echo $entry_date_unavailable; ?></td>
	               <td><input type="text" name="date_unavailable" value="<?php echo $date_unavailable; ?>" size="12" class="date" /></td>
	            </tr>
	             
           
                <tr>
                    <td><?php echo $entry_packing; ?></td>
                    <td><input type="text" name="packing_type" value="<?php echo $packing_type; ?>" /></td>
                </tr>                
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><select name="status">
                        <?php if ($status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
	               <td><?php echo $entry_featured; ?></td>
	               <td class="form-inline">
    	                <?php if ($featured) { ?>
    	                <label><input type="radio" name="featured" value="1" checked="checked" /> <?php echo $text_yes; ?>&nbsp;&nbsp;</label>
    	                <label><input type="radio" name="featured" value="0" /> <?php echo $text_no; ?></label>
    	                
    	                <?php } else { ?>
    	                <label><input type="radio" name="featured" value="1"  /> <?php echo $text_yes; ?>&nbsp;&nbsp;</label>
    	                <label><input type="radio" name="featured" value="0" checked="checked" /> <?php echo $text_no; ?></label>
    	                <?php } ?>
	               </td>
	            </tr>
                <tr>
                    <td><?php echo $entry_donation; ?></td>
                    <td class="form-inline">
                        <?php if ($donation) { ?>
                        <label><input type="radio" name="donation" value="1" checked="checked" /> <?php echo $text_yes; ?>&nbsp;&nbsp;</label>
                        <label><input type="radio" name="donation" value="0" /> <?php echo $text_no; ?></label>
                        
                        <?php } else { ?>
                        <label><input type="radio" name="donation" value="1"  /> <?php echo $text_yes; ?>&nbsp;&nbsp;</label>
                        <label><input type="radio" name="donation" value="0" checked="checked" /> <?php echo $text_no; ?></label>
                        <?php } ?>
                    </td>
                </tr>
             
            <tr>
              <td><?php echo $entry_shipping; ?></td>
              <td><?php if ($shipping) { ?>
                <input type="radio" name="shipping" value="1" checked="checked" />
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" />
                <?php echo $text_no; ?>
                <?php } else { ?>
                <input type="radio" name="shipping" value="1" />
                <?php echo $text_yes; ?>
                <input type="radio" name="shipping" value="0" checked="checked" />
                <?php echo $text_no; ?>
                <?php } ?></td>
            </tr>
                 <tr>
              <td><?php echo $entry_minimum; ?></td>
              <td><input type="text" name="minimum" value="<?php echo $minimum; ?>" size="2" /></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_subtract; ?></td>
              <td><select name="subtract">
                  <?php if ($subtract) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            
	            <tr>
	               <td><?php echo $entry_quantity; ?></td>
	               <td><input type="text" name="quantity" value="<?php echo $quantity; ?>" size="2" /></td>
	            </tr>
            <tr>
              <td><?php echo $entry_stock_status; ?></td>
              <td><select name="stock_status_id">
                  <?php foreach ($stock_statuses as $stock_status) { ?>
                  <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                  <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            </table>
        </div>
        <div id="tab-description">
         <?php if(COUNT($languages)>1) {?>
            <div id="languagesdescription" class="htabs">
                <?php foreach ($languages as $language) { ?>
                <a href="#languagedescription<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
            </div>
            <?php } ?>
            <?php foreach ($languages as $language) { ?>        
            <div id="languagedescription<?php echo $language['language_id']; ?>">
                <table class="form">
                       <tr>
                        <td><?php echo $entry_unit; ?></td>
                        <td>
                            <input type="text" name="product_description[<?php echo $language['language_id']; ?>][unit]" class="span8" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['unit'] : ''; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_origin; ?></td>
                        <td>
                            <input type="text" name="product_description[<?php echo $language['language_id']; ?>][origin]" class="span8" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['origin'] : ''; ?>" />
                        </td>
                    </tr>
                    
                    <tr>
                        <td><?php echo $entry_storage; ?></td>
                        <td>
                            <input type="text" name="product_description[<?php echo $language['language_id']; ?>][storage]" class="span8" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['storage'] : ''; ?>" />
                        </td>
                    </tr>
					<tr>
						<td>详情首图</td>
						<td><input type="hidden" name="product_description[<?php echo $language['language_id']; ?>][des_img]" 
								   value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['des_img'] : ''; ?>" id="des_img" />
							<img src="<?php echo $product_description[$language['language_id']]['des_img']; ?>"  style="width:100px;height:100px;" alt="" id="des_view" class="image" onclick="image_upload('des_img', 'des_view');" />
							<div>
								<a onclick="image_upload('des_img', 'des_view');">浏览</a>&nbsp;&nbsp;|&nbsp;&nbsp;
								<a onclick="$('#des_view').attr('src', '<?php echo $no_image; ?>'); 
							$('#des_img').attr('value', '');">清除</a>
							</div>
						</td>
					</tr>
                    <tr>
                        <td><?php echo $entry_description; ?></td>
                        <td><textarea name="product_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_cooking; ?></td>
                        <td><textarea name="product_description[<?php echo $language['language_id']; ?>][cooking]" id="cooking<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['cooking'] : ''; ?></textarea></td>
                    
                    </tr>
                    <tr style="display:none;">
                        <td><?php echo $entry_package; ?></td>
                        <td>
                            <textarea name="product_description[<?php echo $language['language_id']; ?>][package]" id="package<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['package'] : ''; ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
        <div id="tab-data">
          <table class="form">
         
                 <tr>
                    <td><?php echo $entry_dimension; ?></td>
                    <td><input type="text" name="length" value="<?php echo $length; ?>" size="4" />
                        <input type="text" name="width" value="<?php echo $width; ?>" size="4" />
                        <input type="text" name="height" value="<?php echo $height; ?>" size="4" />
                    </td>
                </tr>
                <tr>
                    <td><?php echo $entry_length; ?></td>
                    <td><select name="length_class_id">
                    <?php foreach ($length_classes as $length_class) { ?>
                        <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                        <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select></td>
                </tr>
                <tr>
                    <td><?php echo $entry_weight; ?></td>
                    <td><input type="text" name="weight" value="<?php echo $weight; ?>" /></td>
                </tr>
                <tr>
                    <td><?php echo $entry_weight_class; ?></td>
                    <td><select name="weight_class_id">
                        <?php foreach ($weight_classes as $weight_class) { ?>
                        <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                        <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                        <?php } ?>
                        <?php } ?>
                        </select>
                    </td>
                </tr>
            <tr>
              <td><?php echo $entry_tax_class; ?></td>
              <td><select name="tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
           
            <tr>
              <td><?php echo $entry_location; ?></td>
              <td><input type="text" name="location" value="<?php echo $location; ?>" /></td>
            </tr>
           <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="2" /></td>
            </tr>
          
          </table>
        </div>
     	<div id="tab-seo">
           <table class="form">
          	 <tr>
              <td><?php echo $entry_keyword; ?></td>
              <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
             </tr>
             <tr>
              <td><?php echo $entry_link_url; ?></td>
              <td><input type="text" name="link_url" value="<?php echo $link_url; ?>" /></td>
             </tr>
          </table>
          <?php if(COUNT($languages)>1) {?>
           <div id="seo_languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#seo_language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
          <?php } ?>
          <?php foreach ($languages as $language) { ?>
          <div id="seo_language<?php echo $language['language_id']; ?>">
            <table class="form">
             <tr>
                <td><?php echo $entry_meta_title; ?></td>
                <td><input type="text" class="span6"  name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" /></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_description; ?></td>
                <td><textarea class="span6"  name="product_description[<?php echo $language['language_id']; ?>][meta_description]" cols="85" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
              </tr>
              <tr>
                <td><?php echo $entry_meta_keyword; ?></td>
                <td><textarea class="span6"  name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="85" rows="5"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
              </tr>
               <tr><td colspan="2">分享设置</td></tr>
                 <tr>
                        <td>分享标题</td>
                        <td><input name="product_description[<?php echo $language['language_id']; ?>][share_title]" value="<?php echo $product_description[$language['language_id']][share_title]; ?>"/>
                          </td>
                    </tr>
                     <tr>
                        <td>分享描述</td>
                        <td><input name="product_description[<?php echo $language['language_id']; ?>][share_desc]" value="<?php echo $product_description[$language['language_id']][share_desc]; ?>"/>
                       </td>
                    </tr>
                   
         	
            </table>
          </div>
		 <?php } ?>
		  <table class="form">
          	   <tr>
                        <td>原始分享链接<br/>系统将自动生成短链接</td>
                        <td><input name="share_link" value="<?php echo $share_link; ?>"/><?php echo $share_short_link; ?>
                       </td>
                    </tr>
                     <tr>
                     <td>分享图标</td>
                     <td valign="top"><input type="hidden" name="share_image" value="<?php echo $share_image; ?>" id="share_image" />
                     <img src="<?php echo $share_image_preview; ?>" alt="" id="share_image_preview" class="image" onclick="image_upload('share_image', 'share_image_preview');" />
                    <div>
	                <a onclick="image_upload('share_image', 'share_image_preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#share_image_preview').attr('src', '<?php echo $no_image; ?>'); $('#share_image_share_image').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
            </tr>
          </table>
       </div>
        <div id="tab-links">
          <table class="form">
            <tr>
              <td><?php echo $entry_manufacturer; ?></td>
              <td><select name="manufacturer_id">
                  <option value="0" selected="selected"><?php echo $text_none; ?></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                  <?php if ($manufacturer['manufacturer_id'] == $manufacturer_id) { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>" selected="selected"><?php echo $manufacturer['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_category; ?></td>
              <td><div class="scrollbox" style="height:500px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($category['category_id'], $product_category)) { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                    <?php echo $category['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_category[]" value="<?php echo $category['category_id']; ?>" />
                    <?php echo $category['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
            </tr>
            
            <tr>
              <td class="top"><?php echo $entry_product_tag; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_tags as $result) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($result['value'], $product_tag)) { ?>
                    <input type="checkbox" name="product_icon[]" value="<?php echo $result['value']; ?>" checked="checked" />
                    <?php echo $result['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_icon[]" value="<?php echo $result['value']; ?>" />
                    <?php echo $result['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
            </tr>
            <tr>
              <td><?php echo $entry_store; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'even'; ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array(0, $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="0" checked="checked" />
                    <?php echo $text_default; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="0" />
                    <?php echo $text_default; ?>
                    <?php } ?>
                  </div>
                  <?php foreach ($stores as $store) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($store['store_id'], $product_store)) { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                    <?php echo $store['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
                    <?php echo $store['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_download; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($downloads as $download) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($download['download_id'], $product_download)) { ?>
                    <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
                    <?php echo $download['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="product_download[]" value="<?php echo $download['download_id']; ?>" />
                    <?php echo $download['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_related; ?></td>
              <td><input type="text" name="related" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div id="product-related" class="scrollbox_auto">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_related as $product_related) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-related<?php echo $product_related['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product_related['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                  </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td><?php echo $entry_combine; ?></td>
              <td>
                  <?php if (!empty($combine)) { ?>
                      <input type="radio" id="combineRd1" name="combine" value="1" onclick="combineSelect()" checked="checked" />
                      <?php echo $text_yes; ?>
                      <input type="radio" id="combineRd2" name="combine" value="0" onclick="combineSelect()"/>
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" id="combineRd1" name="combine" value="1" onclick="combineSelect()"/>
                      <?php echo $text_yes; ?>
                      <input type="radio" id="combineRd2" name="combine" value="0" onclick="combineSelect()" checked="checked" />
                      <?php echo $text_no; ?>
                  <?php } ?>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>
                <div id="combine">
                  <span><?php echo $text_combine_product; ?></span>
                  <input type="text"  name="combine_input" value="" style="width:400px;" />
                  <br/><br/>
                  <div id="product-combine" class="scrollbox_auto" >
                  <?php $class = 'odd'; ?>
                  <?php foreach ($product_combine as $product) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-combine<?php echo $product['product_id']; ?>" class="<?php echo $class; ?>"> <?php echo $product['name']; ?><img src="view/image/delete.png" />
                    <input type="hidden" name="product_combine[]" value="<?php echo $product['product_id']; ?>" />
                  </div>
                  <?php } ?>
                  </div>
                </div>
              </td>
            </tr>
            
            <tr>
              <td><?php echo $entry_coupon; ?></td>
              <td><input type="text" name="coupon_input" value="" /></td>
            </tr>
            
              <tr>
              <td>&nbsp;</td>
              <td>
                <div id="coupon">
                  <div id="product-coupon" class="scrollbox_auto" >
                  <?php $class = 'odd'; $i=0;?>
                  <?php foreach ($product_coupon as $key=> $coupon) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-coupon<?php echo $coupon['coupon_id']; ?>" class="<?php echo $class; ?>"> <?php echo $coupon['name']; ?><img src="view/image/delete.png" />
                  <input type="hidden" name="product_coupon[<?php echo $coupon['coupon_id']; ?>][coupon_id]" value="<?php echo $coupon['coupon_id']; ?>"/>
                    |数量:<input type="text" name="product_coupon[<?php echo $coupon['coupon_id']; ?>][coupon_num]" value="<?php echo $coupon['coupon_num']; ?>" style="width:30px;height:12px;"/>
                    |克隆:<input type="checkbox" name="product_coupon[<?php echo $coupon['coupon_id']; ?>][is_tpl]" value="1" />
                  </div>
                  <?php } ?>
                  </div>
                </div>
              </td>
            </tr>
                      <tr>
              <td><?php echo $entry_trans_code; ?></td>
              <td><input type="text" name="trans_code_input" value="" /></td>
            </tr>
            
              <tr>
              <td>&nbsp;</td>
              <td>
                <div id="trans_code">
                  <div id="product-trans_code" class="scrollbox_auto" >
                  <?php $class = 'odd'; $i=0;?>
                  <?php foreach ($product_trans_code as $key=> $trans_code) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div id="product-trans_code<?php echo $trans_code['coupon_id']; ?>" class="<?php echo $class; ?>"> <?php echo $trans_code['trans_code'].'|'.(int)$trans_code['value']; ?><img src="view/image/delete.png" />
                  <input type="hidden" name="product_trans_code[<?php echo $trans_code['trans_code_id']; ?>][trans_code_id]" value="<?php echo $trans_code['trans_code_id']; ?>"/>
                    |数量:<input type="text" name="product_trans_code[<?php echo $trans_code['trans_code_id']; ?>][num]" value="<?php echo $trans_code['num']; ?>" style="width:30px;height:12px;"/>
                    |克隆:<input type="checkbox" name="product_trans_code[<?php echo $trans_code['trans_code_id']; ?>][is_tpl]" value="1" <?php if($trans_code['is_tpl']=='1'){echo 'checked="true"';} ?> />
                  </div>
                  <?php } ?>
                  </div>
                </div>
              </td>
            </tr> 
            
          </table>
        </div>
        
        <div id="tab-attribute">
          <table id="attribute" class="list">
            <thead>
              <tr>
                <td class="left span2"><?php echo $entry_attribute; ?></td>
                <td class="left"><?php echo $entry_text; ?></td>
                <td class="span2"></td>
              </tr>
            </thead>
            <?php $attribute_row = 0; ?>
            <?php foreach ($product_attributes as $product_attribute) { ?>
            <tbody id="attribute-row<?php echo $attribute_row; ?>">
              <tr>
                <td class="left"><input   type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" />
                  <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
                <td class="left"><?php foreach ($languages as $language) { ?>
                  <textarea class="span6"  name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
                  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                  <?php } ?></td>
                <td class="left"><a onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $attribute_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="left"><a onclick="addAttribute();" class="button"><span><?php echo $button_add_attribute; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- 
        <div id="tab-templet">
        	 <table class="form">
              <tr>
                <td>模板名称</td>
                <td><input type="text" name="templet_name" class="span8" />
                  </td>
              </tr>
              </table>
         
        	
          <table id="templet" class="list">
            <thead>
              <tr>
              	<td  class="left ">模板名称</td>
                <td class="left ">产品名</td>
                <td class="left">价格</td>
                <td class="span2">操作</td>
              </tr>
            </thead>
             <tbody id="attribute-r">
             <?php foreach ($templets as $templet) { ?>
             	 <tr>
             	 <td class="left"><?php echo $templet['templet_info']['templet_name']; ?></td>
                <td class="left"><?php echo $templet['name']; ?></td>
                <td class="left"><?php echo $templet['templet_info']['price']; ?></td>
                <td class="left">  <?php foreach ($templet['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
              </tr>
            <?php }?>
             
            </tbody>
            	
            
          </table>
        </div>
         -->
        
        <div id="tab-option">
          <div id="vtab-option" class="vtabs">
            <?php $option_row = 0; ?>
            <?php foreach ($product_options as $product_option) { ?>
            <a href="#tab-option-<?php echo $option_row; ?>" id="option-<?php echo $option_row; ?>"><?php echo $product_option['name']; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="$('#vtabs a:first').trigger('click'); $('#option-<?php echo $option_row; ?>').remove(); $('#tab-option-<?php echo $option_row; ?>').remove(); return false;" /></a>
            <?php $option_row++; ?>
            <?php } ?>
            <span id="option-add">
            <input name="option" value="" style="width: 130px;" />
            &nbsp;<img src="view/image/add.png" alt="<?php echo $button_add_option; ?>" title="<?php echo $button_add_option; ?>" /></span></div>
          <?php $option_row = 0; ?>
          <?php $option_value_row = 0; ?>
          <?php foreach ($product_options as $product_option) { ?>
          <div id="tab-option-<?php echo $option_row; ?>" class="vtabs-content">
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
            <input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
           <?php if(false) {?>
            <div class="attention">添加或删除选项组将重新计算库存！您可以添加/删除单选项，并已保存的产品后，可以看到您的库存。</div>
            <?php } ?>
            
            <table class="form">
              <tr>
                <td><?php echo $entry_required; ?></td>
                <td><select name="product_option[<?php echo $option_row; ?>][required]">
                    <?php if ($product_option['required']) { ?>
                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                    <option value="0"><?php echo $text_no; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_yes; ?></option>
                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                    <?php } ?>
                  </select></td>
              </tr>
              <?php if ($product_option['type'] == 'text') { ?>
              <tr>
                <td><?php echo $entry_option_value; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'textarea') { ?>
              <tr>
                <td><?php echo $entry_option_value; ?></td>
                <td><textarea class="span6" name="product_option[<?php echo $option_row; ?>][option_value]" cols="40" rows="5"><?php echo $product_option['option_value']; ?></textarea></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'file') { ?>
              <tr style="display: none;">
                <td><?php echo $entry_option_value; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'date') { ?>
              <tr>
                <td><?php echo $entry_option_value; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="date" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'datetime') { ?>
              <tr>
                <td><?php echo $entry_option_value; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="datetime" /></td>
              </tr>
              <?php } ?>
              <?php if ($product_option['type'] == 'time') { ?>
              <tr>
                <td><?php echo $entry_option_value; ?></td>
                <td><input type="text" name="product_option[<?php echo $option_row; ?>][option_value]" value="<?php echo $product_option['option_value']; ?>" class="time" /></td>
              </tr>
              <?php } ?>
            </table>
            <?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox') { ?>
            <table id="option-value<?php echo $option_row; ?>" class="list">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_option_value; ?></td>
                  <td class="right"><?php echo $entry_quantity; ?></td>
                  <td class="left"><?php echo $entry_subtract; ?></td>
                  <td class="right"><?php echo $entry_price; ?></td>
                  <td class="right"><?php echo $entry_option_points; ?></td>
                  <td class="right"><?php echo $entry_weight; ?></td>
                  <td></td>
                </tr>
              </thead>
              <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
              <tbody id="option-value-row<?php echo $option_value_row; ?>">
                <tr>
                  <td class="left"><select class="span2" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]">
                    </select>
                    <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                  <td class="right"><input class="span1" type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="3" /></td>
                  <td class="left"><select class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">
                      <?php if ($product_option_value['subtract']) { ?>
                      <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                      <option value="0"><?php echo $text_no; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_yes; ?></option>
                      <option value="0" selected="selected"><?php echo $text_no; ?></option>
                      <?php } ?>
                    </select></td>
                  <td class="right"><select  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]">
                      <?php if ($product_option_value['price_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['price_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" size="5" /></td>
                  <td class="right"><select  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]">
                      <?php if ($product_option_value['points_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['points_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" size="5" /></td>
                  <td class="right"><select  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]">
                      <?php if ($product_option_value['weight_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['weight_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" size="5" /></td>
                  <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                </tr>
              </tbody>
              <?php $option_value_row++; ?>
              <?php } ?>
              <tfoot>
                <tr>
                  <td colspan="6"></td>
                  <td class="left"><a onclick="addOptionValue('<?php echo $option_row; ?>');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>
                </tr>
              </tfoot>
            </table>
            <?php } ?>
            
            <?php if ($product_option['type'] == 'autocomplete') { ?>
            <table id="option-value<?php echo $option_row; ?>" class="list">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_option_value; ?></td>
                  <td class="right"><?php echo $entry_quantity; ?></td>
                  <td class="left"><?php echo $entry_subtract; ?></td>
                  <td class="right"><?php echo $entry_price; ?></td>
                  <td class="right"><?php echo $entry_option_points; ?></td>
                  <td class="right"><?php echo $entry_weight; ?></td>
                  <td></td>
                </tr>
              </thead>
              <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
              <tbody id="option-value-row<?php echo $option_value_row; ?>">
                <tr>
                  <td class="left">
	                <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value]" value="<?php echo get_option_value($product_option_value['option_value_id']);?>" />
	                <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]" value="<?php echo $product_option_value['option_value_id']; ?>" />
                    <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                  <td class="right"><input class="span1" type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="3" /></td>
                  <td class="left"><select class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">
                      <?php if ($product_option_value['subtract']) { ?>
                      <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                      <option value="0"><?php echo $text_no; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_yes; ?></option>
                      <option value="0" selected="selected"><?php echo $text_no; ?></option>
                      <?php } ?>
                    </select></td>
                  <td class="right"><select style="display:none;"  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]">
                      <?php if ($product_option_value['price_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['price_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" size="5" /></td>
                  <td class="right"><select style="display:none;"  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]">
                      <?php if ($product_option_value['points_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['points_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" size="5" /></td>
                  <td class="right"><select style="display:none;"  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]">
                      <?php if ($product_option_value['weight_prefix'] == '+') { ?>
                      <option value="+" selected="selected">+</option>
                      <?php } else { ?>
                      <option value="+">+</option>
                      <?php } ?>
                      <?php if ($product_option_value['weight_prefix'] == '-') { ?>
                      <option value="-" selected="selected">-</option>
                      <?php } else { ?>
                      <option value="-">-</option>
                      <?php } ?>
                    </select>
                    <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" size="5" /></td>
                  <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                </tr>
              </tbody>
              <?php $option_value_row++; ?>
              <?php } ?>
              <tfoot>
                <tr>
                  <td colspan="6"></td>
                  <td class="left"><a onclick="addAutoOptionValue('<?php echo $option_row; ?>');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>
                </tr>
              </tfoot>
            </table>
            <?php } ?>
            
            <?php if ($product_option['type'] == 'virtual_product' ) { ?>
                        <table id="option-value<?php echo $option_row; ?>" class="list">
                          <thead>
                            <tr>
                              <td class="left"><?php echo $entry_option_value; ?></td>
                              <td class="right"><?php echo $entry_quantity; ?></td>
                              <td class="left"><?php echo $entry_option_virtual; ?></td>
                              <td class="left"><?php echo $entry_subtract; ?></td>
                              <td class="right"><?php echo $entry_price; ?></td>
                              <td class="right"><?php echo $entry_option_points; ?></td>
                              <td></td>
                            </tr>
                          </thead>
                          <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
                          <tbody id="option-value-row<?php echo $option_value_row; ?>">
                            <tr>
                              <td class="left"><select  class="span2" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]">
                                </select>
                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                              <td class="right"><input class="span1" type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" size="3" /></td>
                              <td class="left"><input class="span2" type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_value]" value="<?php echo $product_option_value['product_value']; ?>" size="3" /></td>
                              <td class="left"><select  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]">
                                  <?php if ($product_option_value['subtract']) { ?>
                                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                  <option value="0"><?php echo $text_no; ?></option>
                                  <?php } else { ?>
                                  <option value="1"><?php echo $text_yes; ?></option>
                                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                  <?php } ?>
                                </select></td>
                              <td class="right"><select  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]">
                                  <?php if ($product_option_value['price_prefix'] == '+') { ?>
                                  <option value="+" selected="selected">+</option>
                                  <?php } else { ?>
                                  <option value="+">+</option>
                                  <?php } ?>
                                  <?php if ($product_option_value['price_prefix'] == '-') { ?>
                                  <option value="-" selected="selected">-</option>
                                  <?php } else { ?>
                                  <option value="-">-</option>
                                  <?php } ?>
                                </select>
                                <input type="text"  class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" size="5" /></td>
                              <td class="right"><select   class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]">
                                  <?php if ($product_option_value['points_prefix'] == '+') { ?>
                                  <option value="+" selected="selected">+</option>
                                  <?php } else { ?>
                                  <option value="+">+</option>
                                  <?php } ?>
                                  <?php if ($product_option_value['points_prefix'] == '-') { ?>
                                  <option value="-" selected="selected">-</option>
                                  <?php } else { ?>
                                  <option value="-">-</option>
                                  <?php } ?>
                                </select>
                                <input type="text" class="span1" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" size="5" /></td>
                              
                              <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                            </tr>
                          </tbody>
                          <?php $option_value_row++; ?>
                          <?php } ?>
                          <tfoot>
                            <tr>
                              <td colspan="6"></td>
                              <td class="left"><a onclick="addOptionVirtualValue('<?php echo $option_row; ?>');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>
                            </tr>
                          </tfoot>
                        </table>
                        <?php } ?>
            
           <?php if ($product_option['type'] == 'color') { ?>
	        <table id="option-value<?php echo $option_row; ?>" class="list">
              <thead>
                <tr>
                  <td class="left"><?php echo $entry_option_value; ?></td>
                  <td class="right"><?php echo $entry_option_color; ?></td>

                  <td></td>
                </tr>
              </thead>
              <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
              <tbody id="option-value-row<?php echo $option_value_row; ?>">
                <tr>
                  <td class="left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]">
                    </select>
                    <input type="hidden"  name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                  	<td class="left"><input id="product_option_lable<?php echo $option_row; ?><?php echo $option_value_row; ?>"  class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"  type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_value]" value="<?php echo $product_option_value['product_value']; ?>" />
                  	<input type="hidden" id="product_option_value<?php echo $option_row; ?><?php echo $option_value_row; ?>" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][color_product_id]" value="<?php echo $product_option_value['color_product_id']; ?>" />
                  </td>
                  <td class="left"><a onclick="$('#option-value-row<?php echo $option_value_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                </tr>
              </tbody>
              <script type="text/javascript">
              <!--
              $('input[name=\'product_option[' + <?php echo $option_row; ?> + '][product_option_value][' + <?php echo $option_value_row; ?> + '][product_value]\']').autocomplete({
              		       		delay: 0,
              		       		source: function(request, response) {
              		       			$.ajax({
              		       				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>',
              		       				type: 'POST',
              		       				dataType: 'json',
              		       				data: 'filter_name=' +  encodeURIComponent(request.term),
              		       				success: function(data) {
              		       					response($.map(data, function(item) {
              		       						return {
              		       							label: item.name,
              		       							value: item.product_id
              		       						}
              		       					}));
              		       				}
              		       			});
              	
              		       		},
              		       		select: function(event, ui) {
                  					$('#product_option_lable' + <?php echo $option_row; ?> + <?php echo $option_value_row; ?> ).val(ui.item.label);
              		       			$('#product_option_value' + <?php echo $option_row; ?>  + <?php echo $option_value_row; ?>).val(ui.item.value);
              		       			return false;
              		       		}
              		       	});
              	   -->
               </script>
              
              <?php $option_value_row++; ?>
              <?php } ?>
              <tfoot>
                <tr>
                  <td colspan="2"></td>
                  <td class="left"><a onclick="addOptionColorValue('<?php echo $option_row; ?>');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>
                </tr>
              </tfoot>
            </table>
            <?php } ?>
          </div>
          <?php $option_row++; ?>
          <?php } ?>
          <script type="text/javascript"><!--
          <?php $option_row = 0; ?>
          <?php $option_value_row = 0; ?>
		  <?php foreach ($product_options as $product_option) { ?>
          <?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox'|| $product_option['type'] == 'color' || $product_option['type'] == 'virtual_product' || $product_option['type'] == 'autocomplete' ) { ?>
		  <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
		  $('select[name=\'product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=<?php echo $product_option['option_id']; ?>&option_value_id=<?php echo $product_option_value['option_value_id']; ?>');
		  <?php $option_value_row++; ?>
		  <?php } ?>
		  <?php } ?>
		  <?php $option_row++; ?>
          <?php } ?>
		  //--></script>
        </div>
        <!--  div id="tab-discount">
          <table id="discount" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_customer_group; ?></td>
                <td class="right"><?php echo $entry_discount_quantity; ?></td>
                <td class="right"><?php echo $entry_limited; ?></td>
                <td class="right"><?php echo $entry_priority; ?></td>
                <td class="right"><?php echo $entry_price; ?></td>
                  <td class="left"><?php echo $entry_customer_group_type; ?></td>
                <td class="left"><?php echo $entry_date_start; ?></td>
                <td class="left"><?php echo $entry_date_end; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $discount_row = 0; ?>
            <?php foreach ($product_discounts as $product_discount) { ?>
            <tbody id="discount-row<?php echo $discount_row; ?>">
              <tr>
                <td class="left"><select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="right"><input type="text" class="span1" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" size="2" />
                <input type="hidden" name="product_discount[<?php echo $discount_row; ?>][product_discount_id]" value="<?php echo $product_discount['product_discount_id']; ?>"/> 
                </td>
                <td class="right"><input type="text" class="span1" name="product_discount[<?php echo $discount_row; ?>][limited]" value="<?php echo $product_discount['limited']; ?>" size="2" /></td>
                <td class="right"><input type="text" class="span1" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" size="2" /></td>
                <td class="right"><input type="text" class="span2" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" /></td>
                 <td class="left">
<select name="product_discount[<?php echo $discount_row; ?>][subordinate_type]">
                    <option value="0"<?php if ('0' == $product_discount['subordinate_type']) { ?> selected="selected" <?php }?>>专享</option>
                    <option value="1"<?php if ('1' == $product_discount['subordinate_type']) { ?> selected="selected" <?php }?>>级联</option>                    
                  </select>
</td>
                <td class="left"><input type="text"  name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" class="datetime" /></td>
                <td class="left"><input type="text"  name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" class="datetime" /></td>
                <td class="left"><a onclick="$('#discount-row<?php echo $discount_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $discount_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="8"></td>
                <td class="left"><a onclick="addDiscount();" class="button"><span><?php echo $button_add_discount; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
        </div-->
        <div id="tab-special">
          <table id="special" class="list">
            <thead>
              <tr>
                <td class="left" colspan="11"><span>T+N</span></td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_customer_group; ?></td>
                <td class="right"><?php echo $entry_discount_quantity; ?></td>
                <td class="right"><?php echo $entry_limited; ?></td>
                <td class="right"><?php echo $entry_tags; ?></td>
                <td class="right"><?php echo $entry_priority; ?></td>
                <td class="right"><?php echo $entry_price; ?></td>
                <td class="left"><?php echo $entry_customer_group_type; ?></td>
                <td class="left"><?php echo $entry_type; ?></td>
                <td class="left"><?php echo $entry_date_start; ?></td>
                <td class="left"><?php echo $entry_date_end; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $special_row = 0; ?>
            <?php foreach ($product_specials as $product_special) { ?>
            <tbody id="special-row<?php echo $special_row; ?>">
              <tr>
                <td class="left"><select name="product_special[<?php echo $special_row; ?>][customer_group_id]">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="right"><input type="text" class="span1" name="product_special[<?php echo $special_row; ?>][quantity]" value="<?php echo $product_special['quantity']; ?>" size="2" />
                </td>
                <td class="right"><input type="text" class="span1" name="product_special[<?php echo $special_row; ?>][limited]" value="<?php echo $product_special['limited']; ?>" size="2" />
                                  <input type="hidden" name="product_special[<?php echo $special_row; ?>][product_special_id]" value="<?php echo $product_special['product_special_id']; ?>"/>   
                </td>
                <td class="right"><input type="text" class="span1" name="product_special[<?php echo $special_row; ?>][tags]" value="<?php echo $product_special['tags']; ?>" size="5" /></td>
                
                <td class="right"><input type="text" class="span1" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" size="2" /></td>
                
                <td class="right"><input type="text" class="span1" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" /></td> 
                <td class="left">
<select name="product_special[<?php echo $special_row; ?>][subordinate_type]">
                    <option value="0"<?php if ('0' == $product_special['subordinate_type']) { ?> selected="selected" <?php }?>>专享</option>
                    <option value="1"<?php if ('1' == $product_special['subordinate_type']) { ?> selected="selected" <?php }?>>级联</option>
                  </select>
                  </td>
                  <td class="left">
                  <select name="product_special[<?php echo $special_row; ?>][code]">
                  <?php foreach(EnumPromotionTypes::getPromotionTypes() as $promo){ ?>
                    <option value="<?php echo $promo[value] ;?>"<?php if ($promo[value] == $product_special['code']) { ?> selected="selected" <?php }?>><?php echo $promo[name];?></option>
                  <?php }?>
                  </select>
</td>
                <td class="left"><input type="text" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" class="datetime" /></td>
                <td class="left"><input type="text"  name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" class="datetime" /></td>
                <td class="left"><a onclick="$('#special-row<?php echo $special_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $special_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td colspan="10"></td>
                <td class="left"><a onclick="addSpecial();" class="button"><span><?php echo $button_insert; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
            <table id="special0" class="list">
            <thead>
              <tr>
                <td class="left" colspan="12"><span>T+0</span>：
                 <div id="tabdelivery" class="htabs">
                <a href=".tabcode">查看所有</a>
                <a href=".tabcode-all">通用</a><?php  foreach($points_option as $key=> $groups) {?>
                <a href=".tabcode-<?php echo $key;?>"><?php echo $key;?></a>
       <?php }  ?>
    </div>
    <?php  foreach($points_option as $key=> $groups) {?>
    <div id="tabcode-<?php echo $key;?>" class="tabcode-<?php echo $key;?>">
     <?php foreach($groups as $option) {?>
     <a href=".tabd-<?php echo $option['value']; ?>" <?php if($option['value']==$filter_point_id) {?>selected="selected"<?php } ?>><?php echo $option['name']; ?></a>
    <?php }  ?>
    </div>
    <script>$(function(){
       $('#tabcode-<?php echo $key;?> a').tabs({donefirst:false});
    });
    </script>
    <?php }  ?>
    </td>
              </tr>
              <tr>
                <td class="left"><?php echo $entry_delivery; ?></td>
                <td class="left"><?php echo $entry_customer_group; ?></td>
                <td class="right"><?php echo $entry_discount_quantity; ?></td>
                <td class="right"><?php echo $entry_limited; ?></td>
                <td class="right"><?php echo $entry_tags; ?></td>
                <td class="right"><?php echo $entry_priority; ?></td>
                <td class="right"><?php echo $entry_price; ?></td>
                <td class="left"><?php echo $entry_customer_group_type; ?></td>
                <td class="left"><?php echo $entry_type; ?></td>
                <td class="left"><?php echo $entry_date_start; ?></td>
                <td class="left"><?php echo $entry_date_end; ?></td>
                <td></td>
              </tr>
            </thead>
            <?php $special_row0 = 0; ?>
            <?php foreach ($product_special0s as $kc=> $c) { ?>
            <?php foreach ($c as $kd=> $d) { ?>
            <?php foreach ($d as $product_special0) { ?>
            <tbody id="special-row0<?php echo $special_row0; ?>" class="tabcode tabcode-<?php echo $product_special0[delivery_name];?> tabcode-<?php echo $kc;?> tabd-<?php echo $kd;?>">
              <tr>
              <td class="left"><?php echo $points_option_name[$product_special0['delivery_id']]; ?>
              <input type="hidden" name="product_special0[<?php echo $special_row0; ?>][delivery_id]" value="<?php echo $product_special0['delivery_id']; ?>"/>   
              </td>
                <td class="left"><select name="product_special0[<?php echo $special_row0; ?>][customer_group_id]">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $product_special0['customer_group_id']) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="right"><input type="text" class="span1" name="product_special0[<?php echo $special_row0; ?>][quantity]" value="<?php echo $product_special0['quantity']; ?>" size="2" />
                </td>
                <td class="right"><input type="text" class="span1" name="product_special0[<?php echo $special_row0; ?>][limited]" value="<?php echo $product_special0['limited']; ?>" size="2" />
                                  <input type="hidden" name="product_special0[<?php echo $special_row0; ?>][product_special_id]" value="<?php echo $product_special0['product_special_id']; ?>"/>   
                </td>
                <td class="right"><input type="text" class="span1" name="product_special0[<?php echo $special_row0; ?>][tags]" value="<?php echo $product_special0['tags']; ?>" size="5" /></td>
                
                <td class="right"><input type="text" class="span1" name="product_special0[<?php echo $special_row0; ?>][priority]" value="<?php echo $product_special0['priority']; ?>" size="2" /></td>
                
                <td class="right"><input type="text" class="span1" name="product_special0[<?php echo $special_row0; ?>][price]" value="<?php echo $product_special0['price']; ?>" /></td> 
                <td class="left">
<select name="product_special0[<?php echo $special_row0; ?>][subordinate_type]">
                    <option value="0"<?php if ('0' == $product_special0['subordinate_type']) { ?> selected="selected" <?php }?>>专享</option>
                    <option value="1"<?php if ('1' == $product_special0['subordinate_type']) { ?> selected="selected" <?php }?>>级联</option>
                  </select>
                  </td>
                  <td class="left">
                  <select name="product_special0[<?php echo $special_row0; ?>][code]">
                  <?php foreach(EnumPromotionTypes::getPromotionTypes() as $promo){ ?>
                    <option value="<?php echo $promo[value] ;?>"<?php if ($promo[value] == $product_special0['code']) { ?> selected="selected" <?php }?>><?php echo $promo[name];?></option>
                  <?php }?>
                  </select>
</td>
                <td class="left"><input type="text" name="product_special0[<?php echo $special_row0; ?>][date_start]" value="<?php echo $product_special0['date_start']; ?>" class="datetime" /></td>
                <td class="left"><input type="text"  name="product_special0[<?php echo $special_row0; ?>][date_end]" value="<?php echo $product_special0['date_end']; ?>" class="datetime" /></td>
                <td class="left"><a onclick="$('#special-row0<?php echo $special_row0; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
           
            </tbody>
            <?php $special_row0++; ?>
            <?php } ?>
               <?php } ?>
              <?php } ?>
            <tfoot>
              <tr>
                <td colspan="11"></td>
                <td class="left"><a onclick="addSpecial0();" class="button"><span><?php echo $button_insert; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="tab-image">
          <table id="images" class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_image; ?></td>
                <td></td>
              </tr>
             
            </thead>
            <?php $image_row = 0; ?>
            <?php foreach ($product_images as $product_image) { ?>
            <tbody id="image-row<?php echo $image_row; ?>">
              <tr>
                <td class="left"><img src="<?php echo $product_image['preview']; ?>" alt="" id="preview<?php echo $image_row; ?>" class="image" onclick="image_upload('image<?php echo $image_row; ?>', 'preview<?php echo $image_row; ?>');" />
                  <input type="hidden" name="product_image[<?php echo $image_row; ?>]" value="<?php echo $product_image['image']; ?>" id="image<?php echo $image_row; ?>"  /></td>
                <td class="left"><a onclick="$('#image-row<?php echo $image_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
              </tr>
            </tbody>
            <?php $image_row++; ?>
            <?php } ?>
            <tfoot>
              <tr>
                <td></td>
                <td class="left"><a onclick="addImage();" class="button"><span><?php echo $button_add_image; ?></span></a></td>
              </tr>
            </tfoot>
          </table>
        </div>
        <div id="tab-reward">
          <table class="form">
            <tr>
              <td><?php echo $entry_points; ?></td>
              <td><input type="text" name="points" value="<?php echo $points; ?>" /></td>
            </tr>
          </table>
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_customer_group; ?></td>
                <td class="right"><?php echo $entry_reward; ?></td>
              </tr>
            </thead>
            <?php foreach ($customer_groups as $customer_group) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $customer_group['name']; ?></td>
                <td class="right"><input type="text" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" /></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
        <div id="tab-design">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><?php echo $entry_store; ?></td>
                <td class="left"><?php echo $entry_layout; ?></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $text_default; ?></td>
                <td class="left"><select name="product_layout[0][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php foreach ($stores as $store) { ?>
            <tbody>
              <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="product_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
      </form>
    </div>
  </div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});


CKEDITOR.replace('cooking<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

CKEDITOR.replace('package<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script>

<script type="text/javascript"><!--
$(function(){
$('#tabs a').tabs();
$('#tabdelivery a').tabs();

<?php if(COUNT($languages)>1) {?>
$('#languages a').tabs();
$('#languagesdescription a').tabs();
$('#seo_languages a').tabs();
<?php } ?>

$('#vtab-option a').tabs();
});
//--></script>

<script type="text/javascript"><!--
$(document).ready(function(){
	$('input[name=\'related\']').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'json',
				data: 'filter_name=' +  encodeURIComponent(request.term),
				success: function(data) {
					response($.map(data, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});

		},
		select: function(event, ui) {
			$('#product-related' + ui.item.value).remove();

			$('#product-related').append('<div id="product-related' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_related[]" value="' + ui.item.value + '" /></div>');

			$('#product-related div:odd').attr('class', 'odd');
			$('#product-related div:even').attr('class', 'even');

			return false;
		}
	});
});


$('#product-related div img').live('click', function() {
	$(this).parent().remove();

	$('#product-related div:odd').attr('class', 'odd');
	$('#product-related div:even').attr('class', 'even');
});
//--></script>
<script type="text/javascript"><!--
var attribute_row = <?php echo $attribute_row; ?>;

function addAttribute() {
	html  = '<tbody id="attribute-row' + attribute_row + '">';
    html += '  <tr>';
	html += '    <td class="left"><input   type="text" name="product_attribute[' + attribute_row + '][name]" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
	html += '    <td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<textarea class="span6"  name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5"></textarea><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '    </td>';
	html += '    <td class="left"><a onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
    html += '  </tr>';
    html += '</tbody>';

	$('#attribute tfoot').before(html);

	attributeautocomplete(attribute_row);

	attribute_row++;
}

function attributeautocomplete(attribute_row) {
	$('input[name=\'product_attribute[' + attribute_row + '][name]\']').catcomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'json',
				data: 'filter_name=' +  encodeURIComponent(request.term),
				success: function(data) {
					response($.map(data, function(item) {
						return {
							category: item.attribute_group,
							label: item.name,
							value: item.attribute_id
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product_attribute[' + attribute_row + '][name]\']').attr('value', ui.item.label);
			$('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').attr('value', ui.item.value);

			return false;
		}
	});
}

$(document).ready(function(){
	$.widget('custom.catcomplete', $.ui.autocomplete, {
		_renderMenu: function(ul, items) {
			var self = this, currentCategory = '';

			$.each(items, function(index, item) {
				if (item.category != currentCategory) {
					ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

					currentCategory = item.category;
				}

				self._renderItem(ul, item);
			});
		}
	});

	

	$('#attribute tbody').each(function(index, element) {
		attributeautocomplete(index);
	});
});

//--></script>
<script type="text/javascript"><!--
$(document).ready(function(){
var option_row = <?php echo $option_row; ?>;

$('input[name=\'option\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>',
			type: 'POST',
			dataType: 'json',
			data: 'filter_name=' +  encodeURIComponent(request.term),
			success: function(data) {
				response($.map(data, function(item) {
					return {
						category: item.category,
						label: item.name,
						value: item.option_id,
						type: item.type
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		html  = '<div id="tab-option-' + option_row + '" class="vtabs-content">';
		html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + ui.item.label + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + ui.item.value + '" />';
		html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + ui.item.type + '" />';
		if (ui.item.type != 'virtual_product' ) {
			html += '	<table class="form">';
			html += '	  <tr>';
			html += '		<td><?php echo $entry_required; ?></td>';
			html += '       <td><select name="product_option[' + option_row + '][required]">';
			html += '	      <option value="1"><?php echo $text_yes; ?></option>';
			html += '	      <option value="0"><?php echo $text_no; ?></option>';
			html += '	    </select></td>';
			html += '     </tr>';
		}else{
			html += '	<input type="hidden" name="product_option[' + option_row + '][required]" value="0" />';
		}
		
		if (ui.item.type == 'text') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'textarea') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><textarea  class="span6" name="product_option[' + option_row + '][option_value]" cols="40" rows="5"></textarea></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'file') {
			html += '     <tr style="display: none;">';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'date') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="date" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'datetime') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="datetime" /></td>';
			html += '     </tr>';
		}

		if (ui.item.type == 'time') {
			html += '     <tr>';
			html += '       <td><?php echo $entry_option_value; ?></td>';
			html += '       <td><input type="text" name="product_option[' + option_row + '][option_value]" value="" class="time" /></td>';
			html += '     </tr>';
		}


		html += '  </table>';

		if (ui.item.type == 'select' || ui.item.type == 'radio' || ui.item.type == 'checkbox') {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}
		
		if (ui.item.type == 'autocomplete') {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td class="right"><?php echo $entry_weight; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addAutoOptionValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}

		if (ui.item.type == 'color' ) {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="left"><?php echo $entry_option_color; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="2"></td>';
			html += '        <td class="left"><a onclick="addOptionColorValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}

		if (ui.item.type == 'virtual_product' ) {
			html += '  <table id="option-value' + option_row + '" class="list">';
			html += '  	 <thead>';
			html += '      <tr>';
			html += '        <td class="left"><?php echo $entry_option_value; ?></td>';
			html += '        <td class="right"><?php echo $entry_quantity; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_virtual; ?></td>';
			html += '        <td class="left"><?php echo $entry_subtract; ?></td>';
			html += '        <td class="right"><?php echo $entry_price; ?></td>';
			html += '        <td class="right"><?php echo $entry_option_points; ?></td>';
			html += '        <td></td>';
			html += '      </tr>';
			html += '  	 </thead>';
			html += '    <tfoot>';
			html += '      <tr>';
			html += '        <td colspan="6"></td>';
			html += '        <td class="left"><a onclick="addOptionVirtualValue(' + option_row + ');" class="button"><span><?php echo $button_add_option_value; ?></span></a></td>';
			html += '      </tr>';
			html += '    </tfoot>';
			html += '  </table>';
			html += '</div>';
		}
		
		$('#tab-option').append(html);
		
		$('#option-add').before('<a href="#tab-option-' + option_row + '" id="option-' + option_row + '">' + ui.item.label + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'#vtab-option a:first\').trigger(\'click\'); $(\'#option-' + option_row + '\').remove(); $(\'#tab-option-' + option_row + '\').remove(); return false;" /></a>');

		$('#vtab-option a').tabs();

		$('#option-' + option_row).trigger('click');

		$('.date').datepicker({dateFormat: 'yy-mm-dd'});
		$('.datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:m'
		});

		$('.time').timepicker({timeFormat: 'h:m'});

		option_row++;

		return false;
	}
});
});
//--></script>
<script type="text/javascript"><!--

var option_value_row = <?php echo $option_value_row; ?>;

function addOptionValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select class="span2" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input class="span1" type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="left"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';
	html += '    <td class="right"><select  class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	option_value_row++;
}

//--></script>

<script type="text/javascript"><!--
var option_value_row = <?php echo $option_value_row; ?>;

function addAutoOptionValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value]" class="span2" /><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" /><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input class="span1" type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="left"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select style="display:none;" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select style="display:none;"  name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" size="5" /></td>';
	html += '    <td class="right"><select style="display:none;"   class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" size="5" /></td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_value_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	init_auto_bind(option_row,option_value_row);
	
	option_value_row++;
}

function init_auto_bind(option_row,option_value_row){
	$('input[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value]\']').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/optionvalue&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'json',
				data: 'filter_name=' +  encodeURIComponent($.trim(request.term)),
				success: function(data) {
					response($.map(data, function(item) {
						return {
							label: item.name,
							option_value_id: item.option_value_id,
						}
					}));
				}
			});
		},
		select: function(event, ui) {
			$('input[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value]\']').val(ui.item.label);
			$('input[name=\'product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]\']').val(ui.item.option_value_id);
 		}
	});
}
//--></script>

<script type="text/javascript"><!--
var option_color_value_row = <?php echo $option_value_row; ?>;

function addOptionColorValue(option_row) {
	html  = '<tbody id="option-value-row' + option_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="left">';
	html += '    <input type="text" value="" name="product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][product_value]" id="product_option_lable'+ option_row + option_color_value_row+'"  class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"> ';
	html += '    <input type="hidden" value="" name="product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][color_product_id]" id="product_option_value'+ option_row  + option_color_value_row+'"  > </td>';
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	option_color_value_row_temp=option_color_value_row;
	
	$('input[name=\'product_option[' + option_row + '][product_option_value][' + option_color_value_row + '][product_value]\']').autocomplete({
		delay: 0,
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>',
				type: 'POST',
				dataType: 'json',
				data: 'filter_name=' +  encodeURIComponent(request.term),
				success: function(data) {
					response($.map(data, function(item) {
						return {
							label: item.name,
							value: item.product_id
						}
					}));
				}
			});

		},
		select: function(event, ui) {
			$('#product_option_lable' + option_row + option_color_value_row_temp ).val(ui.item.label);
			$('#product_option_value' + option_row  + option_color_value_row_temp).val(ui.item.value);
			return false;
		}
	});

	option_color_value_row++;
}
//--></script>

<script type="text/javascript"><!--
var option_virtual_value_row = <?php echo $option_value_row; ?>;

function addOptionVirtualValue(option_row) {
	html  = '<tbody id="option-value-row' + option_value_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select class="span2" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][option_value_id]"></select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][product_option_value_id]" value="" /></td>';
	html += '    <td class="right"><input class="span1" type="text" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][quantity]" value="" size="3" /></td>';
	html += '    <td class="right"><input class="span2" type="text" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][product_value]" value="" size="20" /></td>';
	html += '    <td class="left"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][subtract]">';
	html += '      <option value="1"><?php echo $text_yes; ?></option>';
	html += '      <option value="0"><?php echo $text_no; ?></option>';
	html += '    </select></td>';
	html += '    <td class="right"><select class="span1" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][price_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1"  name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][price]" value="" size="5" /></td>';
	html += '    <td class="right"><select name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][points_prefix]">';
	html += '      <option value="+">+</option>';
	html += '      <option value="-">-</option>';
	html += '    </select>';
	html += '    <input type="text" class="span1" name="product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][points]" value="" size="5" /></td>';
	
	html += '    <td class="left"><a onclick="$(\'#option-value-row' + option_virtual_value_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#option-value' + option_row + ' tfoot').before(html);

	$('select[name=\'product_option[' + option_row + '][product_option_value][' + option_virtual_value_row + '][option_value_id]\']').load('index.php?route=catalog/product/option&token=<?php echo $token; ?>&option_id=' + $('input[name=\'product_option[' + option_row + '][option_id]\']').attr('value'));

	option_value_row++;
}
//--></script>


<script type="text/javascript"><!--
var discount_row = <?php echo $discount_row; ?>;

function addDiscount() {
	html  = '<tbody id="discount-row' + discount_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select name="product_discount[' + discount_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '    <td class="right"><input type="text"  class="span1" name="product_discount[' + discount_row + '][quantity]" value="" size="2" />\
       <input type="hidden" name="product_discount[' + discount_row + '][product_discount_id]" value=""/>\
    </td>';
    html += ' <td class="right"><input type="text" class="span1" name="product_discount[' + discount_row + '][limited]" value="0" size="2" /></td>\
    ';
    html += '    <td class="right"><input type="text"  class="span1" name="product_discount[' + discount_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text"  class="span1" name="product_discount[' + discount_row + '][price]" value="" /></td>';
    html +=   ' <td class="left"><select name="product_discount[' + discount_row + '][subordinate_type]">\
    <option value="0" selected="selected">专享</option>\
    <option value="1">级联</option>\
  </select></td>';
    html += '    <td class="left"><input type="text"   name="product_discount[' + discount_row + '][date_start]" value="" class="datetime" /></td>';
	html += '    <td class="left"><input type="text"   name="product_discount[' + discount_row + '][date_end]" value="" class="datetime" /></td>';
	html += '    <td class="left"><a onclick="$(\'#discount-row' + discount_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#discount tfoot').before(html);

	$('#discount-row' + discount_row + ' .datetime').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat: 'h:m'});

	discount_row++;
}
//--></script>
<script type="text/javascript"><!--
var special_row = <?php echo $special_row; ?>;

function addSpecial() {
	html  = '<tbody id="special-row' + special_row + '">';
	html += '  <tr>';
    html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '<td class="right"><input type="text" class="span1" name="product_special[' + special_row + '][quantity]" value="0" size="2" /></td>';
    html += '  <td class="right"><input type="text" class="span1" name="product_special[' + special_row + '][limited]" value="0" size="2" />\
    <input type="hidden" name="product_special[' + special_row + '][product_special_id]" value=""/>\
    </td>\
    <td class="right"><input type="text" class="span1" name="product_special[' + special_row + '][tags]" value="" size="2" /></td>';
    html += '    <td class="right"><input type="text" class="span1"  name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" class="span1"  name="product_special[' + special_row + '][price]" value="" /></td>';
    html +=   ' <td class="left"><select name="product_special[' + special_row + '][subordinate_type]">\
    <option value="0" selected="selected">专享</option>\
    <option value="1">级联</option>\
  </select></td>';
  html += ' <td class="left">\
    <select name="product_special[' + special_row + '][code]">\
    <?php foreach(EnumPromotionTypes::getPromotionTypes() as $promo){ ?>\
      <option value="<?php echo $promo[value] ;?>"><?php echo $promo[name];?></option>\
    <?php }?>\
    </select>\
</td>';
    html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="datetime" /></td>';
	html += '    <td class="left"><input type="text"  name="product_special[' + special_row + '][date_end]" value="" class="datetime" /></td>';
	html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#special tfoot').before(html);

	$('#special-row' + special_row + ' .datetime').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat: 'h:m'});

	special_row++;
}

var special_row0 = <?php echo $special_row0; ?>;

function addSpecial0() {
	html  = '<tbody id="special-row0' + special_row0 + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="product_special0[' + special_row0 + '][delivery_id]">';
	html += '     <option value="*">通用</option>';
		html += '     <option value="0"<?php if($filter_point_id==='0') {?> selected="selected"<?php } ?>>宅配</option>';
    <?php  foreach($points_option as $key=> $groups) {?>
    html += '     <optgroup label="<?php echo $key;?>"><?php echo $key;?></optgroup>';
     <?php foreach($groups as $option) {?>
     html += '     <option value="<?php echo $option['value']; ?>" <?php if($option['value']==$filter_point_id) {?>selected="selected"<?php } ?>><?php echo $option['name']; ?></option>';
    <?php }  ?>
    <?php }  ?>
	html += '    </select></td>';
    html += '    <td class="left"><select name="product_special0[' + special_row0 + '][customer_group_id]">';
    <?php foreach ($customer_groups as $customer_group) { ?>
    html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
    <?php } ?>
    html += '    </select></td>';
    html += '<td class="right"><input type="text" class="span1" name="product_special0[' + special_row0 + '][quantity]" value="0" size="2" /></td>';
    html += '  <td class="right"><input type="text" class="span1" name="product_special0[' + special_row0 + '][limited]" value="0" size="2" />\
    <input type="hidden" name="product_special0[' + special_row0 + '][product_special_id]" value=""/>\
    </td>\
    <td class="right"><input type="text" class="span1" name="product_special0[' + special_row0 + '][tags]" value="" size="2" /></td>';
    html += '    <td class="right"><input type="text" class="span1"  name="product_special0[' + special_row0 + '][priority]" value="" size="2" /></td>';
	html += '    <td class="right"><input type="text" class="span1"  name="product_special0[' + special_row0 + '][price]" value="" /></td>';
    html +=   ' <td class="left"><select name="product_special0[' + special_row0 + '][subordinate_type]">\
    <option value="0" selected="selected">专享</option>\
    <option value="1">级联</option>\
  </select></td>';
  html += ' <td class="left">\
    <select name="product_special0[' + special_row0 + '][code]">\
    <?php foreach(EnumPromotionTypes::getPromotionTypes() as $promo){ ?>\
      <option value="<?php echo $promo[value] ;?>"><?php echo $promo[name];?></option>\
    <?php }?>\
    </select>\
</td>';
    html += '    <td class="left"><input type="text" name="product_special0[' + special_row0 + '][date_start]" value="" class="datetime" /></td>';
	html += '    <td class="left"><input type="text"  name="product_special0[' + special_row0 + '][date_end]" value="" class="datetime" /></td>';
	html += '    <td class="left"><a onclick="$(\'#special-row0' + special_row0 + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
    html += '</tbody>';

	$('#special0 tfoot').before(html);

	$('#special-row0' + special_row0 + ' .datetime').datetimepicker({dateFormat: 'yy-mm-dd',timeFormat: 'h:m'});

	special_row0++;
}
//--></script>

<script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
    html  = '<tbody id="image-row' + image_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="hidden" name="product_image[' + image_row + ']" value="" id="image' + image_row + '" /><img src="<?php echo $no_image; ?>" alt="" id="preview' + image_row + '" class="image" onclick="image_upload(\'image' + image_row + '\', \'preview' + image_row + '\');" /></td>';
	html += '    <td class="left"><a onclick="$(\'#image-row' + image_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#images tfoot').before(html);

	image_row++;
}
//--></script>


<script type="text/javascript"><!--
$(document).ready(function(){
	combineSelect();
     
    $('input[name=\'combine_input\']').autocomplete({
    	delay: 0,
    	source: function(request, response) {
    		$.ajax({
    			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
    			dataType: 'json',
    			success: function(data) {
    				response($.map(data, function(item) {
    					return {
    						label: item.name,
    						value: item.product_id
    					}
    				}));
    			}
    		});
    
    	},
    	select: function(event, ui) {
    		$('#product-combine' + ui.item.value).remove();
    
    		$('#product-combine').append('<div id="product-combine' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_combine[]" value="' + ui.item.value + '" /></div>');
    
    		$('#product-combine div:odd').attr('class', 'odd');
    		$('#product-combine div:even').attr('class', 'even');
    
    		return false;
    	}
    });
    
    
    
    $('#product-combine div img').live('click', function() {
    	$(this).parent().remove();
    
    	$('#product-combine div:odd').attr('class', 'odd');
    	$('#product-combine div:even').attr('class', 'even');
    });
    
    $('input[name=\'coupon_input\']').autocomplete({
    	delay: 0,
    	source: function(request, response) {
    		$.ajax({
    			url: 'index.php?route=sale/coupon/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
    			dataType: 'json',
    			success: function(data) {
    				response($.map(data, function(item) {
    					return {
    						label: item.name,
    						value: item.coupon_id
    					}
    				}));
    			}
    		});
    
    	},
    	select: function(event, ui) {
    		$('#product-coupon' + ui.item.value).remove();
    
    		$('#product-coupon').append('<div id="product-coupon' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_coupon[' + ui.item.value + '][coupon_id]" value="' + ui.item.value + '"/>|数量<input type="text" name="product_coupon[' + ui.item.value + '][coupon_num]" value="1" style="width:30px;height:12px" />|克隆:<input type="checkbox" name="product_coupon[' + ui.item.value + '][is_tpl]" value="1" /></div>');
    
    		$('#product-coupon div:odd').attr('class', 'odd');
    		$('#product-coupon div:even').attr('class', 'even');
    
    		return false;
    	}
    });
    $('#product-coupon div img').live('click', function() {
    	$(this).parent().remove();
    
    	$('#product-coupon div:odd').attr('class', 'odd');
    	$('#product-coupon div:even').attr('class', 'even');
    });
    
    $('input[name=\'trans_code_input\']').autocomplete({
    	delay: 0,
    	source: function(request, response) {
    		$.ajax({
    			url: 'index.php?route=sale/transaction/autocomplete&token=<?php echo $token; ?>&filter_code=' +  encodeURIComponent(request.term),
    			dataType: 'json',
    			success: function(data) {
    				response($.map(data, function(item) {
    					return {
    						label: item.trans_code,
    						value: item.trans_id
    					}
    				}));
    			}
    		});
    
    	},
    	select: function(event, ui) {
    		$('#product-trans_code' + ui.item.value).remove();
    
    		$('#product-trans_code').append('<div id="product-trans_code' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="product_trans_code[' + ui.item.value + '][trans_code_id]" value="' + ui.item.value + '"/>|数量<input type="text" name="product_trans_code[' + ui.item.value + '][num]" value="1" style="width:30px;height:12px" />|克隆:<input type="checkbox" name="product_trans_code[' + ui.item.value + '][is_tpl]" value="1" /></div>');
    
    		$('#product-trans_code div:odd').attr('class', 'odd');
    		$('#product-trans_code div:even').attr('class', 'even');
    
    		return false;
    	}
    });
    $('#product-trans_code div img').live('click', function() {
    	$(this).parent().remove();
    
    	$('#product-trans_code div:odd').attr('class', 'odd');
    	$('#product-trans_code div:even').attr('class', 'even');
    });
    
});
			
/**
 *  套餐选择控制
 */
function combineSelect() {
	if (document.getElementById("combineRd1").checked) {
	    document.getElementById("combine").style.display = "block";
	}
	else {
		document.getElementById("combine").style.display = "none";
	}
} 
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/i18n/jquery-ui-i18n.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});

//--></script>

<?php if(false) {?>
<script type="text/javascript"><!--
function updateStockCont()
{
    var getStockContStatus = $('#has_option').val();

    if(getStockContStatus == 0)
    {
        $('.isStockCont').hide();
        $('.notStockCont').show();
    }else{
        $('.isStockCont').show();
        $('.notStockCont').hide();
    }
}


$(document).ready(function() {
    updateStockCont();

  
    
});
//--></script>
<?php } ?>

<script type="text/javascript">
<!--
$(document).ready(function() {
	
	  $('#saveToTemplet').bind('click',function(){
		  	if($('input[name=\"templet_name\"]').val()==''){
				alert('请输入模板名称');
				return ;
		  	}
			$('#form').attr('action','<?php echo $save_to_templet;?>');
			$('#form').submit();
	     });
});
//-->
</script>


