
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">

  <div class="heading">
    <div class="buttons">
    <a onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><span><?php echo $button_insert; ?></span></a>
    <a onclick="$('form').submit();" class="btn btn-danger"><span><?php echo $button_delete; ?></span></a>
	</div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>

            <td class="left"><?php if ($sort == 'p.name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
		    <td class="left">
              <?php echo $column_category; ?>
            </td>
            <td class="left"><?php if ($sort == 'p.status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td><?php if ($sort == 'p.date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>

          <tr class="filter">
            <td></td>

            <td><input type="text" name="filter_title" value="<?php echo $filter_title; ?>" /></td>

			<td>
				<select name="filter_article_category_id">
					<option value="*"></option>
					<?php foreach($articlecates as $result) {?>
					<option value="<?php echo $result['article_category_id']; ?>" <?php if($filter_article_category_id==$result['article_category_id']) {?> selected="selected"<?php }?>><?php echo $result['name']; ?></option>
					<?php } ?>
				</select>
			</td>
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
            <td><input type="text" class="date" name="filter_date_added" value="<?php echo $filter_date_add; ?>" /></td>
            <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
          <?php if ($article) { ?>
          <?php foreach ($article as $new) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($new['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $new['article_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $new['article_id']; ?>" />
              <?php } ?></td>

            <td class="left"><?php echo $new['title']; ?></td>
            <td class="left"><?php echo $new['category']; ?></td>
            <td class="left"><?php echo $new['status']; ?></td>
            <td class="left"><?php echo $new['date_added']; ?></td>
            <td class="right"><?php foreach ($new['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/article&token=<?php echo $token; ?>';

	var filter_title = $.trim($('input[name=\'filter_title\']').attr('value'));

	if (filter_title) {
		url += '&filter_title=' + encodeURIComponent(filter_title);
	}

	var filter_article_category_id = $('select[name=\'filter_article_category_id\']').attr('value');

	if (filter_article_category_id != '*') {
		url += '&filter_article_category_id=' + encodeURIComponent(filter_article_category_id);
	}
	var filter_status = $('select[name=\'filter_status\']').attr('value');

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	var filter_date_added = $.trim($('input[name=\'filter_date_added\']').attr('value'));

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	location = url;
}
//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
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
