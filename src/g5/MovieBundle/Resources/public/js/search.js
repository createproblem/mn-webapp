"use strict";
/*global $, g5, Routing */
/*jshint jquery:true, globalstrict:true */

$(document).ready(function() {
    // bind Lazy Loading for more data
    $("button[data-loading='btnMore']").each(function(key, val) {
        $(val).bind("click", function() {
            g5.loading();
            var url = Routing.generate("g5_movie_loadmeta", {"tmdbId": $(this).attr("data-tmdbId")});
            var self = this;
            $.post(url, null, function(response) {
                var el = $("p[data-overview="+$(self).attr("data-tmdbId")+"]");
                el.html(response.overview);
                g5.doneLoading();
            });
        });
    });
});
