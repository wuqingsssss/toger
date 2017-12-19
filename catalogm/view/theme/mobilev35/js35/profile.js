/**
 * @file ${FILE_NAME}. Created by PhpStorm.
 * @desc ${FILE_NAME}.
 *
 * @author yangjunbao
 * @since 15/10/29 上午10:31
 * @version 1.0.0
 */
// 页面js文件
$(function () {
//    var $age = $('.age'),
//        $input = $age.find('.col-gray'),
//        config = {
//            confirmText: '完成',
//            confirmCallback: function (values) {
//                $input.html(values[0])
//            },
//            title: '选择年龄',
//            columns: [{
//                id: 'year',
//                items: []
//            }]
//        },
//        scroller,
//        i;
//    for (i = 1900; i < 2015; i++) {
//        config.columns[0].items.push({
//            value: i,
//            label: i,
//            disabled: false
//        })
//    }
//    scroller = new _.Scroller(config);

    var attr = $('[name="salution"]').val();
    if(attr == 'M'){
        $('#man').removeClass('gray').addClass('checked');
    }else{
        $('#female').removeClass('gray').addClass('checked');
    }
    $('.gender>.content').on('click', '.radio', function () {
        var value = $(this).find('i').attr('value');
        $(this).find('i').removeClass('gray').addClass('checked');
        $(this).siblings().find('i').removeClass('checked').addClass('gray');
        $('[name="salution"]').val(value);
    });
//    $age.on('click', function () {
//        scroller.show();
//    });
});