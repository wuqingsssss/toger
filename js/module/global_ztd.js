/**
 * Created by joecliff on 14/12/5.
 */

$(document).ready(function () {
    //add func
    if (!String.format) {
        String.format = function (format) {
            var args = Array.prototype.slice.call(arguments, 1);
            return format.replace(/{(\d+)}/g, function (match, number) {
                return typeof args[number] != 'undefined'
                    ? args[number]
                    : match
                    ;
            });
        };
    }

    $('#ztd-btn.fancybox').fancybox({
        padding: 0,
        margin: 0,
        hideOnOverlayClick: false,
        showCloseButton: true,
        autoDimensions: true,
        content: $('#ztd-hidden-html').html(),
        onComplete: initChooseZtd
    });

    function initChooseZtd() {

        var wrapper$ = $('#fancybox-content');

        var ztd$ = wrapper$.find('#ztd');
        var areas$ = ztd$.find('.ztd-area');
        var cbds$ = ztd$.find('.ztd-cbd');
        var points$ = ztd$.find('.ztd-point');
        var pointDetail$ = ztd$.find('.point-detail');

        var data;
        var selections = {
            areaId: null,
            cbdId: null,
            pointId: null
        };

        function init() {
            var url = ztd$.data('url');
            $.getJSON(url).then(function (result) {
                data = result || [];
                //console.log(data);
                renderArea();
            });
        }

        init();

        function clearSelections(depth) {
            if (depth > 2) {
                areas$.find('dd').remove();
                selections.areaId = null;
            }

            if (depth > 1) {
                cbds$.find('dd').remove();
                selections.cdbId = null;
            }

            points$.find('dd').remove();
            pointDetail$.empty();
            selections.pointId = null;
        }

        function renderArea() {
            clearSelections(3);

            var itemTpl = '<dd><a data-id="{0}">{1}</a></dd>';
            var html = _.map(data, function (item) {
                return String.format(itemTpl, item.city_id, item.name);
            }).join('');
            areas$.find('dl').append(html);

            areas$.find('dl dd a').click(function () {
                var this$ = $(this);
                selections.areaId = this$.data('id');
                renderCbd();
                //add active cls
                areas$.find('dl dd a').removeClass('active');
                this$.addClass('active');

            });
        }

        function findAreaData() {
            var areaId = selections.areaId;
            var area = _.filter(data, function (item) {
                return item.city_id == areaId;
            })[0];
            return area;
        }

        function renderCbd() {
            //find parent
            var area = findAreaData();
            var cbds = area.cbds || [];

            //reload self
            clearSelections(2);

            var itemTpl = '<dd><a data-id="{0}">{1}</a></dd>';
            var html = _.map(cbds, function (item) {
                return String.format(itemTpl, item.id, item.name);
            }).join('');
            cbds$.find('dl').append(html);

            cbds$.find('dl dd a').click(function () {
                var this$ = $(this);
                selections.cbdId = this$.data('id');
                renderPoint();

                //add active cls
                cbds$.find('dl dd a').removeClass('active');
                this$.addClass('active');
            });
        }

        function findCbdData() {
            var area = findAreaData();
            var cbds = area.cbds || [];
            var cbdId = selections.cbdId;
            return _.filter(cbds, function (item) {
                return item.id == cbdId;
            })[0];
        }

        function renderPoint() {
            //find parent
            var cbd = findCbdData();
            var points = cbd.points || [];

            //reload self
            clearSelections(1);

            var itemTpl = '<dd><a data-id="{0}">{1}</a></dd>';
            var html = _.map(points, function (item) {
                return String.format(itemTpl, item.point_id, item.name);
            }).join('');
            points$.find('dl').append(html);

            points$.find('dl dd a').click(function () {
                var this$ = $(this);
                selections.pointId = this$.data('id');

                //add active cls
                points$.find('dl dd a').removeClass('active');
                this$.addClass('active');

                //保存区域,商圈到cookie，结账时会用到
                var select_city_id=$('.ztd-area dd a.active').attr('data-id');

                var select_cbd_id=$('.ztd-cbd dd a.active').attr('data-id');

                var select_point_id=$('.ztd-point dd a.active').attr('data-id');

                $.cookie('point_city_id', select_city_id);
                $.cookie('point_cbd_id', select_cbd_id);
                $.cookie('select_point_id', select_point_id);

                // 更改自提点名称
                $('#ztd-btn').text($('.ztd-point dd a.active').text());
                $.ajax({
                    url: 'index.php?route=point/home/location',
                    type: 'post',
                    data: 'city_id='+select_city_id+'&cbd_id='+select_cbd_id+'&point_id='+select_point_id,
                    dataType: 'json',
                    success: function (json) {
                        if (json['error']) {
                            alert(json['error']);
                        }

                        if($('.shipping-method-list #addressList')) {
                            $('#shipping-method .shipping-method-list').load('index.php?route=checkout/checkout/shipping_method_load');
                        }

                        if (json['success']) {
                            $.fancybox.close();
                        }
                    }
                });

                //$.fancybox.close();
            });

            points$.find('dl dd a').mouseover(function () {
                var this$ = $(this);
                var id = this$.data('id');
                var cbd = findCbdData();
                var points = cbd.points || [];
                var point = _.filter(points, function (item) {
                    return item.point_id == id;
                })[0];

                pointDetail$.html(renderPointDetail(point));

                if($('.i-point-detail').is(":hidden")){
                    $('.i-point-detail').show();
                }

            }).mouseout(function () {
                //pointDetail$.empty();
            });
        }

        function renderPointDetail(point){
            var html='<ul>';

            html+='<li>地址：'+point.address+'</li>';
            html+='<li>营业时间：'+point.business_hour+'</li>';
            html+='<li>联系电话：'+point.telephone+'</li>';

            html+='</ul>';
            return html;
        }

    }
});