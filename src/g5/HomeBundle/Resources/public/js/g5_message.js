/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $ */

var g5Message = (function() {
    'use strict';

    /**
     * PUBLIC
     */
    return {
        showMessage: function(msg, delay) {
            delay = delay || 1000;

            var el = $('<div>').addClass('messageInfo').html(msg).hide();
            $('#messageInfo').html(el.fadeIn().delay(delay).fadeOut('slow'));
        }
    };
}());
