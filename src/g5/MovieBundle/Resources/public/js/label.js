"use strict";
/*global $ */
/*jshint jquery:true, globalstrict:true */

/*
* This file is part of the mn-webapp package.
*
* (c) createproblem <https://github.com/createproblem/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

$(document).ready(function() {

    $("#label_name").typeahead({
        "source": function(query, process) {
            g5.loading();
            return $.get(Routing.generate("g5_movie_label_get"), {"query": query}, function(data) {
                g5.doneLoading();
                return process(data.options);
            });
        }
    });
});
