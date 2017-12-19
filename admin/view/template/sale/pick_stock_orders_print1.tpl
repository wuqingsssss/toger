<div class="box">
    <div class="heading">
        <h2><img src="view/image/order.png" alt=""/> <?php echo $heading_title . " - 菜品打印" ?>
        </h2>


        <div class="buttons noprint" style="margin-bottom:10px;">
            <button onclick="window.print();" class="btn" id="print-btn">打印</button>
            <button type="button" onclick="history.go(-1)" class="btn">返回</button>
        </div>
    </div>
    <div class="content">
        <div class="vtabs noprint">
            <a href="#tab-product">菜品列表</a>
        </div>


        <div id="tab-product" class="vtabs-content i-main-print-zone i-force-print-full-width">
            <table class="form">
                <tr class="highlight">
                    <?php $now = new DateTime(); ?>
                    <td class="left" style="width: 25%">打印时间 : <?php echo $now->format('Y-m-d H:i:s') ?></td>
                </tr>
            </table>

            <table id="products-tbl" class="list">
                <thead>
                <tr>
                    <td style="white-space: nowrap;">序号</td>
                    <td class="left" style="width: 40%">菜品名称</td>
                    <td class="left" style="width: 20%"><?php echo $column_quantity; ?></td>
                    <td class="left" style="width: 20%">单位</td>
                    <td class="left" style="width: 20%">来源</td>
                </tr>
                </thead>
                <?php if (!empty($rows)) { ?>
                    <?php foreach ($rows as $index=>$item) { ?>
                        <tbody>
                        <tr>
                            <td class="center"><?php echo $index+1;?></td>
                            <td class="left">
                                <a href="<?php echo $link_site_product_detail_prefix . $item['product_id']; ?>"
                                   class="popup"><?php echo $item['name']; ?></a>
                            </td>

                            <td class="left"><?php echo $item['num']; ?></td>
                            <td class="left"><?php echo $item['unit']; ?></td>
                            <td class="left"><?php echo EnumPartners::getPartnerInfo($item['partner_code']); ?></td>
                        </tr>
                        </tbody>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="9"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
            </table>

        </div>
    </div>
</div>


<script type="text/javascript">
    //    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.vtabs a').tabs();

    var hasRows =<?php echo empty($rows)?'false':'true' ?>;

    $(document).ready(function () {
        if (hasRows) {
            $('#print-btn').click();
        }
    });

</script>
