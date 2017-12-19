<?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <div class="btn-toolbar" >
      	<div class="pull-left">
      		<form class="inline-form">
			<?php echo $entry_date_start; ?>
	         <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" />
	        <?php echo $entry_date_end; ?>
	        <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" />
	     	<button onclick="filter();return false;" class="btn"><?php echo $button_filter; ?></button>
	     	</form>
      	</div>
      <div class="buttons pull-right span4 tr">
	        <button onclick="$('#form').attr('action', '<?php echo $clear; ?>'); $('#form').submit();" class="btn btn-warning"><?php echo $button_clear; ?></button>
	  	   &nbsp;
	      	<button onclick="$('#form').attr('action', '<?php echo $clearAll; ?>'); $('#form').submit();" class="btn btn-danger"><?php echo $button_clear_all; ?></button>
      </div>
     </div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;">
              <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
              </td>
              <td class="center span1"><?php echo $column_image; ?></td>
              <td class="left span6"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>" class="sort"><?php echo $column_name; ?></a>
                <?php } ?></td>
             
             <td class="left span2">
              <?php if ($sort == 'p.sku') { ?>
                <a href="<?php echo $sort_sku; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sku; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_sku; ?>" class="sort"><?php echo $column_sku; ?></a>
              <?php } ?>
             </td>
              
             <td class="left span1"><?php echo $column_status; ?></td>
             <td class="span1 right">
              <?php if ($sort == 'voted_good_num') { ?>
                <a href="<?php echo $sort_voted_num; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_voted_num; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_voted_num; ?>" class="sort"><?php echo $column_voted_num; ?></a>
              <?php } ?>
              </td>
            </tr>
          </thead>
          <tbody>
            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="border: 1px solid #DDDDDD;" /></td>
              <td class="left"><?php echo $product['name']; ?></td>
              <td class="left"><?php echo $product['sku']; ?></td>
              
              <td class="left"><?php echo $product['status']; ?></td>
              <td class="right">
	              <i style="font-size:18px;">
	              <?php echo $product['voted_good_num']; ?>
	              </i>
              </td>
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
      <?php if ($products) { ?>
      <div class="pagination"><?php echo $pagination; ?></div>
      <?php } ?>
    </div>
  </div>

<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/vote&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}
	
	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
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
			$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
			
			$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
			
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
			
    }
);

//--></script>