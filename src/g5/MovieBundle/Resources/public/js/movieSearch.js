"use strict";
/*global $, Routing, Backbone, _, g5 */
/*jshint jquery:true, globalstrict:true */

////////////////////////
//// Init View /////////
////////////////////////
var Movie = Backbone.Model.extend({
    defaults: {
        "dataLoaded": false
    },

    loadData: function() {
        if (this.get('dataLoaded') === true) {
            return;
        }
        g5.loading();
        var self = this;
        var data = {
            "tmdbid": this.get('tmdbid')
        };

        $.getJSON(Routing.generate('g5_movie_loadmeta', data), function(response) {
            var data = response;
            self.set('overview', data.overview);
            self.set('dataLoaded', true);
            $("#overview").html(data.overview);
            g5.doneLoading();
        });
    }
});

var MovieCollection = Backbone.Collection.extend({
    model: Movie
});

var MovieView = Backbone.View.extend({
    el: $("#searchResult"),

    initialize: function() {
        _.bindAll(this, 'render', 'addItem');

        this.collection = new MovieCollection();
        this.counter = 0;
        this.itr = 0;
    },

    addItem: function(item) {
        this.counter++;
        this.collection.add(item);
    },

    render: function() {
        var movie = this.collection.at(this.itr);

        var addHref = Routing.generate('g5_movie_add', { "tmdbid": movie.get('tmdbid') });

        var body = "<p><a class='btn btn-small btn-primary' href='"+addHref+"'>Add Movie</a></p>";

        var html = "<div class='media'>";
        html += "<img class='media-object pull-left' src='"+movie.get('image')+"'>";
        html += "<div class='media-body'>";
        html += "<h4 class='media-heading'>"+movie.get('title')+"</h4>";
        html += body;
        html += "</div>";
        html += "</div>";
        $(this.el).html(html);
        renderPagination(this.itr);

        console.log("render");
    },

    next: function() {
        var itr = this.itr + 1;
        if (itr < this.collection.length) {
            this.itr = itr;
        }
        this.render();
    },

    previous: function() {
        var itr = this.itr - 1;
        if (itr >= 0) {
            this.itr = itr;
            this.render();
        }
    }
});

var movieView = new MovieView();

/////////////////////////
//// Helper Function ////
/////////////////////////

function renderPagination(current)
{
    var previous = current - 1;
    var next = current + 1;
    console.log('next: '+next);
    console.log('previous: '+previous);
    console.log('view: '+movieView.collection.length);

    var btnNext = $("#btnNext").parent();
    var btnPrevious = $("#btnPrevious").parent();

    // rewrite btnNext
    if (previous < 0) {
        btnPrevious.addClass('disabled');
        console.log('dis: prev');
    } else {
        btnPrevious.removeClass('disabled');
        console.log('en: prev');
    }

    // rewrite btnPrevious
    if (next >= movieView.collection.length) {
        btnNext.addClass('disabled');
        console.log('dis: next');
    } else {
        btnNext.removeClass('disabled');
        console.log('en: next');
    }
}

////////////////////////
//// AJAX Search ///////
////////////////////////

function searchMovie()
{
    g5.loading();
    var data = $('#formMovieSearch').serialize();
    $.post(Routing.generate('g5_movie_lookup'), data, function(response) {
        var data = response;
        // Add results to collection
        $.each(data.results, function(index, value) {
            var movie = new Movie();
            movie.set({
                tmdbid: value.id,
                title: value.original_title,
                image: data.imgUrl + value.poster_path
            });
            movieView.addItem(movie);
        });

        $("#movieList").show();
        g5.doneLoading();
        movieView.render();
    });
}

$(document).ready(function() {
    // bind search event
    $('#btnMovieSearch').bind('click', function() {
        searchMovie();
        return false;
    });

    // bind pagination
    $('#btnNext').bind('click', function() {
        movieView.next();
    });

    $('#btnPrevious').bind('click', function() {
        movieView.previous();
    });
});
