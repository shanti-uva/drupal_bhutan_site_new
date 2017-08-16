(function ($) {
  Drupal.behaviors.exploreMenu = {
    attach: function (context, settings) {
     if(context == document) {
	     $('.explore > a').click( function(event){
	           event.stopPropagation();
	           $('.collections').slideToggle(200);
	       });
	       
	     $('.collections .close').click( function(event){
	           event.stopPropagation();
	          $('.collections').slideToggle(200);
	      });
	    }
	  }
  };
})(jQuery);