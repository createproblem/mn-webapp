/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $ */

var g5MovieLabel = (function() {
    /**
     * PRIVATE
     */
    var addLabel = function(movieId, labelId) {
        g5AjaxQueue.ajaxSingle('add-label', {
            'type': 'POST',
            'url': '#'
        }, function(response) {
            console.log(response);
        });
    }

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
                    labelInput.autocomplete({
                        source: function(request, response) {
                            // do search lookup
                            var term = request.term;
                            g5AjaxQueue.ajaxSingle('label-loopup', {
                                'type': 'GET',
                                'url': Routing.generate('get_labels', {'q': term})
                            }, function (result) {
                                var data = [];

                                $.each(result, function(index, label) {
                                    data.push({
                                        'label': label.name,
                                        'value': label.id
                                    });
                                });

                                response(data);
                            });
                        },
                        select: function(event, ui) {
                            console.log(ui);
                        }
                    });
                    labelInput.focus();
                });
            });
        }
    };
}());


$(document).ready(function() {
    $('.btnLabelAdd').each(function(index, el) {
        // Extract movieId
        var movieId = $(el).attr('data-movieid');

        g5MovieLabel.bindLabelEvent(el, $('#label-form-'+movieId));
    });
});
