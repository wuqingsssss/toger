<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <div class="buttons">
    <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
    <a href="<?php echo $cancel; ?>" class="btn"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs">
	    <a tab="#tab_general"><?php echo $tab_general; ?></a>
	    <!--a tab="#tab_data"><?php echo $tab_data; ?></a-->
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_general">
        <div id="languages" class="htabs">
          <?php foreach ($languages as $language) { ?>
          <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		  <?php } ?>
        </div>
        <?php foreach ($languages as $language) { ?>
        <div id="language<?php echo $language['language_id']; ?>">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_name; ?></td>
              <td><input name="link_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($link_description[$language['language_id']]) ? $link_description[$language['language_id']]['name'] : ''; ?>" />
                <?php if (isset($error_name[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><input type="text" name="link_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" value="<?php echo isset($link_description[$language['language_id']]) ? $link_description[$language['language_id']]['description'] : ''; ?>" size="100" /></td>
            </tr>
          </table>
        </div>
		<?php } ?>
      </div>
      <div id="tab_data">
      <table class="form">
        <tr>
          <td><?php echo $entry_url; ?></td>
          <td>
          	<input type="text" name="uri" value="<?php echo $uri; ?>" size="100" />
          </td>
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
          <td>链接分类</td>
          <td>
          <div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($link_options as $result) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($result['value'], $link_group)) { ?>
                  <input type="checkbox" name="link_group[]" value="<?php echo $result['value']; ?>" checked="checked" />
                  <?php echo $result['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="link_group[]" value="<?php echo $result['value']; ?>" />
                  <?php echo $result['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
          </td>
       </tr>
       <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <option value="1" <?php if ($status==1) { ?> selected="selected"<?php } ?> ><?php echo $text_enabled; ?></option>
                <option value="0" <?php if ($status==0) { ?> selected="selected"<?php } ?> ><?php echo $text_disabled; ?></option>
              </select></td>
          </tr>
      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
      </tr>
      <tr>
        <td> </td>
        <td>
    	<div class="buttons">
    	  <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
    	  <a href="<?php echo $cancel; ?>" class="button"><span><?php echo $button_cancel; ?></span></a>
    	</div>
        </td>
      </tr>
      </table>
      </div>
    </form>
  </div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 

<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs();
//--></script> 