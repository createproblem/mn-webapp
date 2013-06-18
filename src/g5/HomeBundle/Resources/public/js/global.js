"use strict";
/*global $ */
/*jshint jquery:true, globalstrict:true */

/////////////////////////
//// Global Functions ///
/////////////////////////

var g5 = {};

/**
 * Show Loading
 */
g5.loading = function() {
    $('#loadingInfo').fadeIn(0);
};

/**
 * Hide Loading
 */
g5.doneLoading = function() {
    $('#loadingInfo').fadeOut(250);
};
