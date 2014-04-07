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
        var tmp = $http({
            method: 'POST',
            url: '/flights/interest',
            data: 'id=' + id,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
        tmp.error(function(){ console.log("Post of Interest failed")});
    };

    $scope.disinterest = function(id){
        var tmp = $http({
            method: 'POST',
            url: '/flights/disinterest',
            data: 'id=' + id,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    };

    var timer = setInterval(function() {
        $scope.$apply(getFlightList);
        $scope.$apply(getCompare);
    }, 1000);

    $scope.flightsFilter = function(object) {

        if(typeof $scope.search == "undefined")
            return true;

        var search = $scope.search.replace(/ +$/, '');
        if(!search)
            return true;

        array = search.split(' ');

        checkSubStr = function(data, target, checkArray){
            target = target.toLowerCase();
            for(var i = 0, len = checkArray.length; i < len; i++){
                if(data[checkArray[i]].toLowerCase().indexOf(target) != -1){
                    return true;
                }
            }
            return false;
        };

        var match = 0;
        for(var i = 0, len = array.length, is_sub; i < len; i++){
            is_sub = 0;
            if(array[i] == 'from'){
                if(i+1 < len){
                    if(checkSubStr(object, array[i+1], ['departure', 'departure_date'])){
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
                    if(checkSubStr(object, array[i+1], ['arrival', 'arrival_date'])){
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
    };


    $scope.plansIsEmpty = function() {
        for(var key in $scope.plans) {
            if($scope.plans.hasOwnProperty(key))
                return false;
        }
        return true;
    }

    getFlightList();
});

app.controller('userList', function($scope, $http){
    var getUserList = function(){
        var users = $http.get('/api/users');
        users.success(function(data, status, headers, config){
                $scope.users = data;
        });
        users.error(function(data, status, headers, config){
                console.log('Ajax failed');
        });
    };

    var timer = setInterval(function() {
        $scope.$apply(getUserList);
    }, 1000);

    getUserList();
});

app.controller('airportsList', function($scope, $http){
    var getAirportsList = function(){
        var airports = $http.get('/api/airports');
        airports.success(function(data, status, headers, config){
                $scope.airports = data;
        });
        airports.error(function(data, status, headers, config){
                console.log('Ajax failed');
        });
    };

    var timer = setInterval(function() {
        $scope.$apply(getAirportsList);
    }, 1000);

    getAirportsList();
});
