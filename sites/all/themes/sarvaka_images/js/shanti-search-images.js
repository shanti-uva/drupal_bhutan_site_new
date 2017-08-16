(function ($) {
    Drupal.behaviors.shantiImagesTextFieldPlaceholders = {
        attach: function (context, settings) {
            
            // Search for Title fields
            $('.views-widget-filter-title .form-item input.form-text').each(function() {
                $(this).attr('placeholder', 'Search on Title');
            });
        }
    };
}) (jQuery);