<div class="box">
    <div class="heading">
      <h2><?php echo $heading_title;?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary">生成</button> </div>
    </div>
    <div class="content">
      <form action="index.php?route=question/erweima/insterinto/" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> 小区id</td>
            <td><input type="text" name="buildingid" value=" " maxlength="50" /></td>
          </tr>





        </table>
      </form>

        <textarea rows="10" cols="30"> <?php echo $url;?>   </textarea>
    </div>




</div>
