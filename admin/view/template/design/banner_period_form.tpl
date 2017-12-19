<style>
    .list {
        width: 45%;
    }

    caption {
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 10px;
    }
</style>

<?php if ($error_warning) { ?>
    <div class="alert alert-error"><?php echo $error_warning; ?><a class="close" data-dismiss="alert">×</a></div>
<?php } ?>
<div class="box">
    <div class="heading">
        <h2>
            <?php echo $banner['name']; ?> - 周期
        </h2>

        <div class="buttons">
            <button id="save-btn" data-loading-text="loading..." class="btn btn-primary">
                <?php echo $button_save; ?>
            </button>
            <input type="button" onclick="history.go(-1)" class="btn"
                   value="<?php echo $button_cancel; ?>">
        </div>
    </div>
    <div class="content">
        <div id="tabs" class="vtabs">
            <a href="#tab-general" class="first"><?php echo $tab_general; ?></a>
            <a href="#tab-banners">横幅信息</a>
        </div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form"
              class="form-horizontal">
            <input type="hidden" name="imageIds"/>

            <div id="tab-general" class="vtabs-content">
                <table class="form">
                     <tr>
                    <td><span class="required">*</span>标题:</td>
                    <td><input  type="text" name="name"
                               value="<?php echo(isset($result['name']) ? $result['name'] : ''); ?>"
                               required=""/></td>
                    </tr>

                    <tr>
                        <td><span class="required">*</span>开始时间:</td>
                        <td><input class="date" type="text" name="start_date"
                                   value="<?php echo(isset($result['start_date']) ? date('Y-m-d', strtotime($result['start_date'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span>结束时间:</td>
                        <td><input class="date" type="text" name="end_date"
                                   value="<?php echo(isset($result['end_date']) ? date('Y-m-d', strtotime($result['end_date'])) : ''); ?>"
                                   required=""/></td>
                    </tr>
                </table>
            </div>
            <div id="tab-banners" class="vtabs-content">
                <div class="row">
                    <table class="list span6">
                        <caption>已选择</caption>
                        <thead>
                        <tr>
                            <td class="left">标题</td>
                            <td class="left">图片</td>
                            <td class="right">管理</td>
                        </tr>
                        </thead>
                        <tbody id="added-tbody">
                        </tbody>
                    </table>
                    <table class="list span6">
                        <caption>未选择</caption>
                        <thead>
                        <tr>
                            <td class="left">标题</td>
                            <td class="left">图片</td>
                            <td class="right">管理</td>
                        </tr>
                        </thead>
                        <tbody id="remaining-tbody">
                        </tbody>
                    </table>
                </div>
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
<script id="template-added" type="text/x-handlebars-template">
    {{#each data}}
    <tr>
        <td class="left">
            {{banner_image_description.[1].title}}
        </td>
        <td class="left">
            <img src="{{preview}}" alt="image" class="image"/>
        </td>
        <td class="right">[<a class="remove-item" data-image-id="{{banner_image_id}}">删除</a>]</td>
    </tr>
    {{/each}}
</script>
<script id="template-remaining" type="text/x-handlebars-template">
    {{#each data}}
    <tr>
        <td class="left">
            {{banner_image_description.[1].title}}
        </td>
        <td class="left">
            <img src="{{preview}}" alt="image" class="image"/>
        </td>
        <td class="right">[<a class="add-item" data-image-id="{{banner_image_id}}">加入</a>]</td>
    </tr>
    {{/each}}
</script>
<script type="text/javascript">
    var id = '<?php echo (isset($result['id'])?$result['id']:'') ?>';
    var all_banner_images = <?php echo json_encode($all_banner_images); ?>;
    all_banner_images = all_banner_images || [];
    var imagesDataRaw = <?php echo json_encode($images); ?>;
    imagesDataRaw = imagesDataRaw || [];
    var imageIds = _.map(imagesDataRaw, function (item) {
        return parseInt(item.banner_image_id);
    });

//    console.log(all_banner_images);
//    console.log(imageIds);

    $(document).ready(function () {
        $('#tabs a').tabs();
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});

        var addTpl = Handlebars.compile($("#template-added").html());
        var remainTpl = Handlebars.compile($("#template-remaining").html());

        function renderBanners() {
//            console.log(imageIds);
            var addedImages = [];
            var remainingImages = [];
            _.forEach(all_banner_images, function (item) {
                var imageId = parseInt(item.banner_image_id);
                if (imageIds.indexOf(imageId) > -1) {
                    addedImages.push(item);
                } else {
                    remainingImages.push(item);
                }
            });

            $('#added-tbody').html(addTpl({data: addedImages}));
            $('#remaining-tbody').html(remainTpl({data: remainingImages}));
        }

        renderBanners();

        $('.remove-item').live('click', function () {
            var this$ = $(this);
            var imageId = parseInt(this$.data('imageId'));
            _.remove(imageIds, function (item) {
                return item == imageId;
            });
            renderBanners();
        });
        $('.add-item').live('click', function () {
            var this$ = $(this);
            var imageId = parseInt(this$.data('imageId'));
            if (imageIds.indexOf(imageId) == -1) {
                imageIds.push(imageId);
            }
            renderBanners();
        });

        $('#form').submit(function () {
            this$ = $(this);
            this$.find('[name="imageIds"]').val(JSON.stringify(imageIds));
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
