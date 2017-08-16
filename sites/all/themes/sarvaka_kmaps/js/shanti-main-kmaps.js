(function ($) {

  Drupal.behaviors.shantiKmapsSubjectsHomepage = {
    attach: function (context, settings) {

        if ($(".main-col .carousel-header").length > 0 ) {
              $(".breadcrumb").css('display', 'none');
              $(".btn.view-offcanvas-sidebar").addClass('hidden');

        } else {
              $(".breadcrumb").css('display', 'block');
              $(".btn.view-offcanvas-sidebar").removeClass('hidden');
        }
    }
  };

  Drupal.behaviors.searchPanelHeightKMaps = {
    attach: function (context, settings) {
      if (context == document) {
        var $extruder = $('#search-flyout .extruder-content');
        var $kmfacets = $('#kmfacet-list');

        if ($kmfacets.length == 0) { // subjects or places
          /* http://remy.bach.me.uk/blog/2011/04/getting-the-height-of-a-hidden-element-using-jquery/ */
          var invisibleHeight = function ($el) {
            $el.css({
              'display': 'block',
              'visibility': 'hidden'
            });
            var height = $el.innerHeight();
            $el.css({
              'display': 'none',
              'visibility': 'visible'
            });
            return height;
          };

          var $inputsec = $('#kmaps-search .input-section');
          var input_height = $('.search-filters', $inputsec).length == 1 ? 225 : 68; // places but not subjects has search filters

          // *** SEARCH *** adapt search panel height to viewport for scrolling treenav area
          Drupal.ShantiSarvaka.searchTabHeightKMaps = function (e) {
            var extruder_height;
            if ($extruder.css('display') == 'block') {
              extruder_height = $extruder.innerHeight();
              input_height = $inputsec.height(); // height of input-section may have changed
            }
            else {
              extruder_height = invisibleHeight($extruder);
            }
            var viewheightKMaps = extruder_height - input_height - 75;
            $extruder.find('.view-wrap').css('height', viewheightKMaps + 'px');
          };

          //Drupal.ShantiSarvaka.searchTabHeightKMaps();
          $(window).bind('load orientationchange resize', Drupal.ShantiSarvaka.searchTabHeightKMaps);

          // prevent overscrolling into body
          $extruder.find('.view-wrap').scrollLock();
        }
        else { // mandala home page
          Drupal.ShantiSarvaka.searchTabHeightKMaps = function (e) {
            if ($extruder.css('display') == 'block') {
              var view_height = $extruder.innerHeight() - 60; // tab_height = 60
              $('.km-facet-div > div').css('height', view_height + 'px');
            }
          };
          $(window).bind('load orientationchange resize', Drupal.ShantiSarvaka.searchTabHeightKMaps);
          $('#search-flyout').on('extopen', Drupal.ShantiSarvaka.searchTabHeightKMaps);

          // prevent overscrolling into body
          $('.km-facet-div > div').scrollLock();
        }
      }
    }
  };

})(jQuery);
