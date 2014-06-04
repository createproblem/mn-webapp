$(document).ready(function() {
    $('.btn-gen-token').each(function(index, el) {
    	$(el).on('click', function() {
    		var userId = $(this).attr('data-user-id');
    		$.get(Routing.generate('g5_account_admin_generate_token', {user_id: userId}), function(response) {
    			$('#token-'+userId).text(response.access_token);
    		});
    	});
    });
});
