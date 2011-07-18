jQuery(document).ready(function(){
	// Set up click event for handling when a user clicks the quick edit link
	jQuery('.editinline').live('click', function(){
		// Get the tag id
		var tag_id = jQuery(this).parents('tr').attr('id');		
		// Get the end date
		var end_date = jQuery('.end-date', '#'+tag_id).text();
		var start_date = jQuery('.start-date', '#'+tag_id).text();
		// Place order value in the form				
		jQuery(':input[name="end-date"]', '.inline-edit-row').val(end_date);
		jQuery(':input[name="start-date"]', '.inline-edit-row').val(start_date);
		return false;
	});
});