/**
 * @author: Emmanuel KWENE
 * @email: kwene.emmanuel@gmail.com
 * @website: http://emmanuelkwene.com
 * @date: 07/07/2015
 */


app.config(function($routeProvider, $locationProvider) {

    $locationProvider.html5Mode(false);

    $routeProvider
        .when('/', {
            controller: 'home',
            templateUrl: sondageData.js_folder + 'views/list.html'
        })
        .when('/create/', {
            templateUrl: sondageData.js_folder + 'views/add_edit_poll.html',
            controller: 'create'
        });
});