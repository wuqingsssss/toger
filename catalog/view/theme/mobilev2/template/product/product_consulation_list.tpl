<div id="tabs" class="htabs mgt20">
<?php foreach($types as $result) {?>
<a href="#tab_<?php echo $result['value']; ?>"><?php echo $result['name']; ?></a>
<?php } ?>
</div>
<div id="consult">
<?php foreach($types as $result) {?>
	<div id="tab_<?php echo $result['value']; ?>" class="tab_content">
</div>
<?php } ?>
</div>
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();

$(document).ready(function(){
	//引入列表数据
	<?php foreach($types as $result) {?>
	$('#tab_'+<?php echo $result['value']; ?>).load('index.php?route=product/consulation/filter&product_id=<?php echo $product_id; ?>&type=<?php echo $result['value']; ?>');
	<?php } ?>
});
//--></script>
