/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $, g5AjaxQueue, Routing */

var g5MovieLabel = (function() {
    'use strict';

    /**
     * PRIVATE
     */
    var addLabel = function(labelId, movieId) {
        g5AjaxQueue.ajaxSingle('add-label', {
            'type': 'POST',
            'url': Routing.generate('post_movie_label', {'id': movieId, '_format': 'html'}),
            'data': {
                'labelId': labelId
            }
        }, function(response) {
            if(response.indexOf('Label already assigned.') > -1) {
                g5Message.showMessage(response);
            } else {
                $('#label-box-'+movieId).append(response);
            }
            $('#label-form-' + movieId + ' input#link_name').hide();
        });
    },

    bindLabelAutocomplete = function(el, movieId) {
        $(el).autocomplete({
            source: function(request, response) {
                var term = request.term;
                g5AjaxQueue.ajaxSingle('label-loopup', {
                    'type': 'GET',
                    'url': Routing.generate('get_labels', {'q': term})
                }, function(result) {
                    var data = [];
                    $.each(result, function(index, label) {
                        data.push({
                            'label': label.name,
                            'value': label.name,
                            'id': label.id
                        });
                    });
                    response(data);
                });
            },
            select: function(event, ui) {
                addLabel(ui.item.id, movieId);
            }
        }).focus();
    };

    /**
     * PUBLIC
     */
    return {
        bindLabelEvent: function(triggerElement, resultElement) {
            // Extract movieId
            var movieId = $(triggerElement).attr('data-movieid');

            // get label form
            $(triggerElement).on('click', function() {
                g5AjaxQueue.ajaxSingle('label-form', {
                    'type': 'GET',
                    'url': Routing.generate('get_movie_label_form', {'id': movieId, '_format': 'html'})
                }, function(response) {
                    $(resultElement).html(response).show();

                    var labelInput = $('#label-form-' + movieId + ' input#link_name');
                    bindLabelAutocomplete(labelInput, movieId);
                });
            });
        }
    };
}());


$(document).ready(function() {
    'use strict';

    $('.btnLabelAdd').each(function(index, el) {
        // Extract movieId
        var movieId = $(el).attr('data-movieid');

        g5MovieLabel.bindLabelEvent(el, $('#label-form-'+movieId));
    });
});
