<div id="period">
<div class="wrap">
        <div class="htabs2">
        	<?php foreach ($supply_periods as $key => $supply_period) { ?>
	            <a href="javascript:get_product_home(<?php echo $key;?>,0)" title="<?php echo $supply_period['name']; ?>" <?php if($sequence==$key){ ?>class="selected"  <?php }?>><?php echo $supply_period['name'];?> <span class="period-date">（<?php echo date("m-d",strtotime($supply_period['ps_start_date']));?> - <?php echo date("m-d",strtotime($supply_period['ps_end_date']));?> ）</span></a>
        	<?php }?>
        </div>
    </div>
</div>

<?php echo $this->getChild('product/product/filter',array('filter_category_id'=>$filter_category_id));?>

<?php if ($products) { ?>
<div id="filter_contents" >
    <div class="wrap">
        <div id='product_list' class="product-grid">
            <?php include 'catalog/view/theme/'.$this->config->get('config_template').'/template/product/ilex_product_list.php'; ?>
        </div>
    </div>
</div>
<?php } ?>
</div>