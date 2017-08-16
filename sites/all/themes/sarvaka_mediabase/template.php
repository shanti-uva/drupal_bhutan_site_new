<?php

/**
 * @file
 * template.php
 */
define('MBFRAME', 'mbframe'); // Name of MBFRAME Cookie

function sarvaka_mediabase_theme() {
  return array(
    'audio_node_form' => array(
      'render element' => 'form',
      'template' => 'av-node-form',
      'path' => drupal_get_path('theme', 'sarvaka_mediabase') . '/templates'
    ),
    'collection_node_form' => array(
      'render element' => 'form',
      'template' => 'av-node-form',
      'path' => drupal_get_path('theme', 'sarvaka_mediabase') . '/templates'
    ),
    'video_node_form' => array(
      'render element' => 'form',
      'template' => 'av-node-form',
      'path' => drupal_get_path('theme', 'sarvaka_mediabase') . '/templates'
    ),
  );
}

/* overridinng shanti_sarvaka_file_widget which causes bugs MANU-3696 and MANU-3700 */
function sarvaka_mediabase_file_widget($variables) {
  $element = $variables['element'];
  $output = '';

  // The "form-managed-file" class is required for proper Ajax functionality.
  $output .= '<div class="file-widget form-managed-file clearfix">';
  if ($element['fid']['#value'] != 0) {
    // Add the file size after the file name.
    $element['filename']['#markup'] .= ' <span class="file-size">(' . format_size($element['#file']->filesize) . ')</span> ';
  }
  $output .= drupal_render_children($element);
  $output .= '</div>';

  return $output;
}

/**
 * Implements theme_breadcrumb
 * 		Change First Breadcrumb to link to collection/group page for group admin pages
 */
function sarvaka_mediabase_breadcrumb($variables) {
	$cp = current_path();
	// Change First Breadcrumb to link to collection/group page for group admin pages
	if (drupal_match_path($cp, 'group/node/*/admin/**')) {
		$bcs = &$variables['breadcrumb'];
		if (count($bcs) > 1 && $menuitem = menu_get_item($cp)) {
			$node = node_load($menuitem['map'][2]); // 3rd item in menu item map is the group node id
			$url = 'node/' . $node->nid;
			$bcs[1] = l($node->title, $url);
		}
	}
	return shanti_sarvaka_breadcrumb($variables);
}

function sarvaka_mediabase_form_alter(&$form, &$form_state, $form_id) {
	// Add bo class to text areas for Tibetan descriptions
	if ($form_id == 'video_node_form' || $form_id == 'audio_node_form' ) {
		foreach($form['field_pbcore_description'][$form['field_pbcore_description']['#language']] as $key => &$item) {
			if (is_numeric($key) && !empty($item['field_language'][$item['field_language']['#language']]['#default_value'][0])) {
				if ($item['field_language'][$item['field_language']['#language']]['#default_value'][0] == 'Tibetan') {
					$item['field_description']['#attributes']['class'][] = 'bo';
				}
			}
		}
	}
}

/**
 * Add path to view exposed form vars so we can group widgets into categories in template
 */
function sarvaka_mediabase_preprocess_views_exposed_form(&$vars) {
     if (current_path() == 'mycontent/workflow') {
        $vars['is_workflow'] = TRUE;
         /* Doesn't work with AJAX filters do nothing after being rearranged (2016-12-09)
        $widget_names = array_keys($vars['widgets']);
        $catindex = array_search('filter-field_basic_cataloging_value', $widget_names);
        $transindex = array_search('filter-field_timecode_too_infrequent_value', $widget_names);
        $filterindex = array_search('filter-field_video_entryid', $widget_names);

        if ($catindex < $transindex && $transindex < $filterindex) {
           $vars['media_workflow_section'] = array_slice($vars['widgets'], 0, $catindex);
           $vars['catalog_workflow_section'] = array_slice($vars['widgets'], $catindex, $transindex - $catindex);
           $vars['transcript_workflow_section'] = array_slice($vars['widgets'], $transindex, $filterindex - $transindex);
           $vars['workflow_filter_section'] = array_slice($vars['widgets'], $filterindex);
            dpm($vars, 'vars in preprocess');
        }*/
     }
}

function sarvaka_mediabase_get_facet_info($fbid) {
	$dmap = facetapi_get_delta_map();
	$searcher = 'apachesolr@solr';
	//$fbid = 'JpJnN1e8hK027W200nfaKp74Qf8XINrv';
	$facet_name = $dmap[$fbid];
	$fparts = explode(':', $facet_name);
	$facet_name = array_pop($fparts);
	$facet = facetapi_facet_load($facet_name, $searcher);
	return array($facet['label'], $facet_name);
}

function sarvaka_mediabase_current_search_keys($variables) {
  return '<span class="search-term">' .check_plain($variables['keys']). '</span>';
}

function sarvaka_mediabase_facetapi_link_active($variables) {
  // Builds link, passes through t() which gives us the ability to change the
  // position of the widget on a per-language basis.
  $replacements = array(
    '!facetapi_deactivate_widget' => theme('facetapi_deactivate_widget', $variables),
  );
  $variables['text'] = t('!facetapi_deactivate_widget', $replacements);
  $variables['options']['html'] = TRUE;
  $variables['options']['attributes']['class'][] = 'deactivate-link';
  return theme_link($variables);
}

function sarvaka_mediabase_facetapi_link_inactive($variables) {
  $variables['text'] = '<span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span> ' .$variables['text'];

  // Adds count to link if one was passed.
  if (isset($variables['count'])) {
    $variables['text'] .= ' ' . theme('facetapi_count', $variables);
  }

  $variables['options']['html'] = TRUE;
  return theme_link($variables);
}

function sarvaka_mediabase_facetapi_deactivate_widget($variables) {
  return '<span class="glyphicon glyphicon-check" aria-hidden="true"></span> ' .html_entity_decode($variables['text']);
}

/**
 * Preprocess User Profile
 */
function sarvaka_mediabase_preprocess_user_profile(&$variables) {
    $variables['user_profile']['group_audience']['#weight'] = 40; // Put Group Audience Last
}

/**
 * Implements hook_preprocess_html
 */
function sarvaka_mediabase_preprocess_html(&$vars) {
    // Add js and css to detect if in iframe and if so hide header elements
    $mpath = drupal_get_path('theme', 'sarvaka_mediabase');
    // drupal_add_css($mpath . '/css/mb-iframe.css', array('group' => CSS_THEME)); - mf8yk - deprecated 01/15/15 moved this CSS to the shanti-main-mb
    drupal_add_js($mpath . '/js/mb-iframe.js', array('weight' => -99, 'group' => JS_DEFAULT));
    if(isset($_GET[MBFRAME]) && $_GET[MBFRAME] == "on") {
        $vars['classes_array'][] ='in-frame';
    }

    // Determine if video has been added and add body class, used in mediabase-edit-form.css to hide "Add media" button when already added.
    $pcsm = $vars['page']['content']['system_main'];
    if (!empty($pcsm['#node_edit_form']) ) {
        $node = $pcsm['#node'];
        if (in_array($node->type, array('audio', 'video')) && empty($node->field_video) && empty($node->field_audio)) {
            $vars['classes_array'][] = "av-no-media";
        } else {
             $vars['classes_array'][] = "av-has-media";
        }
    }
}

function sarvaka_mediabase_preprocess_block(&$vars) {
    /*
    if(!empty($vars['block_html_id']) && strpos($vars['block_html_id'], 'browse-media-home') > -1) {
        dpm($vars, 'browse media home block vars');
    }
    */
    // Facet labels
    if(!empty($vars['#facet']['label'])) {
        $vars['#facetlabel'] = $vars['#facet']['label'];
    }
}

/**
 * Preprocess function for a NODE
 */
function sarvaka_mediabase_preprocess_node(&$vars) {
    global $base_path;
	// Preprocess Collection and Subcollection Nodes
	$ntype = $vars['type'];
    $mode = $vars['view_mode'];
    $node = $vars['node'];
    if(in_array($ntype, array('collection', 'subcollection'))) {
        // Collection Teasers
        if($mode == 'teaser') {
        	    	$vars['theme_hook_suggestions'][] = 'node__collection__teaser';   // Have them both use the same teaser template
    	        // Get thumbnail image
            if (empty($vars['field_general_featured_image']) || !isset($vars['field_general_featured_image']['und'][0]['uri'])) {
                $vars['thumbnail_url'] = $base_path . drupal_get_path('module', 'mediabase') . '/images/collections-generic.png';
            } else {
                $uri = $vars['field_general_featured_image']['und'][0]['uri'];
                $style = 'av_gallery_thumb'; // Style defined in mb_structure update 7002
                 $vars['thumbnail_url'] = image_style_url($style, $uri);
            }
            // Deal with the body/description (truncate)
            $vars['desc'] = "";
            $bfi = field_get_items('node', $node, 'body');
            if (is_array($bfi)) {
                $body = array_shift($bfi);
                if (!empty($body) && isset($body['safe_value'])) {
                    $desc = strip_tags($body['safe_value']);
                    $vars['desc'] = (strlen($desc) > 0) ? substr($desc, 0, 60) . "..." : "";
                }
            }
            //dpm($vars, 'vars');
            // Get the number of items in the collection from mb_structure.module
            $vars['item_count'] = get_items_in_collection($vars['nid']);

           $vars['coll'] = ($ntype == "subcollection") ? get_collection_ancestor_node($node) : FALSE;

        } // End of Collection Teasers
    	} // End of Collection/Subcollection Nodes
	// Preprocess a/v nodes:
	else if(in_array($ntype, array('audio', 'video'))) {

		// AV Node Teasers
		if($mode == 'teaser') {
			// Get Title language and add as variable for template
			$ew = entity_metadata_wrapper('node', $node);
			try {
				$vars['title_lang'] = lang_code($ew->field_pbcore_title[0]->field_language->value());
			} catch (EntityMetadataWrapperException $emwe) {
				watchdog('sarvaka mediabase', 'No field language in entity wrapper for node ' . $node->nid);
			}
			// Truncate title in teasers
			if(strlen($vars['title']) > 75) {
				$vars['title'] = truncate_utf8($vars['title'], 75, TRUE, TRUE);
			}
		}

		// Add collection field to group details
		if(!empty($vars['coll'])) {
			$vars['coll_title'] = $vars['coll']->title;
			// Truncate collection title in teaser if item title is longer than 60 chars
			if($vars['view_mode'] == 'teaser') {
				$vars['coll_title'] = truncate_utf8($vars['coll_title'], 32, TRUE, TRUE);
			}
			$vars['content']['group_details']['collection'] = array(
				'#type' => 'markup',
				'#markup' => "<div class=\"field field-name-av-collection\">
												<span class=\"icon shanticon-create\" title=\"Collection\"></span>&nbsp;<span class=\"field-label-span\">" .
												t('Collection') . "</span>&nbsp;<a href=\"{$vars['coll']->url}\">{$vars['coll_title']}</a></div>",
			);
		}

		// Add Icons
		if(!empty($vars['content']['group_details']['field_subcollection_new'])) {
			$vars['content']['group_details']['field_subcollection_new']['#icon'] = 'create'; 					// subcollection
		}
		if(!empty($vars['content']['group_details']['field_subject'])) {
			$vars['content']['group_details']['field_subject']['#icon'] = 'subjects'; 				// subjects
		}
		if(!empty($vars['content']['group_details']['field_location'])) {
			$vars['content']['group_details']['field_location']['#icon'] = 'places';	// places
		}
		// Remove Display of Tags in a/v nodes
		unset($vars['content']['group_details']['field_tags']);

		// Add Label as prefix for related media so it doesn't repeat for each on, if related media exist
		if (!empty($vars['content']['group_details']['field_pbcore_relation'])) {
			$vars['content']['group_details']['field_pbcore_relation']['#prefix'] = '<div class="field"><span class="field-label-span">Related Media</span></div>';
		}
	}

	// Author info
	$uid = $vars['uid'];
	$uname = $uid;
	$author = user_load($uid);
	if(!empty($author->realname)) {
		$uname = $author->realname;
	} elseif (!empty($author->name)) {
		$uname = $author->name;
	}
	$vars['user_link'] = l($uname, "user/$uid");
}

/**
 * Implements template_preprocess_entity().
 * Taken from http://drupal.stackexchange.com/a/142389/20666
 */

function sarvaka_mediabase_preprocess_entity(&$variables, $hook) {
  $function = 'sarvaka_mediabase_preprocess_' . $variables['entity_type'];
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}

/**
 * Field Collection-specific implementation of template_preprocess_entity().
*/
function sarvaka_mediabase_preprocess_field_collection_item(&$variables) {
    if (!empty($variables['content']['field_sponsor_name'][0]['#markup'])) {
        // Note misspelling of sponser in machine name.
        $role = $variables['content']['field_sponser_role'][0]['#markup'];
        if (!empty($role)) {
            $variables['content']['field_sponsor_name'][0]['#markup'] .= " ($role)";
        }
    } elseif (!empty($variables['content']['field_sponser_role'][0]['#markup'])) {
        $variables['content'] = array();
    }
}

/**
 * Impelments hook_preprocess_field:
 *  Changes labels for field collection to use "role" for label (or in the case of publisher "rome")
 */
function sarvaka_mediabase_preprocess_field(&$vars) {
    $el = &$vars['element'];
    $fn = $el['#field_name'];
    if($fn == 'field_creator') {
        $ew = entity_metadata_wrapper($el['#entity_type'], $el['#object']);
        $label = $ew->field_creator_role->value();
        if (strlen($label) > 0) { $vars['label'] = $label; }

    } else if($fn == 'field_contributor') {
        $ew = entity_metadata_wrapper($el['#entity_type'], $el['#object']);
        $label = $ew->field_contributor_role->value();
        if (strlen($label) > 0) { $vars['label'] = t('Contributing ') . $label; }

    } else if($fn == 'field_publisher') {
        $ew = entity_metadata_wrapper($el['#entity_type'], $el['#object']);
        $label = $ew->field_publisher_rome->value();
        if (strlen($label) > 0) { $vars['label'] = $label; }
    }
}

/**
 * Preprocess function for a Collection ENTRY FORM
 */
function sarvaka_mediabase_preprocess_collection_node_form(&$vars) {
	drupal_add_css(drupal_get_path('theme', 'sarvaka_mediabase') . '/css/mediabase-edit-form.css');
}

/**
 * Preprocess function for a VIDEO ENTRY FORM
 */
function sarvaka_mediabase_preprocess_video_node_form(&$vars) {
	drupal_add_css(drupal_get_path('theme', 'sarvaka_mediabase') . '/css/mediabase-edit-form.css');
}

/**
 * Preprocess function for a AUDIO ENTRY FORM
 */
function sarvaka_mediabase_preprocess_audio_node_form(&$vars) {
	drupal_add_css(drupal_get_path('theme', 'sarvaka_mediabase') . '/css/mediabase-edit-form.css');
}

/**
 * Preprocess search form
 */
function sarvaka_mediabase_preprocess_search_block_form(&$vars) {
// <label><span>Search:</span>Audio-Video</label>
    //$vars['search_block_form'] = str_replace('<label class="element-invisible" for="edit-search-block-form--2">Search </label>', '<label class="flyout-search-label"><span>Search:</span>Audio-Video</label>',  $vars['search_block_form']);
   // dpm($vars);
}

/**
 * Views Preprocess
 */
function sarvaka_mediabase_preprocess_views_view(&$vars) {
	$view = $vars['view'];
    // Browse media view: includes my media and my collections
	if (isset($view->name) && $view->name == 'browse_media') {
		$query = $view->query;

        /*
		// Make sure text search box is only size 15 on home page filter
	    $filters  = $vars['exposed'];   $vars['exposed']  = '';
	  	$filters = str_replace('name="title" value="" size="30"', 'name="title" value="" size="15"', $filters);

		// Set Dropdown selected value
		$field = $query->orderby[0]['field'];
		$direction = $query->orderby[0]['direction'];
		$selval = $query->fields[$field]['field'] . ' ' . $direction;
		$filters = str_replace("value=\"{$selval}\"", "value=\"{$selval}\" selected=\"selected\"", $filters);
		$vars['exposed']  = $filters;
			*/
	// List views of Media By Kmap
  } else if(isset($view->name) && $view->name == 'media_by_kmap') {
  	$type = $vars['display_id'];
  	$title = "";
		$kmid = $view->args[0];
		if($type == 'list_places') {
			module_load_include('inc', 'mb_location', 'mb_location');
			$place = _get_kmap_place($kmid);
			$title .= t("Resources Associated with the Place: ") . $place->header;
			$parents = array_reverse(fetch_place_dict_details($kmid), TRUE);
			$vars['lineage'] = '<div class="lineage"><span class="label">' . t("Places Tree:") . '<span> <span class="links">';
			$ppath = variable_get('kmaps_site_places', 'http://badger.drupal-dev.shanti.virginia.edu/places');
			$ppath .= variable_get('kmaps_site_path_format', '/%d/overview/nojs');
			$pout = "";
			foreach($parents as $pdid => $pname) {
				$pout .= (strlen($pout) > 0) ? ' > ': '';
				$link = sprintf($ppath, $pdid);
				$pout .= "<a href=\"$link\" target=\"_blank\">$pname</a>";
			}
			$vars['lineage'] .= $pout . '</span></div>';
		} else if($type == 'list_subcollections' || $type == 'list_subjects') {
			module_load_include('inc','kmap_taxonomy','includes/kmap');
			$kmap = new Kmap($kmid);
			$t = $kmap->get_term();
			$typestr = ($type == 'list_subjects') ? t("Subject") : t("Subcollection");
			$title .= t("Resources Associated with the @typestr: ", array('@typestr' => $typestr)) .  $t->name;
			$lineage = $kmap->render_kmap_lineage(Kmap::KMAP_LINEAGE_FULL, TRUE);
			$vars['lineage'] = '<div class="lineage"><span class="label">' . t("Subject Tree:") . '<span> <span class="links">' . $lineage . '</span></div>';
		}
		$vars['title'] = $title;
  }
}

function decodeUniChar($unicodeChar) {
	return json_decode('"'.$unicodeChar.'"');
}

/**
 * Preprocess for spaces preset form
 */
 /*
function sarvaka_mediabase_preprocess_spaces_preset_form(&$vars) {
} */

function sarvaka_mediabase_select($vars) {
	$element = &$vars['element'];

	// Deal with Attributes
	$element['#attributes']['class'][] = 'form-control';
  $element['#attributes']['class'][] = 'form-select';
  $element['#attributes']['class'][] = 'ss-select';
  $element['#attributes']['class'][] = 'selectpicker';
  if (isset($element['#attributes']['multiple'])) {
    if (!isset($element['#attributes']['data-selected-text-format'])) $element['#attributes']['data-selected-text-format'] = 'count>2';
    if (!isset($element['#attributes']['data-header'])) $element['#attributes']['data-header'] = t('Select one or more...');
  }
  element_set_attributes($element, array('id', 'name', 'size'));

	// Process Options into HTML
	$html = form_select_options($element);
	//   If exposed filter in form, add title as a first option
	if($element['#name'] == 'sort_bef_combine') {
		$element['#options'] = array('label' => $element['#title']) + $element['#options'];
		$html = form_select_options($element);
		$html = str_replace('value="label"', 'data-hidden="true"', $html);
		$html = str_replace('selected="selected"', 'selected="selected" disabled="disabled"', $html);
	}

  return '<select' . drupal_attributes($element['#attributes']) . '>' . $html . '</select>';
}

function sarvaka_mediabase_fieldset($vars) {
	$el = $vars['element'];
	if(isset($el['#id']) && $el['#id'] == 'field_collection_item_field_workflow_full_group_workflow') {
		$children = element_children($el);
		$output = '<div class="subgroup"><h5>Media Workflow</h5>';
		foreach($children as $n => $child) {
			$output .= render($el[$child]);
			if($n == 9) {
				$output.= '</div><div class="subgroup"><h5>Cataloging Workflow</h5>';
			}
			if($n == 16) {
				$output.= '</div><div class="subgroup"><h5>Transcript Workflow</h5>';
			}
		}
		$output .= '</div>';
		$vars['element']['#children'] = $output;
	}
	return shanti_sarvaka_fieldset($vars);
}

/*
function sarvaka_mediabase_field__datetime($vars) {
	return render($vars['element']);
}
*/

/**
 * Converts a language's English name into its two-letter language code
 */
function lang_code($lname) {
	// Search through list of enabled languages
	foreach (language_list() as $cd => $lang) {
		if ($lang->name == $lname) { return $cd; }
	}
	// Account for when I18n languages have not been enabled but still used in field collection
	if ($lname == "Tibetan") {return "bo";}
	if ($lname == "Chinese") {return "zh";}
	return "en";
}
