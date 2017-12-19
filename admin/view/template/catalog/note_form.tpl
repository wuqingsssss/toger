<?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <div class="buttons">
      <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="btn"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
      <a href="#tab-general"><?php echo $tab_general; ?></a>
     
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
          <?php if(count($languages) >1) {?>
          <div id="languages" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#language<?php echo $language['language_id']; ?>">
            <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
            </a>
            <?php } ?>
          </div>
          <?php } ?>
          
          <?php foreach ($languages as $language) { ?>
          <div id="language<?php echo $language['language_id']; ?>">
            <table class="form">
              <tr>
                <td><span class="required">*</span> <?php echo $entry_title; ?></td>
                <td><input type="text" name="note_description[<?php echo $language['language_id']; ?>][title]" size="100" maxlength="255" value="<?php echo isset($note_description[$language['language_id']]) ? $note_description[$language['language_id']]['title'] : ''; ?>" />
                  <?php if (isset($error_title[$language['language_id']])) { ?>
                  <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                  <?php } ?></td>
              </tr>
             
               <tr>
              <td><?php echo $entry_summary; ?></td>
              <td>
				  <textarea rows="5" class="input-xxlarge" name="note_description[<?php echo $language['language_id']; ?>][summary]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($note_description[$language['language_id']]) ? $note_description[$language['language_id']]['summary'] : ''; ?></textarea></td>
            </tr>
            </table>
          </div>
          <?php } ?>
          <table class="form">
            <tr>
              <td><?php echo $entry_code; ?></td>
              <td>
                <input type="text" name="code" value="<?php echo $code; ?>" size="50" />
              </td>
            </tr>
            <tr>
           
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
              <td><input type="number" class="input-mini" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
            </tr>
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
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
$('#languages a').tabs();

//--></script> 