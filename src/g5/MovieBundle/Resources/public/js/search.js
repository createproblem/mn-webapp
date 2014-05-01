/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $, g5AjaxQueue, Routing, g5Message */

/**
 * Tmdb Search Result functions
 */
var g5TmdbSearchResult = (function() {
    'use strict';

    /**
     * PUBLIC
     */
    return {
        /**
         * Binds the load more event
         *
         * @param  object triggerElement  The trigger button to load more event.
         * @param  object resultElement   The result element where the data will be displayed.
         */
        bindMoreEvent: function(triggerElement, resultElement) {
            $(triggerElement).on('click', function() {
                var tmdbId = $(triggerElement).attr('data-tmdbid');

                // load more movie data
                g5AjaxQueue.ajaxSingle('load-more', {
                    'type': 'GET',
                    'url': Routing.generate('get_movie_tmdb', { 'tmdbId': tmdbId }),
                }, function(response) {
                    $(resultElement).html(response.overview);
                    $(triggerElement).hide();
                });
            });
        },

        /**
         * Binds the add event
         *
         * @param  object triggerElement  The trigger button to add the movie.
         */
        bindAddMovieEvent: function(triggerElement) {
            $(triggerElement).on('click', function() {
                var tmdbId = $(triggerElement).attr('data-tmdbid');

                g5AjaxQueue.ajaxSingle('add-movie', {
                    'type': 'POST',
                    'url': Routing.generate('post_movie'),
                    'data': { 'tmdbId': tmdbId }
                }, function(response) {
                    if(Object.prototype.toString.call(response) === '[object Array]') {
                        var msg = '';
                        $.each(response, function(index, error) {
                            msg += error.message+"<br>";
                        });
                        g5Message.showMessage(msg);
                    } else {
                        var title = response.title;
                        var release_date = new Date(response.release_date).getFullYear();
                        var msg = title + ' (' + release_date + ') added.';

                        g5Message.showMessage(msg);
                    }
                });
            });
        }
    };
}());

$(document).ready(function() {
    'use strict';

    $('.btnMore').each(function(index, el) {
        // Extract tmdbId
        var tmdbId = $(el).attr('data-tmdbid');

        g5TmdbSearchResult.bindMoreEvent(el, $('#overview'+tmdbId));
    });

    $('.btnAdd').each(function(index, el) {
        g5TmdbSearchResult.bindAddMovieEvent(el);
    });
});
