"use strict";
/*global $, g5 */
/*jshint jquery:true, globalstrict:true */

$(document).ready(function() {
    console.log("test");
    // bind search
    $("#btnSearch").bind("click", function() {
        g5.loading();
        var url = Routing.generate('g5_movie_add');
        var data = ("#formSearch").serilize();

        $.post(url, data, function(response) {
            console.log(response);
            g5.doneLoading();
        })
        return false;
    });
});
