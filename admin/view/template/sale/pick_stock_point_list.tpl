<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2> <?php echo $heading_title; ?></h2>

        <div class="buttons">
        </div>
    </div>
    <div class="content">
        <form action="index.php" method="get" enctype="multipart/form-data" id="form" >
            <input type="hidden" name="route" value="sale/pick_stock"/>
            <table class="list">
                <thead>
                <tr>
                    <td>自提点</td>
                    <td class="left">地址</td>
                    <td class="right"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <tr class="filter" id="search_filter">
                    <td>
                        <input type="text" class="span6" name="filter_name"
                               value="<?php echo $filter_name; ?>"/>
                    </td>
                    <td align="left">
                    </td>
                    <td align="right"><a onclick="$('#form').submit();"
                                         class="button"><span><?php echo $button_filter; ?></span></a></td>
                </tr>
                <?php if ($points) { ?>
                    <?php foreach ($points as $item) { ?>

                        <tr>
                            <td class="left"><?php echo $item['name']; ?></td>
                            <td class="left"><?php echo $item['address']; ?></td>
                            <td class="right">
                                [ <a href="index.php?route=sale/pick_stock/orders&filter_point_id=<?php echo $item['point_id']; ?>">查看</a> ]
                            </td>
                        </tr>

                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>

<script type="text/javascript"><!--
//    function filter() {
//        url = 'index.php?route=sale/pick_stock';
//
//        var paramGropus = [];
//
//        $('#search_filter').find(':input').not(':button').each(function () {
//            var field$ = $(this);
//            var name = field$.attr('name');
//            var val = field$.val();
//            if (name && val) {
//                paramGropus.push(name + '=' + encodeURIComponent(val));
//            }
//        });
//        url += '&' + paramGropus.join('&');
//
//        window.location.href = url;
//    }
    //--></script>
<script type="text/javascript"><!--
    $(document).ready(function () {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    });
    //--></script>
<script type="text/javascript"><!--
//    $('#form input').keydown(function (e) {
//        if (e.keyCode == 13) {
//            filter();
//        }
//    });

    $('#form input[type="checkbox"]').each(function () {
        $(this).click(function () {
            var checkNum = $('#form input[name*="selected"]:checked').length;

            if (checkNum > 0) {
                $('#btn_shiporder').attr('disabled', false);
            } else {
                $('#btn_shiporder').attr('disabled', true);
            }
        });
    });
    //--></script>