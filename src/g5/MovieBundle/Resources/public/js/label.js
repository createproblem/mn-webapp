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
g5.label.isEmpty = false;
g5.label.storage = [];

g5.label.showMessage = function(type, message)
{
    var msgBox = $("#g5_movie_label_messageBox");
    msgBox.html("");

    var msg = $("<small>");
    msg.addClass(type);
    msg.html(message);

    msgBox.append(msg);
    msgBox.show();
};

g5.label.addLabel = function(label)
{
    var labelItem = $("<span>");
    labelItem.addClass("label");
    labelItem.attr("data-labelId", label.id);

    var labelDelItem = $("<span>");
    labelDelItem.addClass("close");
    labelDelItem.html("&VerticalSeparator;&times;");

    labelItem.append(labelDelItem);
    labelItem.append(label.name);

    $("#g5_movie_label_result").append(labelItem);
    $("#g5_movie_label_result").append("&nbsp;");
};

g5.label.searchStorage = function(labelName) {
    var retLabel = null
    $.each(g5.label.storage, function(index, label) {
        if (label.name === labelName) {
            retLabel = label

            return;
        }
    });

    return retLabel;
}

$(document).ready(function() {

    // setup typeahead file for labels
    $("#label_name").typeahead({
        "source": function(query, process) {
            var $this = this;
            g5.label.storage = [];
            if (g5.label.jqxhr !== null) {
                g5.label.jqxhr.abort();
            }

            g5.label.jqxhr = $.ajax({
                type: "GET",
                url: Routing.generate("g5_movie_label_find", {"query": query}),
                beforeSend: function(xhr) { g5.loading(); }
            })
            .done(function(response) {
                var data = $.parseJSON(response);
                var labels = [];

                $.each(data.labels, function(index, label) {
                    g5.label.storage.push(label);
                    labels.push(label.name);
                });

                if (g5.label.searchStorage($this.query) === null) {
                    labels.push($this.query);
                }

                return process(labels);
            })
            .always(function() {
                g5.doneLoading();
            });
        },

        "highlighter": function(item) {
            var text = "";
            var res = g5.label.searchStorage(item);
            if (res === null) {
                text += "<i class='icon-plus-sign'></i> ";
            }
            text += item;

            return text;
        },

        "updater": function(item) {
            var jqxhr = $.ajax({
                type: "POST",
                url: Routing.generate("g5_movie_label_add"),
                data: {
                    "label[name]": item,
                    "label[_token]": $("#label__token").val()
                },

                beforeSend: function(xhr) { g5.loading(); }
            })
            .done(function(response) {
                var data = $.parseJSON(response);
                if (data.status === "OK") {
                    g5.label.addLabel(data.label);
                    g5.label.showMessage("text-success", data.message);
                } else {
                    g5.label.showMessage("text-error", data.message);
                }
            })
            .always(function() {
                g5.doneLoading();
            });

            return item;
        }
    });
});
