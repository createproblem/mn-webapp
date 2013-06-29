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

g5.label = {};
g5.label.jqxhr = null;

$(document).ready(function() {

    // setup typeahead file for labels
    $("#label_name").typeahead({
        "source": function(query, process) {
            if (g5.label.jqxhr !== null) {
                g5.label.jqxhr.abort();
            }

            g5.label.jqxhr = $.ajax({
                type: "GET",
                url: Routing.generate("g5_movie_label_find", {"query": query}),
                beforeSend: function(xhr) { g5.loading(); }
            })
            .done(function(data) {
                return process(data.labels);
                })
            .always(function() {
                g5.doneLoading();
            });
        },

        "updater": function(item) {
            console.log("update: "+ item);
            return item;
        }
    });
});
