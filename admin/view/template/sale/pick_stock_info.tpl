<div class="box">
<div class="heading">
    <h2><img src="view/image/order.png" alt=""/> <?php echo $heading_title; ?></h2>

    <div class="buttons" style="margin-bottom:10px;">
        <a onclick="history.back();" class="btn"><span>返回</span></a></div>
    </div>
</div>
<div class="content">
<div class="vtabs"><a href="#tab-product"><?php echo $tab_product; ?></a>
</div>


<div id="tab-product" class="vtabs-content">
    <table class="form">
        <tr class="highlight">
            <?php if ($order_status) { ?>
                <td class="left"><?php echo $text_order_status; ?>  <?php echo $order_status; ?></td>
            <?php } ?>
            <td class="left"><?php echo $text_order_id; ?>#<?php echo $order_id; ?>
                <?php if ($p_order_id) { ?>
                    - (子订单)
                <?php } ?>
            </td>
            <td class="left">取菜日期 : <?php echo $pdate; ?></td>
            <?php if ($shipping_method) { ?>
                <td class="left">取菜地点 : <?php echo $shipping_method; ?></td>

            <?php } ?>
        </tr>
    </table>
    <table id="product" class="list">
        <thead>
        <tr>
            <td class="left"><?php echo $column_product; ?></td>
            <td class="left"><?php echo $column_model; ?></td>
            <td class="right"><?php echo $column_quantity; ?></td>
        </tr>
        </thead>
        <?php foreach ($products as $product) { ?>
            <tbody id="product-row<?php echo $product['order_product_id']; ?>">
            <tr>
                <td class="left"><?php if ($product['product_id']) { ?>
                        <a href="<?php echo $product['href']; ?>" class="popup"><?php echo $product['name']; ?></a>
                    <?php } else { ?>
                        <?php echo $product['name']; ?>
                    <?php } ?>
                    <?php foreach ($product['option'] as $option) { ?>
                        <br/>
                        <?php if ($option['type'] != 'file') { ?>
                            &nbsp;
                            <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } else { ?>
                            &nbsp;
                            <small> - <?php echo $option['name']; ?>: <a
                                    href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                        <?php } ?>
                    <?php } ?></td>
                <td class="left"><?php echo $product['model']; ?></td>
                <td class="right"><?php echo $product['quantity']; ?></td>
            </tr>
            </tbody>
        <?php } ?>
    </table>
</div>
</div>
</div>
<script type="text/javascript">
    $('.vtabs a').tabs();
</script>
