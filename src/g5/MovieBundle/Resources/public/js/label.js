/* jshint strict: true, undef: true, browser: true, debug: false */
/* globals $, Backbone, _, g5AjaxQueue, Routing, g5Message, labels */

var g5MovieLabel = (function() {
    'use strict';
    var Label = Backbone.Model.extend({}),

    LabelCollection = Backbone.Collection.extend({
        model: Label
    }),

    LabelView = Backbone.View.extend({
        tagName: 'span',
        className: 'label label-default margin-right-xs',

        events: {
            'click i.label-remove': 'remove'
        },

        /**
         * Constructor.
         */
        initialize: function() {
            _.bindAll(this, 'render', 'unrender');
            this.model.bind('remove', this.unrender);
        },

        /**
         * Removes the label
         */
        remove: function() {
            this.model.url = Routing.generate('delete_movie_label', {
                id: this.model.get('movieId'),
                labelId: this.model.get('id')
            });
            this.model.destroy();
        },

        /**
         * Removes the label from DOM
         */
        unrender: function() {
            $(this.el).remove();
        },

        /**
         * Renders a Label model.
         *
         * @return {[object]} The view itself.
         */
        render: function() {
            $(this.el).html(this.model.get('name') + ' <i class="label-remove glyphicon glyphicon-remove"></i>');
            return this;
        }
    }),

    LabelBoxView = Backbone.View.extend({
        /**
         * Constructor.
         */
        initialize: function() {
            _.bindAll(this, 'render', 'addLabel', 'appendLabel');

            this.collection = new LabelCollection();
            this.collection.bind('add', this.appendLabel);

            this.render();
        },

        /**
         * Renders the whole collection
         */
        render: function() {
            var self = this;

            _(this.collection.models).each(function(label) {
                self.appendLabel(label);
            }, this);
        },

        /**
         * Adds a label.
         *
         * @param {[object]} labelData {name: 'name', id: 123}
         */
        addLabel: function(labelData) {
            var label = new Label({
                id: labelData.id,
                name: labelData.name,
                movieId: labelData.movieId
            });
            this.collection.add(label);
        },

        /**
         * Renders a single label.
         *
         * @param  {[object]} label Label model.
         */
        appendLabel: function(label) {
            var labelView = new LabelView({
                model: label
            });

            $(this.el).append(labelView.render().el);
        }
    }),

    /**
     * Submits the autocomplete form.
     *
     * @param  {[object]}  form
     * @param  {[integer]} movieId
     */
    submitForm = function(form, movieId) {
        var data = {};
        $.each($(form).serializeArray(), function(index, rawData) {
            data[rawData.name] = rawData.value;
        });

        movieId = movieId || data['link[movie_id]'];

        g5AjaxQueue.ajaxSingle('add-label', {
            'type': 'POST',
            'url': Routing.generate('post_movie_label', {'id': movieId, '_format': 'html' }),
            'data': data
        }, function(response) {
            $('#label-box-'+movieId).html(response);
        });
        $(form).remove();
    },

    /**
     * Bind autocomplete on input.
     *
     * @param  {[object]}  input
     * @param  {[integer]} movieId
     * @param  {[object]}  form
     */
    bindLabelAutocomplete = function(input, movieId, form) {
        $(input).selectize({
            create: true,
            labelField: 'name',
            valueField: 'name',
            searchField: 'name',
            load: function(query, callback) {
                g5AjaxQueue.ajaxSingle('label-lookup', {
                    'type': 'GET',
                    'url': Routing.generate('get_labels', {'q': query})
                }, function(response) {
                    callback(response);
                });
            },
            onItemAdd: function(value, $item) {
            },

            onDelete: function(values) {
            }
        });
    };


    /**
     * PUBLIC
     */
    return {
        /**
         * Binds the addLabel event for autocomplete.
         *
         * @param  {[object]}  triggerElement
         * @param  {[integer]} movieId
         */
        bindLabelEvent: function(triggerElement, movieId) {
            var resultElement = $('#label-form-'+movieId);
            var saveElement = $('#label-save-'+movieId);


            $(triggerElement).on('click', function() {
                g5AjaxQueue.ajaxSingle('label-form', {
                    'type': 'GET',
                    'url': Routing.generate('get_movie_label_form', {'id': movieId, '_format': 'html'})
                }, function(response) {
                    $(resultElement).html(response).show();

                    var labelInput = $('#label-form-' + movieId + ' input#link_name');
                    var labelForm = $('#label-form-' + movieId + ' form');

                    $(saveElement).on('click', function() {
                        submitForm(labelForm, movieId);

                        $(this).hide();
                        $(triggerElement).show();
                    });

                    bindLabelAutocomplete(labelInput, movieId, labelForm);
                });
                $(this).hide();
                $(saveElement).show();
            });
        },

        /**
         * Initialize the label box view which contains all labels.
         *
         * @param  {[object]} data
         * @return {[object]}
         */
        createView: function(data) {
            return new LabelBoxView(data);
        }
    };
}());


$(document).ready(function() {
    'use strict';

    top.labelViews = {};

    // bind label autocomplete form and events
    $('.label-trigger').each(function(index, el) {
        // Extract movieId
        var movieId = $(el).attr('data-movieid');

        g5MovieLabel.bindLabelEvent(el, movieId);
    });

    // Bind label box events for
    // controlling
    // $('.label-box').each(function(index, labelBox) {
    //     // extract movieId
    //     var movieId = $(labelBox).attr('data-movieid');
    //     var labelView = g5MovieLabel.createView({
    //         el: $(labelBox)
    //     });
    //     $.each(labels[movieId], function(index, label) {
    //         labelView.addLabel({
    //             id: label.id,
    //             name: label.name,
    //             movieId: movieId
    //         });
    //     });

    //     top.labelViews[movieId] = labelView;
    // });
});

// $(document).ready(function() {
//     $('.input-labels').selectize({
//         persist: true,
//         create: true,
//         labelField: 'name',
//         valueField: 'name',
//         searchField: 'name',

//         load: function(query, callback) {
//             g5AjaxQueue.ajaxSingle('label-lookup', {
//                 'type': 'GET',
//                 'url': Routing.generate('get_labels', {'q': query})
//             }, function(response) {
//                 callback(response);
//             });
//         }
//     });
// });
