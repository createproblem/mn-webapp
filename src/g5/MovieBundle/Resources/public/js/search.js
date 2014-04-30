/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $, g5AjaxQueue, Routing */

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
                    'url': Routing.generate('g5_movie_api_load_additional_data'),
                    'data': { 'tmdbId': tmdbId }
                }, function(response) {
                    $(resultElement).html(response.overview);
                    $(triggerElement).hide();
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
});
