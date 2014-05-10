/*jslint browser: true, devel: true, white: true */
/*globals $ */

/**
 * Ajax Request Queue
 */
var g5AjaxQueue = (function() {
    'use strict';

    var activeRequestCount = 0,
        srQueue = {},   // Single(ton) Request Queue
        requestId = 0,

        getRequestId = function() { requestId += 1; return requestId; };

    return {
        /**
         *  Dispatches a singleton ajax request, meaning that if there's already a pending
         *  request in the same queue, it will be aborted before the new one is started.
         *
         * @param  string   queue
         * @param  object   params      jQuery.ajax() style configuration
         * @param  function callback    Function which is called once the request finished
         * @param  bool     showLoading Display the loading spinner
         *
         * @return int  Request Id
         */
        ajaxSingle: function(queue, params, callback, showLoading) {

            // Sanatize params
            queue = queue || 'default';
            params = params || {};
            callback = callback || function() { /* empty Fn */ };
            showLoading = showLoading || true;

            // Abort running requests ...
            if (srQueue[queue] !== null && srQueue[queue] !== undefined) {
                srQueue[queue].abort();
            }

            // ... and dispatch a new one
            var jqxhr = $.ajax({
                type: params.type,
                url: params.url,
                data: params.data,
                context: this
            })
            .done(callback)
            .always(function(jqxhr) {
                if (srQueue[queue].showLoading) {
                    this.loadingHide();
                }
                srQueue[queue] = null;
            })
            .fail(function() {
                g5Message.showMessage('Error please try again later.');
            });

            jqxhr.queueId = queue;
            jqxhr.requestId = getRequestId();
            jqxhr.showLoading = showLoading;
            srQueue[queue] = jqxhr;

            // Loading indicator
            if (showLoading) {
                this.loadingShow();
            }

            return jqxhr.requestId;
        },
        loadingShow: function() {
            $("#loadingInfo").fadeIn(0);
            activeRequestCount += 1;
        },
        loadingHide: function() {
            activeRequestCount -= 1;
            if (activeRequestCount <= 0) {
                activeRequestCount = 0; // Fix negative values
                $("#loadingInfo").fadeOut(0);
            }
        }
    };
}());
