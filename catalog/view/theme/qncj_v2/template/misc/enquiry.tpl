<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
<?php echo $content_top; ?>
<div class="breadcrumb">
    <a href="/">首页</a> 》 <a>发布询价单</a> 
</div>
<h1>发布询价单</h1>
<form action="<?php echo $action; ?>" method="post" id="enquiry-form">
<div id="post-buy">
<div class="basic">
	<h1>发布询价单</h1>
	<div id="item-region" class="tr fd-clr">
    <div class="key">
        &nbsp;
    </div>
	    <div class="value">
	        <table id="module">
	           	<thead>
	            <tr>
	                <td style="width: 345px;"><em class="red">*</em><span class="orange"> 产品名称</span></td>
	                <td style="width: 123px;"><em class="red">*</em><span class="orange"> 产品数量</span></td>
	                <td style="width: 104px;"><em class="red">*</em><span class="orange"> 单位</span></td>
	                <td style="width: 120px;">目标单价</td>
	            </tr>
	            </thead>
	            <?php if($products) {?>
	            <?php $module_row=0; ?>
	        	<?php foreach ($products as $result) { ?>
	            <tbody id="module-row<?php echo $module_row; ?>">
	            	<tr><td colspan="5" height="10"></td></tr>
	            <tr>
	                <td style="position: relative;">
	                    <input type="text" name="product[<?php echo $module_row; ?>][name]" class="text subject key-cnt" style="width: 317px;" value="" data-name="offerName" placeholder="如:采购某某药品" maxlength="30" />
	                </td>
	                <td>
	                    <input type="text" name="product[<?php echo $module_row; ?>][quantity]" style="width: 86px;" value="" class="text amount shadow key-cnt" data-name="amount" placeholder="数量" />
	                </td>
	                <td>
	                    <input type="text"name="product[<?php echo $module_row; ?>][unit]" style="width: 65px;" value="" class="text unit shadow key-cnt" data-name="unit" placeholder="单位" maxlength="10" />
	                </td>
	                <td>
	                    <input type="text" data-name="targetPrice" name="product[<?php echo $module_row; ?>][price]" style="width: 74px;" value="" class="text unit shadow key-cnt" maxlength="10" />
	                </td>
	                <td></td>
	            </tr>
	        	</tbody>
	        	<?php $module_row++; ?>
	        	<?php } ?>
	        	<?php } else {?>
	        	<?php $module_row=0; ?>
	        	<tbody id="module-row<?php echo $module_row; ?>">
	        		<tr><td colspan="5" height="10"></td></tr>
	            <tr>
	                <td style="position: relative;">
	                    <input type="text" name="product[<?php echo $module_row; ?>][name]" class="text subject key-cnt" style="width: 317px;" value="" data-name="offerName" placeholder="如:采购某某药品" maxlength="30" />
	                </td>
	                <td>
	                    <input type="text" name="product[<?php echo $module_row; ?>][quantity]" style="width: 86px;" value="" class="text amount shadow key-cnt" data-name="amount" placeholder="数量" />
	                </td>
	                <td>
	                    <input type="text"name="product[<?php echo $module_row; ?>][unit]" style="width: 65px;" value="" class="text unit shadow key-cnt" data-name="unit" placeholder="单位" maxlength="10" />
	                </td>
	                <td>
	                    <input type="text" data-name="targetPrice" name="product[<?php echo $module_row; ?>][price]" style="width: 74px;" value="" class="text unit shadow key-cnt" maxlength="10" />
	                </td>
	                <td></td>
	            </tr>
	        	</tbody>
	        	<?php $module_row++; ?>
	        	<?php } ?>
	        	<tfoot>
	        		<tr><td colspan="5" height="10"></td></tr>
			         <tr>
			            <td colspan="3"></td>
			            <td colspan="2" class="left"><a onclick="addModule();" class="btn btn-primary"><span>添加商品</span></a></td>
			          </tr>
	        	</tfoot>
	    	</table>
	    </div>
    </div>
		<div class="tr upload fd-clr">
	    <div class="key orange">
	        上传附件
	    </div>
	    <div class="value">
	        <div class="fd-left ui-flash" id="upload-btn" title="可以上传JPG、GIF、PNG、PDF和Office文档，且大小在2M以内。">
	        	<div style="width: 65px; height: 22px;">
	        		<input type="file" name="upload" hidefocus="true" autocomplete="off">
	        	</div>
	        </div>
	        <span class="eg important">&nbsp;&nbsp;(上传采购产品的相关图片、图纸、文档，以便供应商进行报价                )</span>    
	        <div class="uploader-file-list fd-clr"></div></div>
	    <input type="hidden" id="attachments" name="_fm.p._0.a" value="">
	</div>
	<div class="tr fd-clr">
	    <div class="key orange">
	        详细说明
	    </div>
	    <div class="value">
	        <textarea id="offerContent" name="description" class="shadow" data-name="offerContent" vg="1" aria-hidden="true" style="width:640px; height:200px;"></textarea>
	    </div>
	           
	</div>
	<div class="tr tr-pad0">
	    <div class="key">
	        <b>&nbsp;</b>
	    </div>
	    <div class="value">
	        <div id="file-detail" class="fd-clr">
	        </div>
	    </div>
	</div>
	<div class="tr fd-clr">
	    <div class="key">
	        <b>*</b> 联系人
	    </div>
	    <div class="value">
	        <input type="text" name="name" value="" class="text shadow" data-name="contactName" maxlength="32" vg="1">
	            </div>
	</div>
	<div class="tr fd-clr">
	    <div class="key">
	        <b>*</b> 联系电话
	    </div>
	    <div class="value">
	        <input type="text" name="telephone" value="" class="text shadow" data-name="telephone" maxlength="30" vg="1">
	            <span class="eg important">
	                填写手机号可免费接受短信提醒             </span>
	            </div>
	
	</div>
	<div class="tr fd-clr">
	    <div class="key">&nbsp;</div>
	    <div class="value">
	       <input type="submit" class="button" value="确认并提交询价单" />
	    </div>
	</div>
	
	

</div>

</div>
</form>
<?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '<tr><td colspan="5" height="10"></td></tr>';
	html += '  <tr>';
	html += '    <td><input type="text" name="product[' + module_row + '][name]" class="text subject key-cnt" id="offerName" style="width: 317px;" value="" data-name="offerName" placeholder="如:采购某某药品" maxlength="30"></td>';
	html += '    <td><input type="text" name="product[' + module_row + '][quantity]" style="width: 86px;" value="" class="text amount shadow key-cnt" data-name="amount" placeholder="数量" /></td>';
	html += '    <td><input type="text" name="product[' + module_row + '][unit]" style="width: 65px;" value="" class="text unit shadow key-cnt" data-name="unit" placeholder="单位" maxlength="10" /></td>';
	html += '    <td><input type="text" data-name="targetPrice" name="product[' + module_row + '][price]" style="width: 74px;" value="" class="text unit shadow key-cnt" maxlength="10" /></td>';
	html += '    <td class="right"><a onclick="$(\'#module-row' + module_row + '\').remove();"><span>移除</span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
	<?php echo $footer; ?>