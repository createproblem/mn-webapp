/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $, g5AjaxQueue, Routing, g5Message */

$(document).ready(function() {
    'use strict';

    $('#btnDeleteUnusedLabels').on('click', function() {
        g5AjaxQueue.ajaxSingle('delete-labels', {
            'type': 'DELETE',
            'url': Routing.generate('delete_labels', { 'unused': true })
        }, function(response) {
            g5Message.showMessage(response.labels_deleted + ' labels deleted.');
        });
    });
});
