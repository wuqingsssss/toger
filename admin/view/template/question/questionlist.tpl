<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>


        <div class="buttons"><button onclick="location = 'index.php?route=question/questionlist/inster&examination_id=<?php echo  $examination_id;?>'" class="btn btn-primary">新增</button>
            <button onclick="del();" class="btn">del</button></div>
    </div>
    <div class="content">
        <form action="?delurl" method="post" enctype="multipart/form-data" id="form">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th class="left">问题</th>
                    <th class="left">答案选项</th>
                    <th class="left">类型</th>
                    <th class="right"><?php echo $column_action; ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($question_info as $a_question_info) { ?>
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" name="selected" value="<?php echo $a_question_info['id']; ?>" />
                    </td>
                    <td class="left"><?php echo $a_question_info['question_title']; ?></td>

                    <td class="left">
                        <?php
                        $tmp=$a_question_info['question_value'];
                        if($tmp){
                        $tmp_array=json_decode($tmp,true);
                        $strs=array();
                        foreach($tmp_array as $key=>$value){
                        $strs[]=$value;

                        }
                        $end_strs=implode(",",$strs);
                        }else{
                        $end_strs='null';
                        }
                        echo $end_strs;
                        ?>
                    </td>

                    <td class="left"><?php echo $a_question_info['question_type']; ?></td>
                    <td class="right span2"><a href="index.php?route=question/questionlist/edit_view&id=<?php echo $a_question_info['id'];?>" class="btn btn-primary">修改</a></td>
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
            url: 'index.php?route=question/questionlist/del',
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
