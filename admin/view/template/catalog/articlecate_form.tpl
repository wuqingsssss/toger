
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
  <div class="heading">
    <div class="buttons">
    <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
    <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="content">
     <div id="tabs" class="htabs">
     <a href="#tab_general"><?php echo $tab_general; ?></a>
     <a href="#tab_seo"><?php echo $tab_seo_setting; ?></a>
     <a href="#tab_design"><?php echo $tab_design; ?></a>
     
     </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_general">
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
              <td><input class="input-xxlarge" name="article_category_description[<?php echo $language['language_id']; ?>][name]" maxlength="255" value="<?php echo isset($article_category_description[$language['language_id']]) ? $article_category_description[$language['language_id']]['name'] : ''; ?>" />
                <?php if (isset($error_name[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><textarea name="article_category_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($article_category_description[$language['language_id']]) ? $article_category_description[$language['language_id']]['description'] : ''; ?></textarea></td>
            </tr>
          </table>
        </div>
		<?php } ?>
		<table class="form">
          <tr>
            <td><?php echo $entry_category; ?></td>
            <td><select name="parent_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($categories as $category) { ?>
                <?php if ($category['article_category_id'] == $parent_id) { ?>
                <option value="<?php echo $category['article_category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $category['article_category_id']; ?>"><?php echo $category['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <input type="hidden" name="article_category_to_store[]" value="0"  />
          <tr>
            <td><?php echo $entry_code; ?></td>
            <td><input type="text" name="code" value="<?php echo $code; ?>" maxlength="50" /></td>
          </tr>
          
          <tr>
            <td><?php echo $entry_image; ?></td>
            <td valign="top"><input type="hidden" name="image" value="<?php echo $image; ?>" id="image" />
              <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('image', 'preview');" />
               <div>
                <a onclick="image_upload('image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                  <a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>'); 
                  $('#image').attr('value', '');"><?php echo $text_clear; ?></a>
                </div>
              </td>
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
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="number" name="sort_order" class="input-mini" value="<?php echo $sort_order; ?>" /></td>
          </tr>	
          </table>
      </div>
      <div id="tab_seo">
      <table class="form">
		  <tr>
            <td><?php echo $entry_keyword; ?></td>
            <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
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
              <td><?php echo $entry_meta_keyword; ?></td>
              <td><textarea class="input-xxlarge" name="article_category_description[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($article_category_description[$language['language_id']]) ? $article_category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_meta_description; ?></td>
              <td><textarea class="input-xxlarge" name="article_category_description[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($article_category_description[$language['language_id']]) ? $article_category_description[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
            </tr>
          </table>
        </div>
		<?php } ?>
		
      </div>
      <div id="tab_design">
        <table class="form">
          <tr>
            <td><?php echo $entry_template; ?></td>
            <td>
            	<select name="template_id">
            		<option value="0">默认</option>
            		<option value="1" <?php if($template_id==1) {?> selected="selected"<?php }?>>文章标题+摘要</option>
            		<option value="2" <?php if($template_id==2) {?> selected="selected"<?php }?>>文章标题+图片</option>
            		<option value="3" <?php if($template_id==3) {?> selected="selected"<?php }?>>文章标题+图片+摘要</option>
            	</select>
            </td>
          </tr>	
          	
           	<tr>
                <td class="left"><?php echo $text_default; ?></td>
                <td class="left"><select name="article_category_layout[0][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($article_category_layout[0]) && $article_category_layout[0] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>		
            <?php foreach ($stores as $store) { ?>
         	  <tr>
                <td class="left"><?php echo $store['name']; ?></td>
                <td class="left"><select name="article_category_layout[<?php echo $store['store_id']; ?>][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($article_category_layout[$store['store_id']]) && $article_category_layout[$store['store_id']] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
           <?php } ?>
        </table>
      </div>
    </form>
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
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
<?php if(COUNT($languages)>1) {?>
$('#languages a').tabs();
$('#seo_languages a').tabs(); 
<?php } ?>
//--></script>