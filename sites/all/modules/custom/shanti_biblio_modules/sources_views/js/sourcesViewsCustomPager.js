/**
 * @file
 * Custom javascript functionalities for CSC custom views custom pager.
 */
(function ($) {
Drupal.behaviors.sourcesViewsCustomPager = {
  attach: function (context, settings) {
    // Restrict input to numbers only
    $('#pager-input').keypress(function(e) {
      if (e.charCode < 48 || e.charCode > 57) return false;
    });
    // Redirect on enter key
    $('#pager-input').keydown(function(e) {
      if (e.which == 13) {
        var pager_path = window.location.href;
        var new_pager = ($(this).val() <= parseInt($('#max-page-input').val())) ? '&page=' + ($('#pager-input').val() - 1) : '&page=' + ($('#max-page-input').val() - 1);
        if ($.get_query_string_val('page')) {
          var old_page_path = $.get_query_string_val('page');
          var old_pager = '&page=' + old_page_path;
          var new_page_path = pager_path.replace(old_pager, new_pager);
        }
        else {
          var new_page_path = pager_path + new_pager;
        }
        window.location.replace(new_page_path);
      }

    });
  }
};
})(jQuery);
