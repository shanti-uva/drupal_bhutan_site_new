(function ($) {
  Drupal.behaviors.kmapsExplorerCustom = {
    attach: function (context, settings) {
      var $related = $('#sidebar-first > ul.nav-pills > li a');
      $related.click(function () {
        $('#sidebar-first > ul.nav-pills > li').removeClass("active");
        $(this).parent().addClass("active");
      });

      //Include index item to breadcrumbs for normal page loads
      if (Drupal.settings.kmaps_explorer) {
        $('ol.breadcrumb li:first-child a').html(Drupal.settings.kmaps_explorer.app + ': ');
      } else {
        $('ol.breadcrumb li:first-child a').html('');
      }

      //Functionality for history popstate
      $(window).on('popstate', function(e) {
        if (!e.originalEvent.state.tag) return;
        window.location.href = location.href;
      });

      // Functionality for columnizer
      $('.kmaps-list-columns:not(.subjects-in-places)', context).columnize({
            width: 330,
            lastNeverTallest : true
      });
      // dontsplit = don't break these headers
      $('.places-in-places').find('.column > h6, .column > ul > li, .column ul').addClass("dontsplit");
      // dontend = don't end column with headers
      $('.places-in-places').find('.column > h6, .column > ul > li').addClass("dontend");


      //Functionality for popovers
      $('.popover-kmaps', context).each(function() {
        var $that = $(this);
        var app = $that.data("app");
        var kid = $that.data("id");
        $that.popover({
          html: true,
          title: '<span id="pop-title-' + kid + '">Loading ...</span>',
          content: '<span id="pop-content-' + kid + '">Please wait while captions and related assets load. Loading ...</span>'
        });
        $that.on('show.bs.popover', function() {
          $('#pop-title-' + kid, context).html('');
          $('#pop-content-' + kid, context).html('');
          $('div.popover').hide();
          $.get('/mandala/popover/populate/' + app + '/' + kid, function(data) {
            //data = $.parseJSON(data);
            if (data.node) {
              if (data.node && data.node.header) {
                $('#pop-title-' + kid, context).html(data.node.header);
              }
              $('#pop-content-' + kid, context).html(populateSolrPopover(data));
            }
          });
        });
      });

      //Functionality for images to add location and id
      $('.related-photos > .modal', context).each(function() {
        var $that = $(this);
        var id = $that.attr('id');
        var pid = $that.data('place');
        $that.on('show.bs.modal', function(e) {
          var url = Drupal.settings.shanti_kmaps_admin.shanti_kmaps_admin_server_places + '/features/' + pid + '.json';
          $.get(url, function(data) {
            $('#' + id + ' .modal-body .image-modal-location .places-loc', context).html(data.feature.header);
          });
        });
      });

      function populateSolrPopover(data) {
        //Create markup for related places in images.
        var template = $('#popover-general').html();
        var templateScript = Handlebars.compile(template);
        var html = templateScript(data);
        return html;
      }

      $('.transcripts-ui-search-form', context).once('transcript-search-wrapper', function() {
        var $form = $(this);
        var trid = $form.attr('data-transcripts-id');
        var base = 'transcript-search-button-' + trid;

        var element_settings = {
          url: 'http://' + window.location.hostname +  settings.basePath + settings.pathPrefix + 'system/ajax',
          event: 'click',
          progress: { type: 'throbber' },
          callback: 'transcripts_ui_ajax_hits',
        };

        Drupal.ajax[base] = new Drupal.ajax(base, this, element_settings);
        $(this).click();
      });

      //Extend handlebars to support comparison Functionality
      Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {
        switch (operator) {
          case '==':
            return (v1 == v2) ? options.fn(this) : options.inverse(this);
          case '===':
            return (v1 === v2) ? options.fn(this) : options.inverse(this);
          case '<':
            return (v1 < v2) ? options.fn(this) : options.inverse(this);
          case '<=':
            return (v1 <= v2) ? options.fn(this) : options.inverse(this);
          case '>':
            return (v1 > v2) ? options.fn(this) : options.inverse(this);
          case '>=':
            return (v1 >= v2) ? options.fn(this) : options.inverse(this);
          case '&&':
            return (v1 && v2) ? options.fn(this) : options.inverse(this);
          case '||':
            return (v1 || v2) ? options.fn(this) : options.inverse(this);
          default:
            return options.inverse(this);
        }
      });

      //Extend handlebars to support increment function
      Handlebars.registerHelper("inc", function(value, options) {
        return parseInt(value) + 1;
      });

      //Capitalize first letter
      Handlebars.registerHelper("capitalizeFirstLetter", function(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
      });

      //Extract id from solr id
      Handlebars.registerHelper("extractId", function(str) {
        return str.split("-")[1];
      });

    }
  };

})(jQuery);
