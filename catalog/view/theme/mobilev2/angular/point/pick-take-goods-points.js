angular.module('app')
    .config(function ($urlRouterProvider, $stateProvider) {
        $urlRouterProvider.otherwise('/');

        $stateProvider.state('area', {
            url: '/',
            templateUrl: 'areas.html'
        });
        $stateProvider.state('cbd', {
            url: '/areas/:areaId/cbds',
            templateUrl: 'cbds.html',
            controller: function ($state, $scope) {
                var areaId = $scope.areaId = $state.params.areaId;

                $scope.checkDataInit().then(function () {
                    var area = $scope.findAreaData(areaId);
                    $scope.cbds = area.cbds || [];
                });

            }
        });
        $stateProvider.state('point', {
            url: '/areas/:areaId/cbds/:cbdId/points',
            templateUrl: 'points.html',
            controller: function ($state, $scope, $rootScope) {

                var areaId = $scope.areaId = $state.params.areaId;
                var cbdId = $scope.cbdId = $state.params.cbdId;

                $scope.checkDataInit().then(function () {
                    var cbd = $scope.findCbdData(areaId, cbdId);
                    $scope.points = cbd.points || [];
                });

                $scope.notifyPointChoosing = function (point) {
                    $scope.$parent.showContainer = false;
                    $state.go('area');

                    var pointId = point.point_id;
                    $scope.storeAndNotifySelected(areaId, cbdId, pointId);
                };

                $scope.showDetail = function (point) {
                    $state.go('pointDetail', {areaId: areaId, cbdId: cbdId, pointId: point.point_id});
                }
            }
        });
        $stateProvider.state('pointDetail', {
            url: '/areas/:areaId/cbds/:cbdId/points/:pointId',
            templateUrl: 'point-detail.html',
            controller: function ($state, $scope, $rootScope) {

                var areaId = $scope.areaId = $state.params.areaId;
                var cbdId = $scope.cbdId = $state.params.cbdId;
                var pointId = $scope.cbdId = $state.params.pointId;

                $scope.checkDataInit().then(function () {
                    $scope.point = $scope.findPointData(areaId, cbdId, pointId);
                    console.log($scope.point);
                    $('#ztd-btn').text($scope.point.name);
                });


                $scope.notifyPointChoosing = function () {
                    $scope.$parent.showContainer = false;
                    $state.go('area');

                    $scope.storeAndNotifySelected(areaId, cbdId, pointId);
                }
            }
        });
    })
    .controller('SwitchZTDCtrl', function ($scope, $rootScope) {
        $scope.showPickPointsContainer = function () {
            $rootScope.$broadcast('showPickPointsContainer');
        };
    })
    .controller('PickTakeGoodsPointsCtrl', function ($scope, $rootScope, $http, $q,$state) {
        $scope.showContainer = false
        $scope['state']=$state;

        $rootScope.$on('showPickPointsContainer', function () {
            $scope.showContainer = true;
        });

        var initData = $scope.initData = function () {
            var url = 'index.php?route=point/home/initdata';
            return $http.get(url).then(function (data) {
                $scope.allData = data.data || [];
            }).catch(console.error);
        };

        initData();

        //check html flag on home
        var zbdData$ = $('#zbd-data');
        if (!!zbdData$.data('showLocationTip') && window.localStorage.getItem('userClosePointContainer')!="1"){
            $scope.showContainer = true;
        }
        $scope.showPointDetail = (!!zbdData$.data('showPointDetail'));

        $scope.checkDataInit = function () {
            if (!$scope.allData) {
                return $scope.initData();
            }
            var deferred = $q.defer();
            var promise = deferred.promise;
            deferred.resolve(true);
            return promise;
        };

        $scope.findAreaData = function (areaId) {
            return _.filter($scope.allData, function (item) {
                return item.city_id == areaId;
            })[0];
        };

        $scope.findCbdData = function (areaId, cbdId) {
            var area = $scope.findAreaData(areaId);
            var cbds = area.cbds || [];
            return _.filter(cbds, function (item) {
                return item.id == cbdId;
            })[0];
        };
        $scope.findPointData = function (areaId, cbdId, pointId) {
            var cbd = $scope.findCbdData(areaId, cbdId);
            var points = cbd.points || [];
            return _.filter(points, function (item) {
                return item.point_id == pointId;
            })[0];
        };

        $scope.storeAndNotifySelected = function (areaId, cbdId, pointId) {
            $.cookie('point_city_id', areaId);
            $.cookie('point_cbd_id', cbdId);
            $.cookie('select_point_id', pointId);

            $rootScope.$broadcast('point.picked', {areaId: areaId, cbdId: cbdId, pointId: pointId});
            //var point=$scope.findPointData(areaId, cbdId, pointId);
            //console.log(point);
            //$('#ztd-btn').text(point.name);

        };

        $scope.closePointContainer= function () {
          window.localStorage.setItem('userClosePointContainer',1);
            $scope.showContainer = false;
        };
    });