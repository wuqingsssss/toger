<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<?php if ($success) { ?>
<div class="alert alert-success"><?php echo $success; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>

<div class="box">
    <div class="heading">
      <h2><?php echo $heading_title;?></h2>
      <div class="buttons"><button onclick="$('#form').submit();" class="btn btn-primary">保存</button> </div>
    </div>
    <div class="content">
      <form action="index.php?route=sale/coupon_dist_admin/insterinto" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> 特权码</td>
            <td><input type="text" name="conpon_title" value=" " maxlength="50" /></td>
          </tr>
            <tr>
                <td><span class="required"></span> 导入文件</td>
                <td><input type="file" name="conpon_file" value="" maxlength="50" /></td>
            </tr>

            <tr>
                <td><span class="required">*</span> 用户电话</td>
                <td id="add_param"><input type="text" name="conpon_value_1" value="" maxlength="50" /><a href="javascript:void()" onclick="add();">增加</a></td>
            </tr>
        </table>

    </div>
  </div>


<script>
    var i=30;
    function add(){
        i++;
        var content="<tr><td><input type='text' name='conpon_value_"+i+"'><a href='javascript:void()' onclick='remove_param(this)'>移除参数</a></td></tr>"
        $('#add_param').append(content);
    }



    function remove_param(obj){
        $(obj).parent().remove();
    }



</script>
