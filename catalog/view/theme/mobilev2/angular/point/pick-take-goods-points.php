<link rel="stylesheet" href="catalog/view/theme/<?php echo $this->config->get('config_template');  ?>/angular/point/pick-take-goods-points.css?v=<?php echo STATIC_VERSION; ?>"/>
<script src="catalog/view/theme/<?php echo $this->config->get('config_template');  ?>/angular/point/pick-take-goods-points.js?v=<?php echo STATIC_VERSION; ?>"></script>

<div id="ztd" ng-controller="PickTakeGoodsPointsCtrl" ng-show="showContainer">
    <div class="ztd-content" >
        <div class="ztd-content-right">
            <div id="header" class="bar bar-header bar-positive">

                <a ng-if="!state.is('area')" onclick="history.go(-1);" class="button icon-left ion-chevron-left button-clear button-white"></a>
                <h1 class="title">选择自提点</h1>
                <a class="button icon-right ion-close button-clear button-white" ng-click="closePointContainer()"></a>
            </div>

            <div ui-view></div>
        </div>
    </div>
</div>

<script type="text/ng-template" id="areas.html">
    <ul class="lists">
        <li ng-repeat="item in allData"><a class="item" ui-sref="cbd({areaId:item.city_id})">{{item.name}}</a></li>
    </ul>
</script>
<script type="text/ng-template" id="cbds.html">
    <ul class="lists">
        <li ng-repeat="item in cbds"><a class="item" ui-sref="point({areaId:areaId,cbdId:item.id})">{{item.name}}</a></li>
    </ul>
</script>
<script type="text/ng-template" id="points.html">
    <ul class="lists">
        <li ng-repeat="item in points"><a class="item" ng-click="!!showPointDetail?showDetail(item):notifyPointChoosing(item)">{{item.name}}</a></li>
    </ul>
</script><script type="text/ng-template" id="point-detail.html">
    <div class="card">
        <div class="item item-divider">
            <h3>{{point.name}}</h3>
        </div>

        <div class="item item-text-wrap">
            <p>详细地址：{{point.address}}</p>
            <p>联系电话：{{point.telephone}}</p>
            <p>营业时间：{{point.business_hour}}</p>

            <a class="button button-positive button-block" ng-click="notifyPointChoosing()">确认选择</a>
        </div>

    </div>
</script>