<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="heading">
    <h1><img src="view/image/backup.png" alt="" /> <?php echo $heading_title; ?></h1>
   <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a></div>
 </div>
  <div class="content">
 	<div id="tab_general">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
           <table class="form">
                       <tr>
              <td>图片关联更新<td>
                  <span class="help">选择品牌,分类,以及特定的产品,可以实现批量关联图片,选择全部为关联所有产品图片.(注意,大小写区分,且目录结构为:分类ID/货号/货号.jpg,货号-F1.jpg)</span></td>
              </td>
            </tr>
             <tr>
              <td>关联所有</td>
              <td><select name="all">
                  <option value="0" selected="selected">否</option>
                  <option value="1" >是</option>
                  <option value="2" >仅关联最近更新</option>
                </select>
               </td>
            </tr>
            <tr>
              <td>品牌</td>
              <td><select name="manufacturer_id">
                  <option value="0" selected="selected"><?php echo $text_none; ?></option>
                  <?php foreach ($manufacturers as $manufacturer) { ?>
                 		<option value="<?php echo $manufacturer['manufacturer_id']; ?>"><?php echo $manufacturer['name']; ?></option>
                
                  <?php } ?>
                </select>
               </td>
            </tr>
            <tr>
              <td>分类</td>
              <td><div class="scrollbox">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($categories as $category) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                  	 <input type="checkbox" name="categories[]" value="<?php echo $category['category_id']; ?>" />
                   	 (<?php echo $category['category_id']; ?>) <?php echo $category['name']; ?>
              	 </div>
                  <?php } ?>
                </div></td>
            </tr>
            <tr>
              <td>产品</td>
              <td><input type="text" name="prod" value="" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><div class="scrollbox" id="products">
                  <?php $class = 'odd'; ?>
                </div>
              </td>
            </tr>

           </table>
        </form>  
        </div>
      </div>
  </div>

<script type="text/javascript"><!--
$('input[name=\'prod\']').autocomplete({
	delay: 0,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
		
	}, 
	select: function(event, ui) {
		$('#products' + ui.item.value).remove();
		
		$('#products').append('<div id="products' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" /><input type="hidden" name="products[]" value="' + ui.item.value + '" /></div>');

		$('#products div:odd').attr('class', 'odd');
		$('#products div:even').attr('class', 'even');
				
		return false;
	}
});

$('#products div img').live('click', function() {
	$(this).parent().remove();
	
	$('#products div:odd').attr('class', 'odd');
	$('#products div:even').attr('class', 'even');	
});
//--></script>