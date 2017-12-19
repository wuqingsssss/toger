
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1><?php echo $heading_title; ?></h1>
    <div class="buttons">
    
    </div>
  </div>
  <div class="content">
    <form action="<?php echo $category; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td colspan="3">处理数据采集的数据</td>
        </tr>
        <tr>
          <td width="25%">导入探索分类<span class="help">.txt文件支持</span></td>
          <td><input type="file" name="upload" /></td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td><a onclick="$('#form').submit();" class="btn btn-primary"><span><?php echo $button_import; ?></span></a></td>
        </tr>
      </table>
    </form>
    
    
    <form action="<?php echo $product; ?>" method="post" enctype="multipart/form-data" id="form2">
      <table class="form">
        <tr>
            <td>导入探索产品</td>
            <td>
            	注意事项:<br />
            	1 excel文件命名规则,例：tansoole_CSXYCMJPYDCM.xls格式命名，默认一个文件分类所属相同。<br />
            	2 请检查导入数据品牌是否在数据库存在，品牌不存在的时候默认不会绑定品牌
            </td>
          </tr>
           <tr>
          <td width="25%">&nbsp;</td>
          <td><input type="file" name="upload" /></td>
         </tr>
          <tr>
          	<td>&nbsp;</td>
          	<td><a onclick="$('#form2').submit();" class="btn btn-primary"><span>导入商品</span></a></td>
          </tr>
      </table>
    </form>
    
   <!--<form action="<?php echo $export; ?>" method="post" enctype="multipart/form-data" id="form2">
      <table class="form">
        <tr>
            <td>根据分类导出</td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($categories as $category) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
               		<input type="checkbox" name="category[]" value="<?php echo $category['category_id']; ?>" />
                  <?php echo $category['name']; ?>
                 </div>
                <?php } ?>
              </div></td>
          </tr>
          <tr>
          	<td>&nbsp;</td>
          	<td><a onclick="$('#form2').submit();" class="btn btn-primary"><span><?php echo $button_export; ?></span></a></td>
          </tr>
      </table>
    </form>
  --></div>
</div>
