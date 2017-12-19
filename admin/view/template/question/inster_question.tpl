<div class="box">
    <div class="heading">
      <h2>新增问题</h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary">保存</button> <button onclick="location = '＃';" class="btn">##</button></div>
    </div>
    <div class="content">
      <form action="index.php?route=question/questionlist/insterinto/" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> 问题名称</td>
            <td><input type="text" name="question_title" value=" " maxlength="50" /></td>
              <td><input type="hidden" name="examination_id" value="<?php echo $examination_id;?>">
          </tr>

            <tr>
                <td><span class="required">*</span> 问题类型</td>
                <td><input type="radio" name="question_type" value="array" maxlength="50" checked/>下拉框</td>
                <td><input type="radio" name="question_type" value="array_radio" maxlength="50" />单选框</td>
                <td><input type="radio" name="question_type" value="input" maxlength="50" />输入框</td>
            </tr>
            <tr>
                <td><span class="required">*</span> 问题选项</td>
                <td>选项一：<input type="text" name="question_value_1" value="" maxlength="50" /><a href="javascript:void()" onclick="add();">增加</a></td>
            </tr>

            <tr id="add_param">

            </tr>



        </table>
      </form>
    </div>
  </div>


<script>
    var i=30;
    function add(){
        i++;
        var content="<tr><td><input type='text' name='question_value_"+i+"'><a href='javascript:void()' onclick='remove_param(this)'>移除参数</a></td></tr>"
        $('#add_param').append(content);
    }



    function remove_param(obj){
        $(obj).parent().remove();
    }



</script>
