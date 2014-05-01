/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $ */

var g5MovieLabel = (function() {
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
                    labelInput.autocomplete({ source: [ "c++", "java", "php", "coldfusion", "javascript", "asp", "ruby" ] });
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
