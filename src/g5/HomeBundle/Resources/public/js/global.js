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

/**
 * Default Ajax Request
 *
 * @param  {object}   params        $.ajax({});
 * @param  {Function} callback      Callback function with response argument
 *
 * @return $.ajax
 */
g5.ajaxRequest = function(params, callback)
{
    var jqxhr = $.ajax({
        type: params.type,
        url:  params.url,
        data: params.data,
        beforeSend: function(xhr) { g5.loading(); }
    })
    .done(callback)
    .always(function() {
        g5.doneLoading();
    });

    return jqxhr;
};

$(document).ready(function() {
    $("#gui-control").bind("click", function() {
        $("[id|='label-trigger']").toggle();
        $(".label .label-remove").toggle();
        $("#btnDeleteLabel").toggle();
    });
});
