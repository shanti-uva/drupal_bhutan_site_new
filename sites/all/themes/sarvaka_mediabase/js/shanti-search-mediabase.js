(function ($) { // jQuery wrapper function

	// Add Placeholder text ("Enter Search") to Views search input
	Drupal.behaviors.shanti_sarvaka_mb_search_input_update = {
		attach: function (context, settings) {
			if (context == document) {
				$("div.form-type-textfield input.form-text").each(function() {
					if ($(this).attr('placeholder') == "undefined" || $(this).attr('placeholder') == "") {
						$(this).attr('placeholder', 'Enter Search...');
					}
				});
				// Workflow view search boxes
				$('.view-my-workflow input#edit-title-1').attr('placeholder', 'Enter Video Title'); 
                $('.view-my-workflow input#edit-title').attr('placeholder', 'Enter Collection Name'); 
                $('.view-my-workflow input#edit-realname').attr('placeholder', 'Enter Owner Name'); 
                $('.view-my-workflow input#edit-nid-min, .view-my-workflow input#edit-nid-1-min').attr('placeholder', 'Enter Min'); 
                $('.view-my-workflow input#edit-nid-max, .view-my-workflow input#edit-nid-1-max').attr('placeholder', 'Enter Max'); 
                
			}
		}
	};
	

} (jQuery)); // End of JQuery Wrapper
