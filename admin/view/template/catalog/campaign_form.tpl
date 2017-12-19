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
            <a href="#tab-rules"><?php echo $tab_rules; ?> </a>
        </div>
    
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"
              class="form-horizontal">
            <input type="hidden" name="rules"/>

            <div id="tab-general" class="vtabs-content">
                <table class="form">
                	<tr>
                        <td><span class="required">*</span><?php echo $entry_name; ?><?php echo $campaign['name']; ?></td>
                        <td><input  type="text" name="name"
                                   value="<?php echo $campaign['name']; ?>"
                                   required=""/></td>
                    </tr>
                    <tr>
                        <td><span>&nbsp;</span><?php  echo $entry_code; ?></td>
                        <?php if(empty($campaign['code'])){ ?> 
                        <td>
                        <input type="radio" name="codetype" value="0" />通用码&nbsp&nbsp&nbsp&nbsp 
                        <input type="radio" name="codetype" value="1" checked="checked"/>活动码&nbsp&nbsp&nbsp&nbsp  
                        </td> 
                        <?php } else {?>                  
                        <td><?php  echo $campaign['code']; ?></td>
                        <?php }?>
                    </tr>
                
                    <tr>
                        <td><span class="required">*</span><?php echo $entry_starttime; ?></td>
                        <td><input class="date" type="text" name="date_start"
                                   value="<?php echo(isset($campaign['date_start']) ? date('Y-m-d', strtotime($campaign['date_start'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span><?php echo $entry_endtime; ?></td>
                        <td><input class="date" type="text" name="date_end"
                                   value="<?php echo(isset($campaign['date_end']) ? date('Y-m-d', strtotime($campaign['date_end'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                    <tr>     
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="status">
                            <?php if ($campaign['status'] || !isset($campaign['status'])) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                            </select>
                        </td>  
                    </tr>                                 
                </table>
            </div>
            <div id="tab-rules" class="vtabs-content">
                <div>
                     <span><strong><?php echo $campaign_help; ?></strong></span>
                </div>
                <table class="list" id="rules">
                    <thead>
                    <tr>
                        <td class="left">序号</td>            
                        <td class="left">礼包名称</td>
                        <td class="left">发放条件</td>
                        <td class="right">开始日期</td>
                        <td class="right">结束日期</td>
                        <td class="right">批次</td>
                        <td class="right">操作</td>                     
                    </tr>
                    </thead>
                    <tbody id="main-tbody">
                    </tbody>
                    <tbody>
                    <tr>
                        <td colspan="7">
                            <input type="text" placeholder="输入礼包名称" style="margin: 5px 0;width: 200px;" id="insert-field"/>
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
        <td class="left">{{sort_order}}</td>
        <td class="left">{{name}}</td>
        <td class="left">{{cond}}</td>
        <td class="right">{{date_start}}</td>
        <td class="right">{{date_end}}</td>
        <td class="right">{{batch}}</td>
        <td class="right">[<a class="remove-item" data-pid="{{name}}">删除</a>]</td>
    </tr>
    {{/each}}
</script>
<script type="text/javascript">
    var id = '<?php echo (isset($campaign['campaign_id'])?$campaign['campaign_id']:'') ?>';
    var rulesData = <?php echo json_encode($rules); ?>;

    $(document).ready(function () {
        $('#tabs a').tabs();
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});

        var tpl = $("#entry-template").html();
        var template = Handlebars.compile(tpl);

        function renderRules() {
            var index = 0;
            var data = _.map(rulesData, function (item) {
                var p = _.clone(item);
                index++;
                p.sort_order = index;
                return p;
            });
            $('#main-tbody').html(template({data: data}));
        }

        if (!!id) {
            renderRules()
        }

        function insertAutoComplete() {
            var insertField$ = $('#insert-field');
            insertField$.autocomplete({
                delay: 0,
                source: function (request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/campaign/autocomplete',
                        type: 'POST',
                        dataType: 'json',
                        data: 'filter_name=' + encodeURIComponent(request.term),
                        success: function (data) {
                            response($.map(data, function (item) {
                                console.log(item);
                                return {
                                    label: '['+item.batch+']'+item.name,
                                    value: item.name,
                                    raw: item
                                };
                            }));
                        }
                    });
                },
                select: function (event, ui) {
                    var pdata = ui.item;
                    rulesData.push({
                        packet_id: pdata.raw.packet_id,
                        name: pdata.raw.name,
                        cond: pdata.raw.cond,
                        date_start: pdata.raw.date_start,
                        date_end: pdata.raw.date_end,
                        batch: pdata.raw.batch,
                        sort_order: 0
                    });
                    renderRules();

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
                _.remove(rulesData, function (item) {
                    return item.name == pid;
                });
                renderRules();
//            }
        });

        insertAutoComplete();

        $('#form').submit(function () {
            this$ = $(this);
            var rules = _.map(rulesData, function (item) {
                return item.packet_id;
            });
            this$.find('[name="rules"]').val(JSON.stringify(rules));
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
