(function ($) {


/*
 * MbExtruder Init
 */
/* Extruder GIT Doc - https://github.com/pupunzi/jquery.mb.extruder/wiki */
Drupal.behaviors.sarvakaMbextruder = {
  attach: function (context, settings) {
  	if(context == document) {
	    var mywidth = Drupal.settings.shanti_sarvaka.flyoutWidth;
	    $(".input-section, .view-section, .view-section .nav-tabs>li>a").css("display","block"); // show hidden containers after loading to prevent content flash
			// Remove extruder div content so as to preserve AJAX events
			var mbContent = $("#search-flyout .region.region-search-flyout").detach();
	    // Initialize Search Flyout
	    $("#search-flyout").buildMbExtruder({
	      positionFixed: false,
	      position: "right",
	      closeOnExternalClick:false,
	      closeOnClick:false,
	      width: mywidth, // width is set in two places, here and the css
	      top: 0,
		  onExtOpen: function() {
	          $("#search-flyout").trigger("extopen");
          }
        });
	    // Add back in extruder content
	    $('#search-flyout .text').append(mbContent);
	//    $('#search-flyout .text').css('overflow','hidden');
	    // Make it resizeable
	    try { 
		    if($("div.extruder-content > div.text").length > 0) {
          		$('.extruder-content > .text').append( '<span class="icon shanticon-close2"></span>' ); // top-right close button, visible for small mobile screens only
          		$('.extruder-content > .text > .shanticon-close2').click(function() { $('#search-flyout').closeMbExtruder(); } );

			    $("div.extruder-content > div.text").resizable({
			      handles: "w,nw",
			      resize: function (event, ui) {
			      	$('#search-flyout .extruder-content').css('width','');
			        //$('span.fancytree-title').trunk8({ tooltip:false });
			      }
			    });
			}
		    // Bind event listener
		    $(".extruder-content").resize(Drupal.ShantiSarvaka.checkWidth);
		    // Add identifier
		    // $(".extruder-content").attr("aria-label","Search Panel");
		   } catch (e) { 
		   	  console.warn('Resizeable not a function error caught! shanti-search.js line 31');
		   }

	    if (!$(".extruder.right").hasClass("isOpened")) {
	      $(".flap").click( function() {
	        $(".extruder .text").css("width","100%");
	      });
	      // styles inline for now, forces
	      $(".flap").prepend("<span><span class='icon shanticon-search'></span></span>");
	      $(".flap").addClass("on-flap");
	      // Add identifiers
	      $(".flap").attr("role", "button");
	      $(".flap").attr("aria-label", "Open Search Panel");
	    }

	    // --- set class on dropdown menu for icon
	    $('.shanti-field-title a').hover( function() {
	      $(this).addClass('on-hover');
	    },
	    function () {
	      $(this).removeClass('on-hover');
	    });

	    // --- set class on dropdown menu for icon
	    $(".extruder.right .flap").hover(
	      function () {
	        $(this).addClass('on-hover');
	      },
	      function () {
	        $(this).removeClass('on-hover');
	    });
	    // Show Flyout tab hidden on load
	    $('#search-flyout').show();
	    // Inizialize bootstrap elements inside flyout region
	    $('#search-flyout .selectpicker').selectpicker({
	      dropupAuto: false,
	    }); // initiates jq-bootstrap-select


        // listen for 'extopen', the event that signals that the search flyout is being opened.
        $('#search-flyout').on('extopen', function() {
            setTimeout(function () {
                $('#search-flyout .treeview').trigger('shown.bs.tab');
            }, 30);
        });

        /* color change for ui-resize handle */
        $('.extruder .ui-resizable-nw').hover(function () { 
        	$('.extruder .ui-resizable-w').css('opacity','1'); 
        },
          function () {
            $('.extruder .ui-resizable-w').css('opacity','0.8');
          }
        );

    }
  }
};

/**
 * Open Extruder by default
 */
Drupal.behaviors.shanti_sarvaka_flyoutinit = {
    attach: function (context, settings) {
        if (context == document && Drupal.settings.shanti_sarvaka.flyoutautoopen) {
            if ($(window).width() >= 992) {  
                var flyout_status = $.cookie('flyout_status');
                if (!$.cookie('flyout_status') || $.cookie('flyout_status') == 'open') {
                    setTimeout( function() { $('#search-flyout').openMbExtruder(); }, 300);
                }
            }
        }
    }
};

Drupal.behaviors.shanti_sarvaka_flyoutupdate = {
    attach: function (context, settings) {
        if (context == document && Drupal.settings.shanti_sarvaka.flyoutautoopen) {
            var flyout_status = $.cookie('flyout_status');
            $('#search-flyout div.flap').click(function(e) {
                if ($('#search-flyout').attr('isOpened')) {
                    var flyout_status = 'open';
                    $('#search-flyout').openMbExtruder();
                }
                else {
                    var flyout_status = 'close';
                }
                $.cookie('flyout_status', flyout_status, {path:'/'});
                e.preventDefault();
            });
        }
    }
};

/**
* Search Init
*/
Drupal.behaviors.sarvakaSearchInit = {
	attach: function (context, settings) {
		if(context == document) {

	        Drupal.ShantiSarvaka.searchTabHeight();
	        $(window).bind('load orientationchange resize', Drupal.ShantiSarvaka.searchTabHeight );
	    }
	}
};
  

/* Testing for improved positioning of popover son search tree ----------------
  Drupal.behaviors.sarvakaSearchPopoverPosition = {
    attach: function (context, settings) {
    	if(context == document) {
			$('[data-toggle=popover]').on('shown.bs.popover', function () {
			  $('.search-popover.left').css('left',parseInt($('.popover').css('left')) - 15 + 'px')
			})
	    }
    }
  };
*/


})(jQuery);
