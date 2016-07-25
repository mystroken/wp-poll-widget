/**
 * @author: Emmanuel KWENE
 * @email: kwene.emmanuel@gmail.com
 * @website: http://emmanuelkwene.com
 * @date: 07/07/2015
 */


'use strict';

var app = angular.module('SondageFrontEnd', []);


// Controllers
//=====================

/**
 *
 */
app.controller('polls', function ($scope, $http, $q) {

    $scope.loader = true;
    $scope.currentIndex = 0;
    $scope.viewResult = false;

    var canceler = $q.defer();
    $http.post(sondageData.ajaxurl+'?action=applemaniak_sondage_ajax&applemaniak_sondage_action=get_polls', {timeout: canceler.promise})
        .success(function(data, status) {})
        .then(function (data) {
            $scope.loader = false;
            data = data.data;
            data = data.substring(0, data.length-1);
            $scope.polls = JSON.parse(data);
            $scope.maxIndex = $scope.polls.length - 1;
            canceler.resolve();
        });


    $scope.switchPoll = function (poll_index) {

        if( poll_index >= 0 && poll_index <= $scope.maxIndex )
        {
            $scope.currentIndex = poll_index;
        }
    };


    $scope.selectAnswer = function (answer) {
        $scope.currentAnswer = answer.answer.ID;
    };


    $scope.vote = function (poll_index, author) {

        if(typeof $scope.currentAnswer == 'undefined')
        {
            alert("Veillez choisir une reponse SVP!");
        }
        else
        {
            var url = sondageData.ajaxurl+'?action=applemaniak_sondage_ajax&applemaniak_sondage_action=save_vote&poll_id='+$scope.polls[poll_index].ID+'&answer_id='+$scope.currentAnswer+'&author_id='+author;

            $http.post(url, {timeout: canceler.promise})
                .success(function(data, status) {})
                .then(function (data) {
                    data = data.data;
                    data = data.substring(0, data.length-1);
                    $scope.polls[poll_index] = JSON.parse(data);
                    canceler.resolve();
                });
        }
    };

    $scope.showResult = function () {
        $scope.viewResult = true;
    };

    $scope.hideResult = function () {
        $scope.viewResult = false;
    };

});