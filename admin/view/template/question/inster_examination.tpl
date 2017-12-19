<div class="box">
    <div class="heading">
      <h2><?php echo $heading_title;?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary">保存</button> </div>
    </div>
    <div class="content">
      <form action="index.php?route=question/question/insterinto/" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> 问卷名称</td>
            <td><input type="text" name="examination_title" value=" " maxlength="50" /></td>
          </tr>





        </table>
      </form>
    </div>
  </div>
