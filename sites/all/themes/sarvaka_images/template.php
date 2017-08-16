<?php

/**
 * @file
 * template.php
 */

 /**
  *   This is the template.php file for a child sub-theme of the Shanti Sarvaka theme.
  *   Use it to implement custom functions or override existing functions in the theme. 
  */ 
  
function sarvaka_images_preprocess_page(&$vars) {
    // Remove extra search tabs for pages with "search/site" in the current path
    $cp = current_path();
    if (strpos($cp, 'search/site/') > -1) {
        $vars['tabs']['#primary'] = array();
    }
}

/**
 * Implements hook_preprocess_node
 *      Mainly for collections and subcollections for now
 */
function sarvaka_images_preprocess_node(&$vars) {
    //dpm($vars, "in pp node");
    switch ($vars['type']) {
        case 'collection':
        case 'subcollection':
            if ($vars['view_mode'] == 'teaser') {
            $uri = (isset($vars['field_general_featured_image'][0]['uri'])) ?$vars['field_general_featured_image'][0]['uri'] : $vars['field_general_featured_image']['und'][0]['uri'];
            $vars['thumbnail_url'] = image_style_url('image_collection_thumbnail', $uri);
            $vars['item_count'] = _get_items_in_collection($vars['nid']);
            $user = user_load($vars['uid']);
            $vars['user_link'] = l($user->name, 'user/' . $user->uid); // User Link
            // Collection
            $vars['coll'] = '';
            if (!empty($vars['content']['field_og_parent_collection_ref'])) {
                $coll = $vars['content']['field_og_parent_collection_ref']['#items'][0]['entity'];
                $coll->url = url('node/' . $coll->nid);
                $vars['coll'] = $vars['content']['field_og_parent_collection_ref']['#items'][0]['entity'];
            }
            
            // Description
            $vars['desc'] = '';
            if (isset($vars['body']['und'][0]['safe_value'])) {
                $safeval =$vars['body']['und'][0]['safe_value'];
                if (strlen($safeval) > 60) {
                    $vars['desc'] = substr($safeval, 0, strpos($safeval, ' ', 60)) . '... </p>';
                } else {
                    $vars['desc'] = $safeval;
                }
            }
            
            // Access
            $vars['access'] = '';
            if (isset($vars['group_access']['und'][0]['value']) ) {
                $vars['access'] = ($vars['group_access']['und'][0]['value'] == 0) ? t("Public") : t("Private");
            } // End of collection teasers
        }
    }
}

/**
 * Impelments hook_preprocess_views_view
 * 
 * Used to adjust markup and add js/css for the image grid on the home page.
 */
function sarvaka_images_preprocess_views_view(&$vars) {

	$view = $vars['view']; // Get the view

	// Views that use the custom grid code
	$grid_views = array(
	    'media_sharedshelf_my_images',
	    'collection_images_sharedshelf',
    );
	if (in_array($view->name, $grid_views) || in_array($view->name . "_" . $view->current_display, $grid_views)) {
		
		// Load JavaScript
		drupal_add_js(drupal_get_path('theme', 'sarvaka_images') . '/js/contrib/grid.js', array('group'=>JS_LIBRARY, 'weight'=>9990));
		drupal_add_js(drupal_get_path('theme', 'sarvaka_images') . '/js/contrib/jquery.row-grid.js', array('group'=>JS_LIBRARY, 'weight'=>9980));
		drupal_add_js(drupal_get_path('theme', 'sarvaka_images') . '/js/contrib/photoswipe.js', array('group'=>JS_LIBRARY, 'weight'=>9970));
		drupal_add_js(drupal_get_path('theme', 'sarvaka_images') . '/js/contrib/photoswipe-ui-default.js', array('group'=>JS_LIBRARY, 'weight'=>9960));
		drupal_add_js(drupal_get_path('theme', 'sarvaka_images') . '/js/contrib/jquery.actual.min.js', array('group'=>JS_LIBRARY, 'weight'=>9950));
		
		// Load Page JS Settings
		$countsettings = array(
			'sarvaka_image_gallery' => array(
				'total_items' => $view->query->pager->total_items,
				'items_per_page' => $view->query->pager->options['items_per_page'],
				'page_number' => $view->query->pager->current_page,
				'item_count' => count($view->result),
			),
		);
		drupal_add_js($countsettings, 'setting');
		
		// Load CSS
		drupal_add_css(drupal_get_path('theme', 'sarvaka_images') . '/css/grid-components.css');
		drupal_add_css(drupal_get_path('theme', 'sarvaka_images') . '/css/photoswipe.css');
		drupal_add_css(drupal_get_path('theme', 'sarvaka_images') . '/css/pswp-default-skin.css');
		
		// Process Results
		$results = $view->result;
		$rows = '<div id="og-grid" class="og-grid clearfix">';
		
		// Iterate through results, get info about each file, and build item html 

		foreach ($results as $res) {
			$file = file_load($res->fid); // Load file
			$furl = url('file/' . $file->fid); // Url to file's page
			// Get image paths for various sizes (thumb, large, huge)
			$file_ext = ($file->type == 'document') ? '.jpg' : sarvaka_images_get_image_extension($file);
			$furi = str_replace('sharedshelf://', 'public://media-sharedshelf/', check_plain($file->uri)) . $file_ext;
			$thumb_path = image_style_url('media_thumbnail', $furi) ; 		// Thumb path for grid
			$large_path = image_style_url('media_large', $furi) ;					// Large path for popup
			$huge_path = image_style_url('media_lightbox_large', $furi) ;	// Huge path for lightbox
			// Get dimensions for huge image and append to url with "::" separators (url::width::height)
			$hugepts = explode('/sites/', $huge_path);
			$hugepts = explode('?', $hugepts[1]);
			$huge_info = image_get_info('sites/' . $hugepts[0]);
			$huge_path .= '::' . $huge_info['width'] . '::' . $huge_info['height']; 
			
			// Get details about file from metadata_wrapper
		    $info_bundle = array('bundle' => $file->type);
		    $wrapper = entity_metadata_wrapper('file', $file, $info_bundle);
			$ftitle = $file->filename;
			// Creator
			$creator = sarvaka_images_metadata_process($wrapper->field_sharedshelf_creator->value());
			if(empty($creator)) {$creator = "Not available";}
			// Date
			$date = sarvaka_images_metadata_process($wrapper->field_sharedshelf_date->value());
			if(empty($date)) {$date = "Not available";} else { $date = date('F j, Y', strtotime($date)); }
			$photographer = sarvaka_images_metadata_process($wrapper->field_sharedshelf_photographer->value());
			// Photographer
			if(empty($photographer)) {$photographer = "Not available";}
			// Place
			$place = sarvaka_images_metadata_process($wrapper->field_sharedshelf_place->value());
			if(empty($place)) {$place = "Not available";}
			// SSID
			$ssid = sarvaka_images_metadata_process($wrapper->field_sharedshelf_ssid->value());
			
			// Description
			$fdesc = $wrapper->field_sharedshelf_description->value(array('sanitize' => TRUE));
			if (empty($fdesc)) {$fdesc = t("No description currently available.");} 
				// Trim Description 
			if (strlen($fdesc) > 750) { // Trim to 750 characters
				$fdesc = substr($fdesc, 0, 750);
				$fdesc = substr($fdesc, 0, strrpos($fdesc, ' ')) . "...";
			}
			// Type of file (from mimetype)
			$dtype = substr($file->filemime, strpos($file->filemime, '/') + 1); // Take last part of mimetype
			// Create HTML
			$rows .= '<div class="item">
		    		<a href="' . $furl . '" data-largesrc="' . $large_path . '" data-hugesrc="' . $huge_path . '" data-title="' . $ftitle . '" data-description="' . $fdesc . '" 
			    	data-creator="' . $creator . '" data-photographer="' . $photographer . '" data-date="' . $date . '" data-place="' . $place . '" data-type="' . $dtype . '" 
			    	data-ssid="' . $ssid . '" > <img src="' . $thumb_path . '" alt="' . $ftitle . '" />
			    </a>
		    </div>';
		}
		$rows .= '</div>';
		$vars['rows'] = $rows;
	}
}

function sarvaka_images_metadata_process($mdinfo) {
	if (is_array($mdinfo)) {
		if(count($mdinfo) > 0) {
			return $mdinfo[0];
		} else {
			return "";
		}
	} else {
		return $mdinfo;
	}
}

function sarvaka_images_get_image_extension($file) {
	$mimetype = $file->filemime;
	switch ($mimetype) {
      case 'image/jpeg':
        return '.jpg';
        break;

      case 'image/tiff':
        return '.tif';
        break;

      case 'image/png':
        return '.png';
        break;

      case 'image/gif':
        return '.png'; // Shared Shelf Gifs get saved as PNGs in Drupal (?)
        break;

      default:
        return '';
    }
}

/**
 * Implements hook_preprocess_file_entity
 * 
 * 	   (Not used yet.)
function sarvaka_images_preprocess_file_entity(&$vars) {
	dpm($vars, 'vars in file pp');
	//$vars['content_attributes'][] = "clearfix";
}

 */
 
/**
 * Implements hook preprocess field
 * 		Hide empty fields in image nodes
 */
function sarvaka_images_preprocess_field(&$vars) {
	if ($vars['element']['#bundle'] == 'image' && count($vars['element']['#items']) == 1 && empty($vars['element'][0]['#markup'])) {
		$vars['classes_array'][] = "hidden";
	}
}

/**
 * Implements hook breadcrumb alter
 *    for search pages just make the breadcrumb search
 */
function sarvaka_images_menu_breadcrumb_alter(&$active_trail, $item) {
	if ($item['path'] == 'search/site/%') {
		$active_trail = array(); // remove default breadcrumbs which are messed up
		drupal_set_title(t("Search for “@term”", array('@term' => $item['page_arguments'][1])));
		return;
	} else {
	   $map = $item['map']; // Item map tells us about what page we are on
        if ($map[0] == "node") {
            $node = $map[1];
            // if it's a collection node, add link to all collections before it's name 
            if (in_array($node->type, array('collection', 'subcollection'))) {
                $collslink = array(
                  'title' => t('Collections'), 
                  'href' => 'collections', 
                  'link_path' => 'collections', 
                  'localized_options' => array(), 
                  'type' => 0,
                );
                $newat = array();
                $newat[0] = array_shift($active_trail);
                $newat[1] = $collslink;
                $active_trail = array_merge($newat, $active_trail);
            } 
        } else if ($item['path'] == 'collections' && count($active_trail) == 3 && $active_trail[1]['link_title'] == "Collections") {
            unset($active_trail[1]); // Remove the extra "collections" breadcrumb from user menu set up.
        }
	}
 }

/**
 * Implements preprocess search results
 */
function sarvaka_images_preprocess_search_results(&$vars) {
	$vars['query_params'] = $vars['query']->getParams();
}
	
/**
 * Implements preprocess search result
 * 		Removes snippet and info (for now) and adds thumb url
 */

function sarvaka_images_preprocess_search_result(&$vars) {
	$vars['snippet'] = '';
	$vars['info'] = '';
	$vars['title_full'] = $vars['title'];
	$vars['title'] = truncate_utf8($vars['title'], 40, FALSE, TRUE);
	$file = file_load($vars['result']['fields']['entity_id']);
	$uri = str_replace('sharedshelf://', 'public://media-sharedshelf/', $file->uri);
	$surl = image_style_path('media_thumbnail', $uri . '.jpg');
	$turl = file_create_url($surl);
	$vars['thumb_url'] = $turl;
}


/**
 * Return the count of number of items in a collection
 */
function _get_items_in_collection($coll=FALSE, $return="count") {
    $nids = array();
    if (is_numeric($coll)) {  $coll = node_load($coll); } // Load collection node if id given
    if ($coll) {
        // Get all collection and subcollection nids invovled
        $nids[] = $coll->nid;
        $nids = array_merge($nids, _get_subcollections_in_collection($coll));
        /**
         * Sample Query:
         *      select count(etid) from og_membership where entity_type='node' and field_name='field_og_collection_ref' and gid in (3,1721,1725,1769,2228,2258,3498,3939,4836,4835,1760,1748,1841);
         * 
         * NOTE: TODO: the results of this query returns a larger number of results than the view shows for THL. WHY is this?
         */
        $result = db_select('og_membership', 'ogm')
                            ->fields('ogm', array('etid'))
                            ->condition('entity_type', 'node')
                            ->condition('field_name', 'field_og_collection_ref')
                            ->condition('gid', $nids, 'IN')
                            ->execute();
        $nids = $result->fetchCol();
        if ($return == "nids") {   return $nids;  }
    }
    return count($nids);
}


/**
 * Return list of subcollections in collection
 *      Returns an array of nids
 */
 
 function _get_subcollections_in_collection($coll=FALSE) {
    $nids = array();
    if (is_numeric($coll)) {  $coll = node_load($coll);   } // convert nid to node
    // Only collections have subcollections
    if ($coll->type == "collection") {
        /**
         * Sample Query:
         *       select etid from og_membership where entity_type='node' and field_name='field_og_parent_collection_ref' and gid=3;
         **/
        $result = db_select('og_membership', 'ogm')
                            ->fields('ogm', array('etid'))
                            ->condition('entity_type', 'node')
                            ->condition('field_name', 'field_og_parent_collection_ref')
                            ->condition('gid', $coll->nid)
                            ->execute();
        $nids = $result->fetchCol();
        //drupal_set_message(count($nids) . ' subcols found');
    }
    return $nids;
 }
