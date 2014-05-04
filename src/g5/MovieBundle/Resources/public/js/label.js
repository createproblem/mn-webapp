/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $, g5AjaxQueue, Routing, g5Message */

var g5MovieLabel = (function() {
    'use strict';

    /**
     * Submits the form
     *
     * @param  object form
     */
    var submitForm = function(form, movieId) {
        var data = {};
        $.each($(form).serializeArray(), function(index, rawData) {
            data[rawData.name] = rawData.value;
        });

        movieId = movieId || data['link[movie_id]'];

        g5AjaxQueue.ajaxSingle('add-label', {
            'type': 'POST',
            'url': Routing.generate('post_movie_label', {'id': movieId}),
            'data': data
        }, function(response) {
            if('error' in response) {
                g5Message.showMessage(response.error);
            } else {
                $('#label-box-'+movieId).append(response);
            }
            $('#label-form-' + movieId + ' input#link_name').hide();
        });
    },

    /**
     * Bind autocomplete.
     *
     * @param  {[object]}  input
     * @param  {[integer]} movieId
     * @param  {[object]}  form
     */
    bindLabelAutocomplete = function(input, movieId, form) {
        $(input).autocomplete({
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
                submitForm(form, movieId);
            }
        }).focus();
    };

    /**
     * PUBLIC
     */
    return {
        /**
         * Bind form event.
         *
         * @param  {[object]} triggerElement
         * @param  {[object]} resultElement
         */
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
                    var labelForm = $('#label-form-' + movieId + ' form');
                    bindLabelAutocomplete(labelInput, movieId, labelForm);
                });
            });
        },

        /**
         * Submit form.
         *
         * @param  {[object]} form
         */
        submitForm: function(form) {
            submitForm(form);
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
