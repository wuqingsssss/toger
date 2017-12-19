<div class="box">
    <div class="heading">
      <h2>修改问题</h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary">保存</button> <button onclick="location = '#';" class="btn">##</button></div>
    </div>
    <div class="content">
      <form action="index.php?route=question/questionlist/update_view" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> 问题名称</td>
            <td><input type="text" name="question_title" value="<?php echo $question_info[0]['question_title']; ?>" maxlength="50" /></td>
          <input type="hidden" name="question_id" value="<?php echo $question_info[0]['id'];?>">
          </tr>

            <?php
            $tmp=$question_info[0]['question_value'];
            $tmp_array=json_decode($tmp,true);
            $i=0;
            foreach($tmp_array as $key=>$value){
            $i=$i+1; ?>
            <tr>
                <td><span class="required">*</span> 问题选项</td>
                <td><input type="text" name="question_value_<?php echo $i;?>" value="<?php echo $value; ?>" maxlength="50" /></td>
            </tr>

<?php }?>

        </table>
      </form>
    </div>
  </div>
