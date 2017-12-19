<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <div class="btn-toolbar" >
      <div class="buttons">
      	<button onclick="location = '<?php echo $insert; ?>'" class="btn btn-primary"><?php echo $button_insert; ?></button>
      </div>
      <div class="btn-group">
          <button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><?php echo $button_batch;?> <span class="caret"></span></button>
          <ul class="dropdown-menu">
            <li><a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').submit();"  ><?php echo $button_delete; ?></a></li>
      <!-- 
            <li><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" ><?php echo $button_copy; ?></a></li>
            <li><a onclick="$('#form').attr('action', '<?php echo $disabled; ?>'); $('#form').submit();" ><?php echo $button_disable;?></a></li>
            <li><a onclick="$('#form').attr('action', '<?php echo $enabled; ?>'); $('#form').submit();" ><?php echo $button_enable;?></a></li>
	   -->
          </ul>
	  </div>
 	 </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      	<input type="hidden" id="pr_id" name="pr_id" value="<?php echo $pr_id;?>"  />
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;">
              <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" />
              </td>
              <td class="center"><?php echo $column_image; ?></td>
              <td class="left span5"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>

             <td><?php echo '分类';?></td>
             <td class="left span2"><?php if ($sort == 'p.sku') { ?>
                <a href="<?php echo $sort_sku; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sku; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sku; ?>"><?php echo $column_sku; ?></a>
              <?php } ?></td>
              <td class="right span1"><?php if ($sort == 'p.price') { ?>
                <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'p.quantity') { ?>
                <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'p.use_quantity') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_use_quantity; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_use_quantity; ?></a>
                <?php } ?></td>
                  <td class="left"><?php if ($sort == 'p.group') { ?>
                <a href="<?php echo $sort_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_group; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_group; ?>"><?php echo $column_group; ?>分组</a>
                <?php } ?></td>
                  <td class="left"><?php if ($sort == 'p.sort_order') { ?>
                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($sort_order); ?>"><?php echo $column_sort_order; ?>排序</a>
                <?php } else { ?>
                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?>排序</a>
                <?php } ?></td>
              <td class="left span2"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter" >
              <td></td>
              <td></td>
              <td><input type="text" name="filter_name" class="span4"  value="<?php echo $filter_name; ?>" /></td>
              <td><select id="filter_category_id" name="filter_category_id">
              	<option value=""><?php echo $column_all; ?> </option>
				<?php foreach ($categories as $category) { ?>
					<?php if(isset($filter_category_id)&&$filter_category_id==$category['category_id']){?>
					<option value="<?php echo $category['category_id']?>" selected="selected"><?php echo $category['name'] ?> </option>
					<?php }else{?>
					<option value="<?php echo $category['category_id']?>"><?php echo $category['name'] ?> </option>
					<?php }?>
				<?php }?>
			</select></td>
              <td><input type="text" name="filter_sku" class="span2" value="<?php echo $filter_sku; ?>" /></td>
              <td align="right"><input type="text" class="span1" name="filter_price" value="<?php echo $filter_price; ?>" /></td>
              <td align="right"><input type="text" class="span1" name="filter_quantity" value="<?php echo $filter_quantity; ?>" style="text-align: right;" /></td>
               <td></td>
                <td align="right"><input type="text" class="span1" name="filter_group" value="<?php echo $filter_group; ?>" style="text-align: right;" /></td>
              <td></td>
              <td align="left"><button onclick="filter();return false;" class="btn"><?php echo $button_filter; ?></button></td>
            </tr>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="height:60px;width:60px;padding: 1px; border: 1px solid #DDDDDD;" /></td>
              <td>
              	<a href="index.php?route=catalog/product/update&token=<?php echo $token; ?>6&product_id=<?php echo $product['product_id']; ?>" class="button"><span><?php echo $product['name']; ?></span></a>
              </td>
              <td class="left"></td>
              <td class="left"><?php echo $product['sku']; ?></td>
              <td class="right">
			    <?php if ($product['special']) { ?>
                <span style="text-decoration:line-through"><?php echo $product['price']; ?></span><br/><span style="color:#b00;"><?php echo $product['special']; ?></span>
                <?php } else { ?>
			    <?php echo $product['price']; ?>
              <?php } ?>
              </td>
              <td class="right">
              <?php if($product['has_option'] == 0){ ?>
              <?php if ($product['quantity'] <= 0) { ?>
                <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                <?php } elseif ($product['quantity'] <= 5) { ?>
                <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                <?php } else { ?>
                <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                <?php } ?>
              <?php } else {?>
              <span style="color: #FFA500;"><?php echo $column_label_hasoption; ?></span>
              <?php } ?>
               </td>
              <td class="left"><?php echo $product['useQuantity']; ?></td>
                <td class="left"><?php echo $product['pr_group']; ?></td>
                  <td class="left"><?php echo $product['p_sort_order']; ?></td>
              <td class="left">
             
                <?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="11"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
<!-- wordpress form start -->
<?php if (isset($linkpost_wordpress)) { ?>
<section>
		<div id="wpModal" class="modal hide fade form-horizontal" style="display:none">
			<div class="modal-header">
				<h2><?php echo $button_linkpost_wordpress;?></h2>
			</div>
			<div class="modal-body">
				<fieldset>
					<span id="wp_cats">loading...</span>
				</fieldset>
			</div>
			<div class="modal-footer">
				<input type="button" onclick="$('#form').submit();" class="btn btn-primary" value="Go">
				<input type="button" class="btn" value="Close" data-dismiss="modal">
			</div>
		</div>
</section>
<?php } ?>
<!-- wordpress form end -->
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=promotion/zerobuy&token=<?php echo $token; ?>&pr_id=<?php echo $pr_id;?>';
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	var filter_group = $('input[name=\'filter_group\']').attr('value');
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_model = $('input[name=\'filter_model\']').attr('value');
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	var filter_sku = $('input[name=\'filter_sku\']').attr('value');
	
	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}
	
	var filter_price = $('input[name=\'filter_price\']').attr('value');
	
	if (filter_price) {
		url += '&filter_price=' + encodeURIComponent(filter_price);
	}
	
	var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');
	
	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	var filter_category_id = $('select[name=\'filter_category_id\']').attr('value');
	if (filter_category_id) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
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
<script type="text/javascript"><!--
$(document).ready(
		function(){

			$('.menu li').click(function(){
				$('.showmenu a').html($(this).find('span').html());
				
				$('#filter_category').val($(this).attr('data-id'));
			});
			
			
			$('input[name=\'filter_name\']').autocomplete({
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
					$('input[name=\'filter_name\']').val(ui.item.label);
									
					return false;
				}
			});

			$('input[name=\'filter_model\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.model,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(event, ui) {
					$('input[name=\'filter_model\']').val(ui.item.label);
									
					return false;
				}
			});


			$('input[name=\'filter_sku\']').autocomplete({
				delay: 0,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_sku=' +  encodeURIComponent(request.term),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.sku,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(event, ui) {
					$('input[name=\'filter_sku\']').val(ui.item.label);
									
					return false;
				}
			});
    }
);

//--></script>
