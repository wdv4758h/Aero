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
    };

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

Object.prototype.isEmpty = function() {
    for(var key in this) {
        if(this.hasOwnProperty(key))
            return false;
    }
    return true;
}
