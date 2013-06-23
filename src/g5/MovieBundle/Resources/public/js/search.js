"use strict";
/*global $, g5, Routing */
/*jshint jquery:true, globalstrict:true */

////////////////////////
// helper functions ////
////////////////////////

function bindMoreButtons()
{
    $(".btnMore").each(function() {
        var tmdbId = $(this).attr('data-tmdbId');
        var data = {
            "tmdbId": tmdbId
        };

        $(this).bind('click', data, requestMore);
    });
}

function bindAddButtons()
{
    $(".btnAdd").each(function() {
        var tmdbId = $(this).attr('data-tmdbId');
        var data = {
            "tmdbId": tmdbId
        };

        $(this).bind('click', data, requestAdd);
    });
}

function requestAdd(event)
{
    g5.loading();

    var url = Routing.generate('g5_movie_add');
    var tmdbId = event.data.tmdbId;
    var data = {
        "tmdbId": tmdbId
    };

    $.post(url, data, function(response) {
        console.log(response);
        g5.doneLoading();
    });
}

function requestMore(event)
{
    g5.loading();

    var url = Routing.generate('g5_movie_loadTmdbData');
    var tmdbId = event.data.tmdbId;
    var data = {
        "tmdbId": tmdbId
    };

    $.post(url, data, function(response) {
        var overview = $("#overview"+tmdbId);
        overview.html(response.overview);
        g5.doneLoading();
    });
}

$(document).ready(function() {
    // Search TmdbApi
    $("#btnSearch").bind('click', function() {
        g5.loading();
        var data = $("#formSearch").serialize();
        var url = Routing.generate("g5_movie_search_tmdb");

        $.post(url, data, function(response) {
            $("#searchResult").html(response);
            bindMoreButtons();
            bindAddButtons();
            g5.doneLoading();
        });

        return false;
    });
});
