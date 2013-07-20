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

/**
 * @param  Object       formContainer
 * @param  string|int   uid             Unituqe id (need for multiple implemenation)
 */
g5.label.dispatchForm = function(formContainer, uid)
{
    // form elements
    formContainer.show();
    var $uid = uid;
    var form = formContainer.children(".g5movie_label_new_form");
    var labelInput = form.find("input[name='label[name]']");
    var labelToken = form.find("input[name='label[_token]']");
    var messageBox = formContainer.children(".g5_movie_label_messageBox");
    var labelBox = $("#label-box-"+uid);

    labelInput.typeahead({
        "source": function(query, process) {
            var $this = this;
            g5.label.storage = [];
            if (g5.label.jqxhr !== null) {
                g5.label.jqxhr.abort();
            }

            g5.label.jqxhr = g5.ajaxRequest({
                type: "GET",
                url: Routing.generate("g5_movie_label_find", {"query": query})
            }, function(response) {
                var data = response;
                var labels = [];

                $.each(data.labels, function(index, label) {
                    g5.label.storage.push(label);
                    labels.push(label.name);
                });

                if (g5.label.searchStorage($this.query) === null) {
                    labels.push($this.query);
                }

                return process(labels);
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
            g5.ajaxRequest({
                type: "POST",
                url: Routing.generate("g5_movie_label_add"),
                data: {
                    "label[name]": item,
                    "label[movie_id]": $uid,
                    "label[_token]": labelToken.val()
                }
            }, function(response) {
                var data = response;
                if (data.status === "OK") {
                    g5.label.addLabel(labelBox, data.label);
                    g5.label.showMessage(messageBox, "text-success", data.message);
                } else {
                    g5.label.showMessage(messageBox, "text-error", data.message);
                }
            });
            formContainer.hide();

            return item;
        }
    });

    labelInput.focus();

}

/**
 * @param  Object msgBox
 * @param  string type    Twitter Bootstrap CSS class
 * @param  string message
 */
g5.label.showMessage = function(msgBox, type, message)
{
    msgBox.html("");

    var msg = $("<small>");
    msg.addClass(type);
    msg.html(message);

    msgBox.append(msg);
    msgBox.show();
};

/**
 * @param  Object labelBox  The view element
 * @param  Object label
 */
g5.label.addLabel = function(labelBox, label)
{
    var labelItem = $("<span>");
    labelItem.addClass("label");
    labelItem.attr("data-labelId", label.id);

    var labelDelItem = $("<span>");
    labelDelItem.addClass("close");
    labelDelItem.html("&VerticalSeparator;&times;");

    labelItem.append(labelDelItem);
    labelItem.append(label.name);

    labelBox.append(labelItem);
    labelBox.append("&nbsp;");
};

/**
 * @param  string labelName
 *
 * @return Object|null
 */
g5.label.searchStorage = function(labelName) {
    var retLabel = null
    $.each(g5.label.storage, function(index, label) {
        if (label.name === labelName) {
            retLabel = label

            return;
        }
    });

    return retLabel;
};

$(document).ready(function() {
    // Label button binding
    $("[id|='label-trigger']").bind('click', function() {
        var $this = this;
        var uid = $(this).attr("data-id");
        // load label form
        g5.ajaxRequest({
            type: "GET",
            url: Routing.generate("g5_movie_label_new"),
        }, function(response) {
            // start label form
            $("#label-form-"+uid).html(response);
            g5.label.dispatchForm($("#label-form-"+uid), uid);
        });
    });
});
