var app = angular.module('Airline', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.controller('flightList', function($scope, $http){
    var getFlightList = function(){
        var planes = $http.get('/api/planes');
        planes.success(function(data, status, headers, config){
                $scope.flights = data;
        });
        planes.error(function(data, status, headers, config){
                alert('Ajax failed');
        });
    };

    var timer = setInterval(function() {
        $scope.$apply(getFlightList);
    }, 1000);

    getFlightList();
});

app.controller('FXshake', function ($scope) {
    var remove = function(x) {
       angular.element(document.getElementById(x)).removeClass('error');
    }
});