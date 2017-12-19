<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
    <div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <div class="btn-toolbar">
            <!--            <div class="btn-group">
                <button class="btn btn-small dropdown-toggle" data-toggle="dropdown"><?php /*echo $button_batch; */ ?> <span
                        class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a onclick="$('#form').submit();"><?php /*echo $button_delete; */ ?></a></li>
                    <li>
                        <a onclick="$('#form').attr('action', '<?php /*echo $enabled; */ ?>'); $('#form').submit();"><?php /*echo $button_enable; */ ?></a>
                    </li>
                    <li>
                        <a onclick="$('#form').attr('action', '<?php /*echo $disabled; */ ?>'); $('#form').submit();"><?php /*echo $button_disable; */ ?></a>
                    </li>
                </ul>
            </div>-->
            <div class="buttons">
                <a href="<?php echo $insert?>" class="btn btn-primary">新增</a>
                <a class="btn btn-danger" id="remove-btn">删除</a>
            </div>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="list">
                <thead>
                <tr>
                    <td width="1" style="text-align: center;"><input type="checkbox"
                                                                     onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"/>
                    </td>
                    <td class="left span3">礼包名称</td>
                    <td class="left span3">展示开始</td>
                    <td class="left span3">展示结束</td>                 
                    <td class="left span3">礼包类型</td>
                    <td class="left span3">适用条件</td>
                    <td class="left span2">礼包批次</td>
                    <td class="right span3"><?php echo $column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($points) { ?>
                    <?php foreach ($points as $item) { ?>
                        <tr>
                            <td style="text-align: center;">
                                <?php if ($item['selected']) { ?>
                                    <input type="checkbox" name="selected[]" class="check-item"
                                           value="<?php echo $item['packet_id']; ?>" checked="checked"/>
                                <?php } else { ?>
                                    <input type="checkbox" name="selected[]" class="check-item"
                                           value="<?php echo $item['packet_id']; ?>"/>
                                <?php } ?>
                            </td>

                            <td class="left"><?php echo $item['name']; ?></td>
                            <td class="left"><?php echo date('Y-m-d', strtotime($item['date_start'])); ?></td>
                            <td class="left"><?php echo date('Y-m-d', strtotime($item['date_end'])); ?></td>
                            <td class="left"><?php if($item['type']==0){echo "有效期红包";}elseif($item['type']==1){echo "无有效期红包";}else{echo "宝箱";} ?></td>
                            <td class="left"><?php  if($item['cond']==0){echo "新注册用户";}else{echo "老用户单次";} ?></td>
                            <td class="left"><?php if($item['batch']==0){echo "无限次数";} else{echo $item['batch']."次";}?></td>
                            <td class="right">
                                [
                                <a href="index.php?route=catalog/packet/update&id=<?php echo $item['packet_id']; ?>">查看</a>
                                ]
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
        <div class="pagination"><?php echo $pagination; ?></div>
    </div>
</div>

<script src="view/javascript/json3.min.js" type="text/javascript"></script>
<script type="text/javascript">
    function getSelections() {

    }
    $('#remove-btn').click(function () {
        var checkedItems$ = $('.check-item:checked');
        if(!checkedItems$.length){
            return;
        }

        if(!confirm('确认删除所选项及与产品的关联？')){
            return;
        }

        var ids= $.map(checkedItems$, function (item) {
            return $(item).val();
        });

        $.ajax({
            url:'index.php?route=catalog/supply_period/delete',
            type: 'POST',
            dataType: 'json',
            data:{ids:JSON.stringify(ids)},
            success: function(data) {
//                console.log(data);
                window.location.reload();
            }

        });

    });
</script>
