/**
 * @author: Emmanuel KWENE
 * @email: kwene.emmanuel@gmail.com
 * @website: http://emmanuelkwene.com
 * @date: 07/07/2015
 */

'use strict';

app
    .controller('Polls', function ($scope, $http, $q) {

        $scope.loader = true;
        var canceler = $q.defer();

        $http
            .post(sondageData.ajaxurl+'?action=applemaniak_sondage_ajax&applemaniak_sondage_action=get_polls', {timeout: canceler.promise})

            .success(function(data, status) {})
            .then(function (data) {
                $scope.loader = false;
                data = data.data;
                data = data.substring(0, data.length-1);
                $scope.polls = JSON.parse(data);
                canceler.resolve();
            });
    })

    .controller('Answers', function ($scope, $http, $q) {

        $scope.poll_id = parseInt( document.getElementById('poll_id').value );
        $scope.loader = true;

        var canceler = $q.defer();

        $http
            .post(sondageData.ajaxurl+'?action=applemaniak_sondage_ajax&applemaniak_sondage_action=get_answers&poll_id='+$scope.poll_id,
            {timeout: canceler.promise})
            .success(function(data, status) {})
            .then(function (data) {
                $scope.loader = false;
                data = data.data;
                data = data.substring(0, data.length-1);
                $scope.answers = JSON.parse(data);
                canceler.resolve();
            });

        $scope.addAnswer = function () {

            var canAdd = true;

            for(var i = 0; i < $scope.answers.length; i++) {
                if($scope.answer_input.toLowerCase() === $scope.answers[i].content.toLowerCase() || $scope.answer_input === '') {
                    canAdd = false;
                }
            }

            if(true === canAdd) {
                $scope.answers.push({content:$scope.answer_input, ID: 0});
                $scope.answer_input = '';
            }

        };



        $scope.delAnswer = function (mavar) {

            $scope.answers.forEach(function (value, index) {
                if( value.content.toLowerCase() === mavar.answer.content.toLowerCase()) {
                    $scope.answers.splice(index, 1);
                }
            });

        };

    });