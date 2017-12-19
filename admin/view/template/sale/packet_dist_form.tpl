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
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> 礼包名称</td>
            <td><input type="text" name="packetname" placeholder="输入礼包名称" value="<?php echo $packetname; ?>" maxlength="50" id="insert-field" />
                <input type="hidden" name="packet_id"  value=" "/>
            </td>
          </tr>
            <tr>
                <td><span class="required"></span> 导入文件</td>
                <td><input type="file" name="conpon_file" value="" maxlength="50" /></td>
            </tr>

            <tr>
                <td><span class="required">*</span> 用户电话</td>
                <td id="add_param"><input type="text" name="mobile_1" value="" maxlength="50" /><a href="javascript:void()" onclick="add();">增加</a></td>
            </tr>
        </table>

    </div>
  </div>


<script src="view/javascript/json3.min.js" type="text/javascript"></script>
<script src="view/javascript/handlebars-v2.0.0.js" type="text/javascript"></script>
<script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/jq.validate/1.13.1-pre/jquery.validate.js"
        type="text/javascript"></script>
        
<script>
    var i=30;
    function add(){
        i++;
        var content="<tr><td><input type='text' name='mobile_"+i+"'><a href='javascript:void()' onclick='remove_param(this)'>移除参数</a></td></tr>"
        $('#add_param').append(content);
    }



    function remove_param(obj){
        $(obj).parent().remove();
    }
</script>

<script type="text/javascript">
    $.widget('custom.catcomplete', $.ui.autocomplete, {
        _renderMenu: function (ul, items) {
            var self = this, currentCategory = '';

            $.each(items, function (index, item) {
                if (item.category != currentCategory) {
                    ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

                    currentCategory = item.category;
                }

                self._renderItem(ul, item);
            });
        }
    });
</script>

<script type="text/javascript"><!--

$(document).ready(function () {
	       function insertAutoComplete() {
	            var insertField$ = $('#insert-field');
	            insertField$.autocomplete({
	                delay: 0,
	                source: function (request, response) {
	                    $.ajax({
	                        url: 'index.php?route=sale/coupon_dist_admin/autocompletepacket',
	                        type: 'POST',
	                        dataType: 'json',
	                        data: 'packetname=' + encodeURIComponent(request.term),
	                        success: function (data) {
	                            response($.map(data, function (item) {
	                                console.log(item);
	                                return {
	                                    label: '['+item.batch+']'+item.name,
	                                    value: item.packet_id,
	                                    raw: item
	                                };
	                            }));
	                        }
	                    });
	                },	               
	                select: function (event, ui) {
	                	$('input[name=\'packetname\']').val(ui.item.label);
	                	$('input[name=\'packet_id\']').val(ui.item.value);
						
						return false;
						
	                }
	            }).bind("input.autocomplete", function () {
	                $(this).autocomplete("search", this.value);
	            });
	        }

	        insertAutoComplete();
});  

//--></script>