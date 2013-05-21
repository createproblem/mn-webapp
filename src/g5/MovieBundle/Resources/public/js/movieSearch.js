"use strict";
/*global $, Routing, g5 */
/*jshint jquery:true, globalstrict:true */

////////////////////////
//// AJAX Search ///////
////////////////////////

function search()
{
    g5.loading();
    var data = $('#formMovieSearch').serialize();
    $.post(Routing.generate('g5_movie_add'), data, function(response) {
        var data = response;
        // Add results to collection
        console.log(data);
        g5.doneLoading();
    });
}

$(document).ready(function() {
    // bind search event
    $('#btnMovieSearch').bind('click', function() {
        searchMovie();
        return false;
    });
});
