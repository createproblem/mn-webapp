"use strict";
/*global $, g5, Routing */
/*jshint jquery:true, globalstrict:true, unused:false */

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

g5.movie = {};

/**
 * Change the favorite status of a movie
 *
 * @param  int movieId
 */
g5.movie.updateFavorite = function(el, movieId)
{
    g5.ajaxRequest({
        type: "GET",
        url: Routing.generate('g5_movie_api_movie_update_favorite', { movieId: movieId })
    }, function(response) {
        if (response.status === 'OK') {
            if (response.data === false) {
                $(el).addClass("btn-favorite");
                $(el).removeClass("btn-favorite-active");
                $(el).css("color", "#FFF");
            } else {
                $(el).addClass("btn-favorite-active");
                $(el).removeClass("btn-favorite");
                $(el).css("color", "#AA0000");
            }
        }
    });
};

$(document).ready(function() {
    $("[id|='favorite-trigger']").bind('click', function() {
        var movieId = $(this).attr("data-id");
        g5.movie.updateFavorite(this, movieId);
    });
});
