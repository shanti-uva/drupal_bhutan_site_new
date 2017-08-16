
/**
 * Set help link changes help link when chart type is changed
 */			
	
function setHelpLink(hlink, typestr) {
	jQuery('#iframe_container legend a.sn-helplink').attr({
      'href' : hlink,
      'title' : typestr + " help"
    }).show();
    
  // Reset Title and Breadcrumbs
  var newtitle = 'Create a ' + typestr;
	var pts = jQuery('.breadcrumb').html().split('› ');
	pts[pts.length - 1] = newtitle;
	jQuery('.breadcrumb').html(pts.join('› '));
  jQuery('#page-title').text(newtitle);
}

(function($) {
/** shivanode.js: Javascript functions for use within shivanode Drupal Module */
	
/**
 *    Use Drupal.behaviors as $(document).ready() to call upon load.
 *      This is called after every ajax call. Hence the use of context.
 * 			May have to detect context to run some of this only when major page loads.
 */

	/** 
	 * General behaviors
	 * 
	 */
	Drupal.behaviors.shivaGeneral = {
		attach: function (context, settings) {
		    if (context == document) {
        		    if (window.location.pathname.indexOf('/edit') > -1) {
        		        if ($('#sneditloadmsg').length == 0) {
                		        $('#shivaEditFrame').before('<div id="sneditloadmsg">Loading ...</div>');
                		    }
        		        $('#shivaEditFrame').hide();
        		    }
    		    }
		}
	};
	
	/** 
	 * Views behaviors: Adjusts Shiva views
	 * 	Used for admin view
	 */
	Drupal.behaviors.shivaAdjustViews = {
		attach: function (context, settings) {
			if ($('.view-id-admin_content').length > 0) {
				$(".form-item.form-type-textfield.form-item-nid > input#edit-nid").attr('size','10');
				$(".views-exposed-widget.views-widget-sort-by").css('clear', 'both');
			}
		}
	};
	
	// Node view behaviors
	Drupal.behaviors.shivaViewNode = {
		attach: function (context, settings) {
			// Assign shiva_settings variable from drupal settings
			shiva_settings = Drupal.settings.shivanode;
	
			// Initialize HTML5 messaging
			if (window.addEventListener) {
				window.addEventListener('message', Drupal.Shivanode.shivaMessageHandler, false);
			}
			else {
				window.attachEvent('message', Drupal.Shivanode.shivaMessageHandler);
			}
			
			// if format=simple then hide header footer and sidenavs
			var ss = window.location.search;
			if (ss.indexOf('format=simple') > -1) {
				$('html').addClass('lightpop');
				//$('#admin-menu, #header, #footer, #sidebar-first, #sidebar-second').hide()
				$('#main-wrapper').height(800);
			}
			
			// Hide Header etc. for specific shivanode pages in an Iframe
			var snpageclasses = '.shivanode_data_elements, .shivanode_google_spreadsheets'; // Classes of pages that appear in popups.
			if ($(snpageclasses).length == 1 ) {
				if (self != top) {
					$('html').addClass('lightpop');
					$(snpageclasses).addClass('popup');
				}
			}
			$.each($('.snviewer iframe'), function() {
				if (!$(this).attr('src') || $(this).attr('src') == '' ) {
					window.location.reload();
				}
			});
                      
			// Make all Shiveyes Site links open in new window
			$("a:contains('Shiveyes Site')").attr('target','_blank');
			
			var srch = window.location.search;
			if (typeof(srch) == "string" && srch.indexOf('insert=') > -1) {
				$did = srch.substr(srch.indexOf('=') + 1);
				$('body *').hide();
				isnew = ($('#lightbox').length == 0) ? true : false;
				Drupal.Shivanode.setDataElement($did, isnew);
			}
			
			// Show popup is popup=dataurl is in url
			if (window.location.search.indexOf('popup=dataurl') > -1) {
			  if (Drupal.Shivanode.getShivaCookie('Drupal.Shivanode.popup') != 'done') {
			    var etyp = Drupal.Shivanode.getShivaCookie('Drupal.Shivanode.lastElementType');
			    if (etyp) {
		            $('#edit-shivanode-element-type-und').val(etyp);
		            $('#edit-shivanode-element-type-und').change();
		          }
	            $('#use-data-element-link a').click();
	  			Drupal.Shivanode.setShivaCookie('Drupal.Shivanode.popup', 'done', 1);
			  }
	    }
			
		  // Enable Gallery select buttons
		  if (window.location.pathname == '/mygallery') {
		     $('.shivagallerychooser select[name=gallerytype]').val(['my']);
		  } else {
		     $('.shivagallerychooser select[name=gallerytype]').val(['all']);
		  }
		  $('.shivagallerychooser select[name=gallerytype]').change(function() {
		    var val = $(this).val();
		    if (val == 'my' ) {  
		      window.location.href='/mygallery';
		    } else if (val == 'all') {
		      window.location.href='/';
		    }
		  });
		}
	}; // End of Drupal.behaviors.shivaViewNode
	
	// Entry form Behaviors
	Drupal.behaviors.shivaEntryFormConfig = {
		attach: function (context, settings) {
			// Force Process Ready Message upon edit window load because sometimes data does not get loaded into the form
			$(window).load(function() { 
			    if (Drupal.settings.shivanode.qmedia == true) {
			        setTimeout(function() {
                        var mdata = Drupal.settings.shivanode.qmjson;
                        mdata = (mdata == "") ? Drupal.settings.shivanode.jsonFromDrupal : mdata;
			            Drupal.Shivanode.loadQmedia(mdata);
			            setTimeout(function() {$('#sneditloadmsg').remove(); $('#shivaEditFrame').slideDown("slow");}, 500);
			        }, 1000);
			    } else {
        			    Drupal.Shivanode.processReadyMessage();
        			}
			 });
			// Send Data to SHIVA editor once loaded
			if ($('iframe#shivaEditFrame').length > 0) {
				Drupal.Shivanode.setUnload(); // Set the unload function to check whether data changed.
				
				// On load for all visualization
				$('iframe#shivaEditFrame').load(function() {
				  //Drupal.Shivanode.shivaSendMessage("shivaEditFrame", "GetType"); // once edit frame is loaded send GetType message just to register this page as parent frame
					//var json = $('#edit-shivanode-json-und-0-value').val();

					if ($('#iframe_container legend .form-required').length == 0) {
						$('#iframe_container legend span').append('<span title="This field is required." class="form-required">*</span>');
					}

					// Make Edit frame as wide as fieldset
					/* Using CSS and markup to deal with this
					var pw = $('#shivaEditFrame').parent().width();
					var w = $('#shivaEditFrame').width();
					if (pw > w) {$('#shivaEditFrame').width(pw);}
					*/
					
					// Add change listener to visualization type, when it's a data entry show add other data entries link
					$('#edit-shivanode-element-type-und').change(function() {
						var v = $('#edit-shivanode-element-type-und option[value=' + $(this).val() + ']').text();
						if (v == 'Data') {
							$('.spreadsheet-add-de-link').show();
						} else {
							$('.spreadsheet-add-de-link').hide();
						}
					});
					
					// Register last submit button clicked in window.lastButtonClicked variable to check if leaving without saving in
					//     .setWindowUnloadConfirm function
					$('input[type="submit"]').each(function() {
						$(this).click(function() { window.lastButtonClicked = $(this).attr('id'); });
					});
					
					// Prevent Return from registering in text areas???
					$("input.form-text").keypress(function(e){
						if (e.which==13) {
							return false;
						}
					});
					
					// Enable save etc. buttons
					$('#otherbuttons button[disabled=disabled], #lowerbuttons button[disabled=disabled], ' +
  					'#otherbuttons input[disabled=disabled], #lowerbuttons input[disabled=disabled]').each(function() {
  				  $(this).removeAttr('disabled').removeClass('form-button-disabled');
  				});
  				
  				shiva_settings.formLoaded = true;
				});
				
				// Adjust JSON field
				// move the required asterisk from the uneditable JSON field to the Iframe legend
				$("#shivanode_json_div label:contains('Shiva Visualization')").text('JSON Value');
				$('#shivanode-json-add-more-wrapper label span.form-required').appendTo('#iframe_container legend span');
				$("#shivanode_json_div label:contains('JSON Value'), #shivanode_json_div textarea")
					.attr('title','This field is uneditable. Use the form above to edit the visualization. Click here to refresh JSON based on form above.');
				if ($('#json-hide-link').length == 0 ) {
					$("#shivanode_json_div label:contains('JSON Value')")
						.append('<span> (<a href="#" id="json-hide-link" class="toggle-link" ' +
						'onclick="Drupal.Shivanode.toggleJsonElement(); return false;" title="Hide this field">Hide</a>)</span>');			
				}
			}
		}
	}; // End of Drupal.behaviors.shivaEntryFormConfig
	
	Drupal.behaviors.shivaPopup = {
		attach: function (context, settings) {
			if(context == document) {
				// Only perform on data selector popup
				if (window.location.pathname == '/mydata' && window.location.search.indexOf('format=simple') > -1) {
					// Hide popup once something is chosen
					$('.views-table td.views-field-nothing a').click(function() {
						$('body').hide();
					});
					// Remove links from titles in popups
					$('.views-table .views-field-title a').each(function() {
						$(this).parent().html('<span>' + $(this).text() + '</span>');
					});
				}
			}
		}
	};
	/**
	 * Add query string to no results message
	 */
	Drupal.behaviors.shivaSearch = {
        attach: function (context, settings) {
            if(context == document) {
                var qsspan = $(".no-results .query-string");
                if (qsspan.length == 1) {
                    var href = window.location.href;
                    var results = href.match(/\?search_api_views_fulltext=([\w\W]+)/);
                    if (results.length > 1) {
                        qsspan.eq(0).text(results[1]);
                    }
                }
            }
        }
    };
	
}) (jQuery);
