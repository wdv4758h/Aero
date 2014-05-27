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
        //tmp.error(function(){ console.log("Post of Interest failed")});
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
    }, 2000);

    search = function(object, key) {

        if(typeof key == "undefined")
            return true;

        var search = key.replace(/ +$/, '');
        if(!search)
            return true;

        array = search.split(' ');

        checkSubStr = function(data, target, checkArray){
            target = target.toLowerCase();
            for(var i = 0, len = checkArray.length; i < len; i++){
                if(data[checkArray[i]].toString().toLowerCase().indexOf(target) != -1){
                    return true;
                }
            }
            return false;
        };

        var match = 0;

        var keywords = {
            'from': ['departure', 'departure_date'],
            '從': ['departure', 'departure_date'],
            'to': ['arrival', 'arrival_date'],
            '到': ['arrival', 'arrival_date'],
            'id': ['id'],
            'fare': ['fare'],
            '價錢': ['fare'],
            'flight': ['code'],
            '航班': ['code'],
            'on': ['departure_date', 'arrival_date'],
            '日期': ['departure_date', 'arrival_date']
        };

        for(var i = 0, len = array.length, is_sub; i < len; i++){
            is_sub = 0;

            next = false;
            for(var key in keywords){
                if(array[i] == key){
                    if(i+1 < len){
                        if(checkSubStr(object, array[i+1], keywords[key])){
                            match+=2;
                            i++;
                        }
                    } else{
                        match++;
                    }
                    next = true;
                    break;
                }
            }

            if(next)
                continue;

            for(var key in object){
                if(object[key].toString().toLowerCase().indexOf(array[i].toLowerCase()) != -1)
                    is_sub = 1;
            }
            match += is_sub;
        }

        if(match == array.length)
            return true;
        return false;
    };

    $scope.flightsFilter = function(object){
        return search(object, $scope.search);
    };

    $scope.compareFilter = function(object){
        return search(object, $scope.search2);
    };

    $scope.plansIsEmpty = function() {
        for(var key in $scope.plans) {
            if($scope.plans.hasOwnProperty(key))
                return false;
        }
        return true;
    }

    getFlightList();
    getCompare();
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
    }, 3000);

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

    var getAirportsListOnce = function(){
        var airports = $http.get('/api/airports');
        airports.success(function(data, status, headers, config){
                $scope.airportsOnce = data;
        });
        airports.error(function(data, status, headers, config){
                console.log('Ajax failed');
        });
    };

    var timer = setInterval(function() {
        $scope.$apply(getAirportsList);
    }, 10000);

    getAirportsList();
    getAirportsListOnce();
});

app.controller('countryList', function($scope, $http){
    var getCountryList = function(){
        var country = $http.get('/api/country');
        country.success(function(data, status, headers, config){
                $scope.countrys = data;
        });
        country.error(function(data, status, headers, config){
                console.log('Ajax failed');
        });
    };

    getCountryList();
});

app.controller('timezone', function($scope, $http){
    var getTimezone = function(){
        var timezone = $http.get('/api/timezone.json');
        timezone.success(function(data, status, headers, config){
            $scope.timezone = data.timezone;
        });
        timezone.error(function(data, status, headers, config){
            console.log('Ajax failed');
        });
    };

    getTimezone();
});

app.controller('formPost', function($scope, $http){

    $scope.formData = {}
    $scope.formReturn = []
    $scope.formData.order = ""
    $scope.formData.asc_desc = ""

    // process the form
    $scope.processForm = function(url) {
        $http({
            method  : 'POST',
            url     : url,
            data    : $scope.formData,
            headers : { 'Content-Type': 'application/x-www-form-urlencoded' }  // set the headers so angular passing info as form data (not request payload)
        })
            .success(function(data) {
                $scope.formReturn = data;
                if ($scope.formData.sort == "") {
                    if ($scope.formReturn.length == 0) {
                        alertify.error("沒有符合的 Ticket");
                    } else {
                        alertify.success("已找到符合的 Ticket");
                    }

                    if (!data.success) {
                        // if not successful, bind errors to error variables
                        //$scope.errorName = data.errors.name;
                        //$scope.errorSuperhero = data.errors.superheroAlias;
                    } else {
                        // if successful, bind success message to message
                        $scope.message = data.message;
                    }
                }
            })
            .error(function(data, status, headers, config){
                console.log('Ajax failed');
            });

    };

    $scope.newForm = function(url) {
        $scope.formData.order = "";
        $scope.formData.asc_desc = ""
        $scope.processForm('/api/ticket/search');
    }

    $scope.sort = function(data) {
        if ($scope.formData.order == data) {
            switch ($scope.formData.asc_desc) {
                case "ASC":
                    $scope.formData.asc_desc = "DESC";
                    break;
                case "DESC":
                    $scope.formData.order = "";
                    $scope.formData.asc_desc = "";
                    break;
            }
        } else {
            $scope.formData.order = data;
            $scope.formData.asc_desc = "ASC";
        }
        $scope.processForm('/api/ticket/search');
    };
});

app.controller('ticketList', function($scope, $http){
});
