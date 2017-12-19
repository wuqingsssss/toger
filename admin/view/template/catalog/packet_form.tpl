<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2><?php echo $heading_title; ?></h2>

        <div class="buttons">
            <button id="save-btn" data-loading-text="loading..." class="btn btn-primary">
                <?php echo $button_save; ?>
            </button>
            <input type="button" onclick="history.go(-1)" class="btn btn-default"
                   value="<?php echo $button_cancel; ?>">
        </div>
    </div>
    <div class="content">
        <div id="tabs" class="vtabs">
            <a href="#tab-general" class="first"><?php echo $tab_general; ?></a>
            <a href="#tab-products">优惠券信息</a>
        </div>
    
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"
              class="form-horizontal">
            <input type="hidden" name="productIds"/>

            <div id="tab-general" class="vtabs-content">
                <table class="form">
                	<tr>
                        <td><span class="required">*</span><?php echo $entry_name; ?></td>
                        <td><input  type="text" name="name"
                                   value="<?php echo $result['name']; ?>"
                                   required=""/></td>
                    </tr>

                
                    <tr>
                        <td><span class="required">*</span>开始展示时间:</td>
                        <td><input class="date" type="text" name="date_start"
                                   value="<?php echo(isset($result['date_start']) ? date('Y-m-d', strtotime($result['date_start'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span>结束展示时间:</td>
                        <td><input class="date" type="text" name="date_end"
                                   value="<?php echo(isset($result['date_end']) ? date('Y-m-d', strtotime($result['date_end'])) : ''); ?>"
                                   required=""/></td>
                    </tr>

                    <tr>
                        <td><span class="required">*</span><?php echo $entry_type; ?></td>
                        <td><select name="type">

                                <option value="0"<?php if ($result['type']=='0') { ?> selected="selected"<?php } ?>><?php echo $text_zero; ?></option>
                                <option value="1"<?php if ($result['type']=='1') { ?> selected="selected"<?php } ?>><?php echo $text_one; ?></option>
                                <option value="2"<?php if ($result['type']=='2') { ?> selected="selected"<?php } ?>><?php echo $text_two; ?></option>

                            </select></td>
                    </tr>
 <tr>
                        <td><span class="required">*</span>发放类型</td>
                        <td><select name="pick_type">
                                <option value="0"<?php if ($result['pick_type']=='0') { ?> selected="selected"<?php } ?>>一次全部发放</option>
                                <option value="1"<?php if ($result['pick_type']=='1') { ?> selected="selected"<?php } ?>>随机抽取发放</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span><?php echo $entry_cond; ?></td>
                        <td><select name="cond">

                                <option value="0"<?php if ($result['cond']=='0') { ?> selected="selected"<?php } ?>><?php echo $text_new; ?></option>
                                <option value="1"<?php if ($result['cond']=='1') { ?> selected="selected"<?php } ?>><?php echo $text_old; ?></option>
                                <option value="2"<?php if ($result['cond']=='2') { ?> selected="selected"<?php } ?>><?php echo $text_code; ?></option>
                            </select></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span><?php echo $entry_code; ?></td>
                        <td><input type="text" name="code" value="<?php echo $result['code']; ?>" /></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span><?php echo $entry_batch; ?><br/>
                        <span class="help"><?php echo $text_code_help; ?></span></td>
                        <td><input  type="text" name="batch"
                                    value="<?php echo $result['batch']; ?>"
                                    required=""/>次
                        </td> 
                    </tr>
                     <tr>
                        <td>分享标题</td>
                        <td><input name="share_title" value="<?php echo $result['share_title']; ?>"/>
                          </td>
                    </tr>
                     <tr>
                        <td>分享描述</td>
                        <td><input name="share_desc" value="<?php echo $result['share_desc']; ?>"/>
                       </td>
                    </tr>
                     <tr>
                     <td>分享图标</td>
                     <td valign="top"><input type="hidden" name="share_image" value="<?php echo $result['share_image']; ?>" id="share_image" />
                     <img src="<?php echo $preview; ?>" alt="" id="preview" class="image" onclick="image_upload('share_image', 'preview');" />
                    <div>
	                <a onclick="image_upload('share_image', 'preview');"><?php echo $text_browse; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
	                  <a onclick="$('#preview').attr('src', '<?php echo $no_image; ?>'); 
	                  $('#share_image').attr('value', '');"><?php echo $text_clear; ?></a>
	                </div>
                </td>
            </tr>
                      <tr>
                        <td>使用说明><br/>
                        <span class="help"><?php echo $text_code_help; ?></span></td>
                        <td> 
                        <textarea name="info" rows="6" width=350px><?php echo $result['info']; ?></textarea>
                        </td> 
                    </tr>
                    
                </table>
            </div>
            <div id="tab-products" class="vtabs-content">
                <div>
                     <span><strong><?php echo $packet_help; ?></strong></span>
                </div>
                <table class="list" id="products">
                    <thead>
                    <tr>
                        <td class="left">优惠券名称</td>
                        <td class="left">特权码</td>
                        <td class="left">折扣</td>
                        <td class="left">折扣类型</td>
                        <td class="right">开始日期</td>
                        <td class="right">结束日期</td>
                        <td class="right">操作</td>
                    </tr>
                    </thead>
                    <tbody id="main-tbody">
                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="7">
                            <input type="text" placeholder="输入名称" style="margin: 5px 0;width: 200px;" id="insert-field"/>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </form>

    </div>
</div>


<script src="view/javascript/json3.min.js" type="text/javascript"></script>
<script src="view/javascript/handlebars-v2.0.0.js" type="text/javascript"></script>
<script src="http://ilex-static.oss-cn-hangzhou.aliyuncs.com/lib/jq.validate/1.13.1-pre/jquery.validate.js"
        type="text/javascript"></script>
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
<script id="entry-template" type="text/x-handlebars-template">
    {{#each data}}
    <tr>
        <td class="left">{{name}}</td>
        <td class="left">{{code}}</td>
        <td class="left">{{discount}}</td>
        <td class="left">{{type}}</td>
        <td class="right">{{date_start}}</td>
        <td class="right">{{date_end}}</td>
        <td class="right">[<a class="remove-item" data-pid="{{code}}">删除</a>]</td>
    </tr>
    {{/each}}
</script>
<script type="text/javascript">
    var id = '<?php echo (isset($result['packet_id'])?$result['packet_id']:'') ?>';
    var productsData = <?php echo json_encode($products); ?>;

    $(document).ready(function () {
        $('#tabs a').tabs();
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});

        var tpl = $("#entry-template").html();
        var template = Handlebars.compile(tpl);

        function renderProducts() {
            var data = _.map(productsData, function (item) {
                var p = _.clone(item);

                return p;
            });
            $('#main-tbody').html(template({data: data}));
        }

        if (!!id) {
            renderProducts()
        }

        function insertAutoComplete() {
            var insertField$ = $('#insert-field');
            insertField$.autocomplete({
                delay: 0,
                source: function (request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/packet/autocomplete',
                        type: 'POST',
                        dataType: 'json',
                        data: 'filter_name=' + encodeURIComponent(request.term),
                        success: function (data) {
                            response($.map(data, function (item) {
                                console.log(item);
                                return {
                                    label: '['+item.code+']'+item.name,
                                    value: item.code,
                                    raw: item
                                };
                            }));
                        }
                    });
                },
                select: function (event, ui) {
                    var pdata = ui.item;
                    productsData.push({
                        coupon_id: pdata.raw.coupon_id,
                        name: pdata.raw.name,
                        code: pdata.raw.code,
                        date_start: pdata.raw.date_start,
                        date_end: pdata.raw.date_end,
                        discount: pdata.raw.discount,
                        type:pdata.raw.type
                    });
                    renderProducts();

                    insertField$.val('');
                    return false;
                }
            }).bind("input.autocomplete", function () {
                $(this).autocomplete("search", this.value);
            });
        }

        $('.remove-item').live('click', function () {
            var this$ = $(this);
//            if (confirm('确认删除该项？')) {
                var pid = this$.data('pid');
                _.remove(productsData, function (item) {
                    return item.code == pid;
                });
                renderProducts();
//            }
        });

        insertAutoComplete();

        $('#form').submit(function () {
            this$ = $(this);
            var productIds = _.map(productsData, function (item) {
                return item.code;
            });
            this$.find('[name="productIds"]').val(JSON.stringify(productIds));
        });

        jQuery.extend(jQuery.validator.messages, {
            required: "此项必填",
//        remote: "Please fix this field.",
            email: "请输入正确的邮箱地址"
//        url: "Please enter a valid URL.",
//        date: "Please enter a valid date.",
//        dateISO: "Please enter a valid date (ISO).",
//        number: "Please enter a valid number.",
//        digits: "Please enter only digits.",
//        creditcard: "Please enter a valid credit card number.",
//        equalTo: "Please enter the same value again.",
//        accept: "Please enter a value with a valid extension.",
//        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
//        minlength: jQuery.validator.format("Please enter at least {0} characters."),
//        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
//        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
//        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
//        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
        });
        $("#form").validate({
            ignore: []
        });


        $('#save-btn').click(function () {
            $('#form').submit();
        });
        
    });
</script>
