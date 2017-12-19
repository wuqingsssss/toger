<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>
        <div class="buttons"><button onclick="location = 'index.php?route=question/question/inster'" class="btn btn-primary">新增</button>
            <button onclick="del();" class="btn">del</button></div>
    </div>
    <div class="content">
        <form action="?delurl" method="post" enctype="multipart/form-data" id="form">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th class="left"><?php echo $column_name; ?></th>
                    <th class="right"><?php echo $column_action; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($question_examination_info as $a_question_examination_info) { ?>
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" name="selected" value="<?php echo $a_question_examination_info['id']; ?>" />
                        </td>
                    <td class="left"><?php echo $a_question_examination_info['examination_name']; ?></td>
                    <td class="right span2"><a href="index.php?route=question/questionlist&id=<?php echo $a_question_examination_info['id'];?>" class="btn btn-primary">查看</a></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>

</div>

<script>
    function del(){

        var ids="";
        var datas="";
        $('[name="selected"]:checked').each(function(i,o){
            ids= $(o).val()+","+ids;
        });
        datas="ids="+ids;


        $.ajax({
            type: 'POST',
            url: 'index.php?route=question/question/del',
            data: datas,
            dataType: 'json',
            success: function(json) {
                if(json['success']){
                    alert("del_ok");
                }
            }

        });


        //alert(datas);
    }



</script>