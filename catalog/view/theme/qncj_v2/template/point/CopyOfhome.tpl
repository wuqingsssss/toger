<style type="text/css">
  #ztd{  padding: 3px; background: #FFCC7D; width:600px;  }
  #ztd .ztd-content{ display: block;  padding:10px; background: rgb(255, 255, 255); overflow:hidden;  }
  #ztd .ztd-content-right{ }
  #ztd .ztd-content .tip{ font-size:18px; }
  
  #ztd input {}
  #location-form{ overflow:hidden; }
  #location-form .td1 input{ font-size:16px; width:100px; }
  #location-form .td{ float:left; display:inline-block; }
  #location-form .td.td1{ width:150px; }
  #location-form .td.td2{ width:80px; }
</style>
<div id="ztd">
  	<div class="ztd-content">
  		<div class="ztd-content-right">
            <p class="tip" style="margin-top:10px; margin-bottom:20px;">请选择您附近的自提点区域</p>
  		
  		<div class="step1" style="margin-top:10px;">
  		<form method="post" id="location-form">
  			<div class="td">
  			所在区域:<br />
            <div class="area-lists">
            	<?php foreach ($cities as $city) { ?>
                <a href="#area-content-<?php echo $city['city_id']; ?>" class="area-item" data-id="<?php echo $city['city_id']; ?>" title=""><?php echo $city['name']; ?></a>
                <?php } ?>
            </div>
           <?php foreach ($cities as $city) { ?> 
            <div id="area-content-<?php echo $city['city_id']; ?>">
            	
            </div>
            <?php } ?>
            
            
            
	  		<!--<select name="city_id" style="width:120px;" onchange="$('select[name=\'cbd_id\']').load('index.php?route=common/localisation/cbd&city_id=' + this.value);">
	  			<option value=""><?php echo $text_select; ?></option>
	                <?php foreach ($cities as $city) { ?>
	                  <?php if ($city['city_id'] == $city_id) { ?>
	                  <option value="<?php echo $city['city_id']; ?>" selected="selected"><?php echo $city['name']; ?></option>
	                  <?php } else { ?>
	                  <option value="<?php echo $city['city_id']; ?>"><?php echo $city['name']; ?></option>
	                  <?php } ?>
	                  <?php } ?>
	  		</select>
  			-->
  			
  			</div>
  			<div class="td td1">
  				所在商圈:<br />
		  		<select name="cbd_id" style="width:120px;">
		  			<option value=""><?php echo $text_select; ?></option>
		  		</select>
  			</div>
  			<div class="td td2">
  				<br />
  				<input style="margin-left:5px;" type="button" class="btn btn-primary" value="确定" onclick="set_default_location()" />
  			</div>
  			
  		</form>
  		</div>
  		</div>
  	</div>
    
</div>
<script type="text/javascript"><!--
	$('select[name=\'cbd_id\']').load('index.php?route=common/localisation/cbd&city_id=<?php echo $city_id; ?>&cbd_id=<?php echo $cbd_id; ?>');
//--></script> 
	
<script type="text/javascript"><!--
function set_default_location(){
	var filter_city_id = $('select[name=\'city_id\']').attr('value');

	if(!filter_city_id){
		alert('请选择所在区域');
		return;
	}

	var filter_cbd_id = $('select[name=\'cbd_id\']').attr('value');
	
	if(!filter_cbd_id){
		alert('请选择所在商圈');
		return;
	}

	$.ajax({
		url: '<?php echo $action; ?>',
		type: 'post',
		data: $('#location-form').serialize(),
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}
			 
			if (json['redirect']) {
//				window.location='index.php?route=checkout/checkout';
				$('#shipping-method .shipping-method-list').load('index.php?route=checkout/checkout/shipping_method_load');
			}	 
						
			if (json['success']) {
				$.cookie('point_city_id', filter_city_id); 
				$.cookie('point_cbd_id', filter_cbd_id); 
				$.fancybox.close();
				return;
			}	
		}
	});
}

</script>
  
  