
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <div class="buttons">
      <a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_save; ?></span></a>
      <a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
      <a href="#tab-general"><?php echo $tab_general; ?></a>
      <a href="#tab-link"><?php echo $tab_links; ?></a>
      <a href="#tab-seo"><?php echo $tab_seo_setting; ?></a>
      <a href="#tab-design"><?php echo $tab_design; ?></a>
     </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
         <?php if(count($languages)>1) {?>
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
              <td><input type="text" name="article[<?php echo $language['language_id']; ?>][title]" class="input-xxlarge" value="<?php echo isset($article[$language['language_id']]) ? $article[$language['language_id']]['title'] : ''; ?>" />
                <?php if (isset($error_title[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td class="vTop"><?php echo $entry_description; ?></td>
              <td>
			  <?php if (isset($error_content[$language['language_id']])) { ?>
                <span class="error"><?php echo $error_content[$language['language_id']]; ?></span>
                <?php } ?>
			  <textarea name="article[<?php echo $language['language_id']; ?>][content]" id="content<?php echo $language['language_id']; ?>"><?php echo isset($article[$language['language_id']]) ? $article[$language['language_id']]['content'] : ''; ?></textarea></td>
            </tr>
            <tr>
              <td><?php echo $entry_summary; ?></td>
              <td>
			  <textarea rows="5" class="input-xxlarge" name="article[<?php echo $language['language_id']; ?>][summary]" id="summary<?php echo $language['language_id']; ?>"><?php echo isset($article[$language['language_id']]) ? $article[$language['language_id']]['summary'] : ''; ?></textarea></td>
            </tr>
			<tr>
              <td><?php echo $entry_tags; ?></td>
              <td><input type="text" name="article_tags[<?php echo $language['language_id']; ?>]" value="<?php echo isset($article_tags[$language['language_id']]) ? $article_tags[$language['language_id']] : ''; ?>" size="80"/></td>
            </tr>
          </table>
        </div>
		<?php } ?>
		<table class="form">
		 <tr>
            <td><?php echo $entry_category; ?></td>
            <td>
			 
			<div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($categories as $category) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($category['article_category_id'], $article_category)) { ?>
                  <input type="checkbox" name="article_category[]" value="<?php echo $category['article_category_id']; ?>" checked="checked" />
                  <?php echo $category['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="article_category[]" value="<?php echo $category['article_category_id']; ?>" />
                  <?php echo $category['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <?php if (isset($error_no_cate)) { ?>
                <span class="error"><?php echo $error_no_cate; ?></span>
              <?php } ?>
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
            <td><?php echo $entry_feature; ?></td>
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
              <td><?php echo $entry_editor; ?></td>
              <td><input type="text" name="editor" value="<?php echo $editor; ?>" maxlength="50" />

              </td>
            </tr>
            <tr>
              <td><?php echo $entry_date_added; ?></td>
              <td><input type="text" name="date_added" value="<?php echo $date_added; ?>" size="12" class="date" />

              </td>
            </tr>
            <tr>
	            <td><?php echo $entry_sort_order; ?></td>
	            <td><input type="number" class="input-mini"  name="sort_order" value="<?php echo $sort_order; ?>" /></td>
          	</tr>
        </table>
        </div>
        <div id="tab-seo">
        <table class="form">
		 <tr>
            <td><?php echo $entry_keyword; ?></td>
            <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" /></td>
          </tr>
        </table>
        <?php if(count($languages)>1) {?>
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
              <td><textarea class="input-xxlarge" name="article[<?php echo $language['language_id']; ?>][meta_keyword]" cols="40" rows="5"><?php echo isset($article[$language['language_id']]) ? $article[$language['language_id']]['meta_keyword'] : ''; ?></textarea></td>
            </tr>
	 
            <tr>
              <td><?php echo $entry_meta_description; ?></td>
              <td><textarea class="input-xxlarge" name="article[<?php echo $language['language_id']; ?>][meta_description]" cols="40" rows="5"><?php echo isset($article[$language['language_id']]) ? $article[$language['language_id']]['meta_description'] : ''; ?></textarea></td>
            </tr>
          </table>
        </div>
		<?php } ?>
		
        </div>
        <div id="tab-link">
           <table class="form">
		 <tr>
              <td><?php echo $entry_download; ?></td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($downloads as $download) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php if (in_array($download['download_id'], $article_download)) { ?>
                    <input type="checkbox" name="article_download[]" value="<?php echo $download['download_id']; ?>" checked="checked" />
                    <?php echo $download['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="article_download[]" value="<?php echo $download['download_id']; ?>" />
                    <?php echo $download['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div></td>
            </tr>

          <tr>
            <td><?php echo $entry_related; ?></td>
            <td><table>
                <tr>
                  <td style="padding: 0;" colspan="3">
                  <select id="category" style="margin-bottom: 5px;" onchange="getNews();">
                  	  <option value=""></option>
                      <?php foreach ($categories as $category) { ?>
                      <option value="<?php echo $category['article_category_id']; ?>"><?php echo $category['name']; ?></option>
                      <?php } ?>
                    </select></td>
                </tr>
                <tr>
                  <td style="padding: 0;">
                  <select multiple="multiple" id="article" size="10" style="width: 350px;">

                    </select></td>
                  <td style="vertical-align: middle;"><input type="button" value="--&gt;" onclick="addRelated();" />
                    <br />
                    <input type="button" value="&lt;--" onclick="removeRelated();" /></td>
                  <td style="padding: 0;"><select multiple="multiple" id="related" size="10" style="width: 350px;">
                    </select></td>
                </tr>
              </table>
              <div id="article_related">
                <?php foreach ($article_related as $related_id) { ?>
                <input type="hidden" name="article_related[]" value="<?php echo $related_id; ?>" />
                <?php } ?>
              </div></td>
          </tr>
        </table>
        </div>
         <div id="tab-design">
          <table class="list">
            <thead>
              <tr>
      			<td class="left"><?php echo $entry_layout; ?></td>
      			<td class="left"></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $text_default; ?></td>
                <td class="left"><select name="article_layout[0][layout_id]">
                    <option value=""></option>
                    <?php foreach ($layouts as $layout) { ?>
                    <?php if (isset($article_layout[0]) && $article_layout[0] == $layout['layout_id']) { ?>
                    <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
              </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>

<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('content<?php echo $language['language_id']; ?>', {
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
function addRelated() {
	$('#article :selected').each(function() {
		$(this).remove();

		$('#related option[value=\'' + $(this).attr('value') + '\']').remove();

		$('#related').append('<option value="' + $(this).attr('value') + '">' + $(this).text() + '</option>');

		$('#article_related input[value=\'' + $(this).attr('value') + '\']').remove();

		$('#article_related').append('<input type="hidden" name="article_related[]" value="' + $(this).attr('value') + '" />');
	});
}

function removeRelated() {
	$('#related :selected').each(function() {
		$(this).remove();
		$('#article_related input[value=\'' + $(this).attr('value') + '\']').remove();
	});
}

function getNews() {
	$('#article option').remove();

	$.ajax({
		url: 'index.php?route=catalog/article/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
		dataType: 'json',
		success: function(data) {
			for (i = 0; i < data.length; i++) {
	 			$('#article').append('<option value="' + data[i]['article_id'] + '">' + data[i]['title'] + '  </option>');
			}
		}
	});
}

function getRelated() {
	$('#related option').remove();

	$.ajax({
		url: 'index.php?route=catalog/article/related&token=<?php echo $token; ?>',
		type: 'POST',
		dataType: 'json',
		data: $('#article_related input'),
		success: function(data) {
			$('#article_related input').remove();

			for (i = 0; i < data.length; i++) {
	 			$('#related').append('<option value="' + data[i]['article_id'] + '">' + data[i]['title'] + ' </option>');

				$('#article_related').append('<input type="hidden" name="article_related[]" value="' + data[i]['article_id'] + '" />');
			}
		}
	});
}

getNews();
getRelated();
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
<?php if(COUNT($languages)>1) {?>
$('#languages a').tabs();
$('#seo_languages a').tabs();
<?php }?>
//--></script>
