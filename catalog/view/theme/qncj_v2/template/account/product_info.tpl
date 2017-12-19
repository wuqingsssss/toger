  <div class="box">
    <div class="heading">
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons"><a onclick="Pass();" class="btn btn-primary"><span><?php echo $text_pass ?></span></a>  <a onclick="NoPass();" class="btn btn-primary"><span><?php echo $text_no_pass; ?></span></a>&nbsp;<a onclick="location = '<?php echo $cancel; ?>';" class="btn"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs"><a href="#tab-general"><?php echo $tab_general; ?></a></div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <div id="tab-general">
            <table class="form">
             <tr>
              <td><?php echo $entry_category; ?></td>
              <td>
              <label><?php echo 'dd';?></label>
            </tr>
             <tr>
                <td><span class="required"></span> <?php echo $entry_number; ?></td>
                <td><label><?php echo $number; ?></label>
              </tr>
            
              <tr>
                <td><span class="required"></span> <?php echo $entry_name; ?></td>
                <td><label><?php echo $name; ?></label>
              </tr>
              
              <tr>
                <td><span class="required"></span> <?php echo $entry_trade_type; ?></td>
                <td>
                <label><?php echo $trade_type;?></label>
              </tr>
              
              <tr>
                <td><span class="required"></span> <?php echo $entry_industry_type; ?></td>
                <td>
                <label><?php echo $industry_type;?></label>
              </tr>
              
               <tr>
                <td><span class="required"></span> <?php echo $entry_local; ?></td>
                <td>
                <label><?php  echo $local_addr_zone;?></label><label><?php echo $local_addr_city;?></label>
              </tr>
              
               <tr>
                <td><span class="required"></span> <?php echo $entry_period; ?></td>
                <td>
                <label><?php echo $period; ?></label>
              </tr>
              
              <tr>
                <td><span class="required"></span> <?php echo $entry_status; ?></td>
                <td>
                <label><?php echo $project_status;?></label>
              </tr>
              
              <tr>
                <td><span class="required"></span> <?php echo $entry_price; ?></td>
                <td>
                <label><?php echo $price; ?></label>
              </tr>
              
              <tr>
                <td><?php echo $entry_description; ?></td>
                <td><?php echo  $description;?></textarea>
              </tr>
              <tr>
                <td><?php echo $entry_condition; ?></td>
                <td>
                <label><?php echo $conditions;?></label>
                </td>
              </tr>
              
              <tr>
              <td><?php echo $entry_supply_demand; ?></td>
              <td>
                  <?php if ($supply_demand == '0') { ?>
                  <lanel><?php echo $text_demand;?></label> 
                  <?php } else { ?>
                   <lanel><?php echo $text_supply;?></label> 
                  <?php } ?>
               </td>
                </tr>
              
              
              <tr>
                 <td><?php echo $entry_show; ?></td>
              <td>
                  <?php if ($status == '0') { ?>
                  <label><?php echo $text_disabled; ?></label>
                  <?php } else { ?>
                  <label><?php echo $text_enabled; ?></label>
                  <?php } ?>
                </select></td>
                
              </tr>
              
              <input type="hidden" id="verified" name="verified" value="<?php echo $verified?>"/>
            </table>
          </div>
          
        </div>
      </form>
    </div>
  </div>
  <script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
  <script type="text/javascript"><!--
CKEDITOR.replace('description1', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});

  function NoPass()
  {
	var verObj = document.getElementById('verified');
	verObj.value = 1;
	$('#form').submit();
  }
  function Pass()
  {
	var verObj = document.getElementById('verified');
	verObj.value = 0;
	$('#form').submit();
  }
//--></script>


  

