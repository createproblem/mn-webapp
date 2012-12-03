function addMovie(id)
{
    var movieFormData = $("#movieForm"+id).serialize();
    $.post('/save', movieFormData, function(response){
        $('#addMovieContent').html("");
        $('#addMovieContent').append(response); 
    });
}

var Movie = function() {
    this.data = new Array();
    this.tmdb_id = function(val) {
        if (typeof val === "undifened" || val === null) {
            return this.data['tmdb_id'];
            
        } else {
            this.data['tmdb_id'] = val;
            return val;
        }
    }
}

function addLabel()
{
    var data = $("#addLabelForm").serialize();
    $.post("/label/add", data, function(response) {
        $('#addLabelContent').html("");
        $('#addLabelContent').append(response); 
    });
}
