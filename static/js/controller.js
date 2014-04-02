var app = angular.module('Airline', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.controller('flightList', function($scope, $http){
    var getFlightList = function(){
        var planes = $http.get('/api/flights');
        planes.success(function(data, status, headers, config){
                $scope.flights = data;
        });
        planes.error(function(data, status, headers, config){
                console.log('Ajax failed');
        });
    };

    var getCompare = function(){
        var planes = $http.get('/api/plans');
        planes.success(function(data, status, headers, config){
                $scope.plans = data;
        });
        planes.error(function(data, status, headers, config){
                console.log('Ajax failed');
        });
    };

    $scope.interest = function(id){
        data = { "id" : id }
        $http.post('/flights/interest', data).error(function(){ console.log("Post of Interest failed")});
    };

    $scope.deinterest = function(id){
        data = { "id" : id }
        $http.post('/flights/deinterest', data).error(function(){ console.log("Post of Deinterest failed")});
    };

    var timer = setInterval(function() {
        $scope.$apply(getFlightList);
    }, 1000);

    $scope.flightsFilter = function(object) {

        if(typeof $scope.search == "undefined")
            return true;

        var search = $scope.search.replace(/ +$/, '');
        if(!search)
            return true;

        array = search.split(' ');

        var match = 0;
        for(var i = 0, len = array.length, is_sub; i < len; i++){
            is_sub = 0;
            if(array[i] == 'from'){
                if(i+1 < len){
                    if(object['departure'].toLowerCase().indexOf(array[i+1].toLowerCase()) != -1){
                        match+=2;
                        i++;
                    }
                } else{
                    match++;
                }
                continue;
            }

            if(array[i] == 'to'){
                if(i+1 < len){
                    if(object['arrival'].toLowerCase().indexOf(array[i+1].toLowerCase()) != -1){
                        match+=2;
                        i++;
                    }
                } else{
                    match++;
                }
                continue;
            }

            for(var key in object){
                if(object[key].toLowerCase().indexOf(array[i].toLowerCase()) != -1)
                    is_sub = 1;
            }
            match += is_sub;
        }

        if(match == array.length)
            return true;
        return false;
    }

    getFlightList();
});
