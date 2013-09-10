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
g5.label.storage = [];
g5.label.uid = 0;
g5.label.labelBox = null;
g5.label.formContainer = null;
g5.label.query = null;

/**
 * @param  string labelName
 *
 * @return Object|null
 */
g5.label.searchStorage = function(labelName) {
    var retLabel = null;
    $.each(g5.label.storage, function(index, label) {
        if (label.name.toLowerCase() === labelName.toLowerCase()) {
            retLabel = label;

            return;
        }
    });

    return retLabel;
};

/**
 * Helper function for autocomplete
 *
 * @param  Object ul
 * @param  Object item
 *
 * @return Object
 */
g5.label._renderItem = function(ul, item)
{
    var res = g5.label.searchStorage(item.label);
    var text = null;

    if (res === null) {
        text = "<a><span class='glyphicon glyphicon-plus'></span> "+item.label+"</a>";
    } else {
        text = "<a>"+item.label+"</a>";
    }

    return $("<li></li>")
        .data("item.autocomplete", item )
        .append(text)
        .appendTo(ul)
    ;
};

/**
 * @param  Object   label
 * @param  int      movieId
 */
g5.label.renderLabel = function(label, movieId)
{
    var a = $("<a></a>");
    var spanLabel = $("<span></span>");
    var spanRemove = $("<span></span>");

    a.attr("href", Routing.generate('g5_movie_label_index', { name: label.name_norm }));
    a.html(label.name);

    spanRemove.addClass("label-remove glyphicon glyphicon-remove");
    spanLabel.addClass("label label-default");
    spanLabel.attr("data-labelId", g5.label.uid);
    spanLabel.attr("data-movieId", movieId);
    spanLabel.append(a);
    spanLabel.append(spanRemove);

    spanRemove.bind('click', function() {
        g5.label.unlinkLabel(spanLabel, label.id, movieId);
    });

    g5.label.labelBox.append(spanLabel);
    g5.label.formContainer.hide();
};

/**
 * Links the label to the movie
 *
 * @param  Object event
 * @param  Object ui
 */
g5.label.linkLabel = function(event, ui)
{
    var labelToken = $(this).parent().find("input[name='link[_token]']").val();
    g5.ajaxRequest({
        type: "POST",
        url: Routing.generate("g5_movie_api_label_add"),
        data: {
            "link[name]": ui.item.value,
            "link[movie_id]": g5.label.uid,
            "link[_token]": labelToken
        }
    }, function(response) {
        if (response.status === "OK") {
            g5.label.renderLabel(response.label, g5.label.uid);
        } else {
            g5.label.formContainer.hide();
        }
    });
};

/**
 * Label lookup
 *
 * @param  Object   request
 * @param  function process
 */
g5.label.findLabels = function(request, process)
{
    g5.label.query = request.term;
    g5.label.storage = [];

    if (g5.label.jqxhr !== null) {
        g5.label.jqxhr.abort();
    }

    g5.label.jqxhr = g5.ajaxRequest({
        type: "GET",
        url: Routing.generate("g5_movie_api_label_find", {"query": g5.label.query})
    }, function(response) {
        var labels = [];
        $.each(response.labels, function(index, label) {
            g5.label.storage.push(label);
            labels.push({
                "label": label.name,
                "value": label.name
            });
        });

        if (g5.label.searchStorage(g5.label.query) === null) {
            labels.push({
                "label": g5.label.query,
                "value": g5.label.query
            });
        }

        process(labels);
    });
};

/**
 * Unlinks the label from the movie
 *
 * @param  Object label
 * @param  int labelId
 * @param  int movieId
 */
g5.label.unlinkLabel = function(label, labelId, movieId)
{
    g5.ajaxRequest({
        type: "GET",
        url: Routing.generate('g5_movie_api_unlink', { labelId: labelId, movieId: movieId })
    }, function(response) {
        if (response.status === 'OK') {
            label.hide();
        }
    });
};

/**
 *  Deletes a Label permanently
 *
 * @param  int labelId
 */
g5.label.deleteLabel = function(labelId)
{
    g5.ajaxRequest({
        type: "GET",
        url: Routing.generate('g5_movie_api_label_delete', { labelId: labelId })
    }, function(response) {
        if (response.status === 'OK') {
            console.log(response);
        }
    });
};

/**
 * @param  Object       formContainer
 * @param  string|int   uid             Unituqe id (need for multiple implemenation)
 */
g5.label.dispatchForm = function(formContainer)
{
    g5.label.formContainer = formContainer;
    formContainer.show();
    var form = formContainer.children(".g5movie_label_new_form");
    var labelInput = form.find("input[name='link[name]']");

    // bind focus lose event
    labelInput.bind('blur', function() {
        formContainer.hide();
    });

    labelInput.autocomplete({
        source: g5.label.findLabels,
        select: g5.label.linkLabel
    }).data("ui-autocomplete")._renderItem = g5.label._renderItem;

    labelInput.focus();
};

$(document).ready(function() {
    $("[id|='label-trigger']").bind('click', function() {
        var $this = this;
        g5.label.uid = $(this).attr("data-id");
        g5.label.labelBox = $("#label-box-"+g5.label.uid);

        // load label form
        g5.ajaxRequest({
            type: "GET",
            url: Routing.generate("g5_movei_api_label_new")
        }, function(response) {
            // start label form
            $("#label-form-"+g5.label.uid).html(response);
            g5.label.dispatchForm($("#label-form-"+g5.label.uid));
        });
    });

    // bind label unlink
    $(".label .label-remove").bind("click", function() {
        var label = $(this).parent(".label");
        var labelId = label.attr('data-labelId');
        var movieId = $(this).parent(".label").attr('data-movieId');
        g5.label.unlinkLabel(label, labelId, movieId);
    });

    // bind label delete button on label view
    $("#btnDeleteLabel").bind('click', function() {
        var labelId = $(this).attr("data-labelId");
        g5.label.deleteLabel(labelId);
    });
});
