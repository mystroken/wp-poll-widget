/**
 * @author: Emmanuel KWENE
 * @email: kwene.emmanuel@gmail.com
 * @website: http://emmanuelkwene.com
 * @date: 07/07/2015
 */


'use strict';

var app = angular.module('sondage', []);


// Custom filters
//=====================

/**
 * Limit character
 */
app.filter('limitCharacters', function () {
    return function (input, count) {
        return (input.length > count) ? input.substring(0,count) : input;
    }
});