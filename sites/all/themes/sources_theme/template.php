<?php

/**
 * @file
 * template.php
 */
 
 /**
  *   This is the template.php file for a child sub-theme of the Shanti Sarvaka theme.
  *   Use it to implement custom functions or override existing functions in the theme. 
  */ 

/**
 * Implements HOOK_breadcrumb
 * Customizes output of breadcrumbs
 */
function sources_theme_breadcrumb(&$bcs) {
      $obj = menu_get_object();
      if (!empty($obj) && $obj->type == 'biblio') {
          /*dpm($obj, 'node');
          dpm($bcs);*/
          if (!empty($obj->field_og_collection_ref)) {
              $gid = $obj->field_og_collection_ref['und'][0]['target_id'];
              $gnode = node_load($gid);
              if ($gnode->type == "subcollection") {
                  $bc = l($gnode->title, 'node/' . $gid);
                  array_splice($bcs['breadcrumb'], 1, 0, array($bc));
                  $gid = $gnode->field_og_parent_collection_ref['und'][0]['target_id'];
                  $gnode = node_load($gid);
              }
              $bc = l($gnode->title, 'node/' . $gid);
              array_splice($bcs['breadcrumb'], 1, 0, array($bc));
              $bcout = shanti_sarvaka_breadcrumb($bcs);
              return $bcout;
          }
      }
      $bchtml = sources_views_custom_breadcrumbs();
      if ($bchtml == "<ol class='breadcrumb'><li><a href='#' term_id='all'>Sources:</a></li><li><a href='#' term_id='0'></a><span class=\"icon shanticon-arrow3-right\"></span></li></ol>") {
          $bchtml = shanti_sarvaka_breadcrumb($bcs);
      }
      return $bchtml;
}

/**
 * Implements HOOK_preprocess_html().
 */
function sources_theme_preprocess_html(&$variables) {
    $cp = current_path();
  if (strstr($cp, 'sources-search?')) { drupal_goto('home'); } 
  drupal_add_js('sites/all/libraries/cookie.js/jquery.cookie.js', 'file');
  if (!sources_theme_is_front_page()) {
    sources_theme_remove_front_page_class($variables);
  }
  if(drupal_is_front_page()) {
      $variables['classes_array'][] = 'page-sources-search';
  }
}

function sources_theme_is_front_page() {
  return (drupal_is_front_page() && !$_SERVER['QUERY_STRING']);
}

function sources_theme_remove_front_page_class(&$variables) {
  if (($key = array_search('front', $variables['classes_array'])) !== FALSE) {
    unset($variables['classes_array'][$key]);
  }
}

function sources_theme_preprocess_node(&$vars) {
    
}

/**
 * Overriding theme_biblio_long($variables) in biblio_theme.inc (line 75)
 */
function sources_theme_biblio_long($variables) {
    $output = '';
    $node = $variables['node'];
    // Title Info
    $title = '';
    if (!empty($node->field_biblio_long_title)) {
        $title = $node->field_biblio_long_title['und'][0]['safe_value'];
    } else {
        $title = $node->title;
    }
    $output .= '<!-- Title info -->
                        <div class="source-field-title"><span class="field-content"> ' . $title . '</span></div>';
   if (!empty($node->biblio_secondary_title)) {
       $output .= '<div class="source-field source-field-title-2"><span class="field-content"> ' . $node->biblio_secondary_title . '</span></div>';
   }
   if (!empty($node->biblio_tertiary_title)) {
       $output .= '<div class="source-field source-field-title-3"><span class="field-content"> ' . $node->biblio_tertiary_title . '</span></div>';
   }
    // Authors
    $auths = array();
    foreach ((array)$node->biblio_contributors as $auth) {
        if ($auth['auth_category'] == 1) {
            $type = sources_misc_get_author_types($auth['auth_type']);
            $auths[] = theme('biblio_author_link', array('author' => $auth)) . " ({$type})";
        }
    }
    if (!empty($auths)) {
        $output .= '<!-- Authors -->
            <div class="source-field source-field-authors"><span class="field-content"><ul><li>';
        $output .= implode(", ", $auths) . '</li></ul></span></div><!-- end of authors -->';
    }
    
    // Publication Type
    $output .= '<div class="source-field source-field-biblio-type">  
                        <span class="field-label label-biblio-type-1">' . t('Format') . ': </span>    
                        <span class="field-content">' . $node->biblio_type_name . "</span>  </div>\n";
                        
    // Publication Year
    $output .= '<div class="source-field source-field-biblio-year">  
                            <span class="field-label label-biblio-year-1">' . t('Publication Year') . ': </span>    
                            <span class="field-content">' . $node->biblio_year . "</span>  </div>\n";
                            
    // Publication Info 
    if (!empty($node->biblio_publisher)) {
        $output .= '<div class="source-field source-field-publisher">  
                                <span class="field-label label-publisher">' . t('Publisher') . ': </span>    
                                <span class="field-content">' . $node->biblio_publisher . "</span>  </div>\n";
    }
    if (!empty($node->biblio_place_published)) {
        $output .= '<div class="source-field source-field-pubplace">  
                                <span class="field-label label-pubplace">' . t('Place of Publication') . ': </span>    
                                <span class="field-content">' . $node->biblio_place_published . "</span>  </div>\n";
    }
    
    // Pages 
    if (!empty($node->biblio_pages)) {
        $output .= '<div class="source-field source-field-pages">  
                                <span class="field-label label-pages">' . t('Pages') . ': </span>    
                                <span class="field-content">' . $node->biblio_pages . "</span>  </div>\n";
    }
    
    // ID
    $output .= '<div class="source-field source-field-id">  
                            <span class="field-label label-id">' . t('Source ID') . ': </span>    
                            <span class="field-content">shanti-sources-' . $node->biblio_citekey . "</span>  </div>\n";
                            
    // Collection
    if (!empty($node->field_og_collection_ref['und'])) {
        $coll = $node->field_og_collection_ref['und'][0]['entity'];
        $output .= '<div class="source-field source-field-collection">  
                                <span class="field-label label-collection">' . t('Collection') . ': </span>    
                                <span class="field-content">' . l($coll->title, '/node/' . $coll->nid) . "</span>  </div>\n";
    }

    // Visibility
    if (!empty($node->field_group_content_access['und'][0])) {
        $output .= '<div class="source-field source-field-visibility">  
                                <span class="field-label label-visibility">' . t('Visibility') . ': </span>    
                                <span class="field-content">' . $node->field_group_content_access['und'][0]['#markup'] . "</span>  </div>\n";
    }
    
    // Zotero Collections
    $zcolls = array();
    foreach ($node->field_zotero_collections['und'] as $n => $term) {
        $term = taxonomy_term_load($term['tid']);
        if ($term) {
            $zcolls[] = l($term->name, "/sources-search?field_zotero_collections={$term->tid}&view_mode=collection");
        }
    }
    if (!empty($zcolls)) {
        $output .= '<div class="source-field source-field-zotero">  
                                <span class="field-label label-zotero">' . t('Zotero Collections') . ': </span>    
                                <span class="field-content">' . implode($zcolls, ', ') . "</span>  </div>\n";
    }
                            
    // Extract
    if (!empty($node->biblio_abst_e)) {
        $output .= '<div class="source-field source-field-abstract-e">  
                                <span class="field-label label-abstract-e">' . t('Abstract') . ': </span>    
                                <span class="field-content">' . $node->biblio_abst_e  . "</span>  </div>\n";
    }
                          
    return $output;
}

/**
 * Implements THEME_preprocess_views_view_fields().
 */
function sources_theme_preprocess_views_view_fields(&$vars) {
  if ($vars['view']->name == 'biblio_search_api') {
    $publication_year = (!empty($vars['row']->_entity_properties['biblio_year'])) ? $vars['row']->_entity_properties['biblio_year'] : '';
    $updated_query_string_parameters = sources_theme_get_updated_query_string_parameters($vars);
    $publication_format = sources_theme_get_publication_type($vars);
    $source_title_info = sources_theme_get_source_title_info($publication_year, $publication_format);
    $custom_title_link_wrapper_prefix = '<div class="source-icon-' . $publication_format . ' title-link-container"><span class="icon shanticon-essays"></span><span class="fa fa-plus"></span>';
    $custom_title_link_wrapper_suffix = '</div>';
    //Display custom views field output based on current views display.
    switch ($vars['view']->current_display) {
      case 'page':
        $link = l($vars['row']->_entity_properties['title'] . $source_title_info, 'sources-search/biblio', array('query' => $updated_query_string_parameters, 'html' => TRUE));
        $vars['fields']['title']->content = $custom_title_link_wrapper_prefix . $link . $custom_title_link_wrapper_suffix;
        break;
      case 'allpage':
      
        $link = l($vars['row']->_entity_properties['title'] . $source_title_info, 'sources-search/biblio/' . $updated_query_string_parameters['current_nid'], array('html' => TRUE));
        $vars['fields']['title'] = (object) array(
            "label_html" => '',
            "wrapper_prefix" => $custom_title_link_wrapper_prefix,
            "content" => $link,
            "wrapper_suffix" => $custom_title_link_wrapper_suffix, 
        );
        break;
      case 'biblio_full':
        $vars['fields']['biblio_publication_type_1']->content = '<span>' . $publication_format . '</span>';
        break;
    }
  }
}

/**
 * Returns query string parameters with updated node id and page number.
 *
 */
function sources_theme_get_updated_query_string_parameters($vars) {
  $current_page = (!empty($_GET['page'])) ? intval($_GET['page']) : 0;
  $new_page = $vars['view']->row_index + (25 * $current_page);
  $current_query_strings = $_SERVER['QUERY_STRING'];
  parse_str($current_query_strings, $parsed_current_query_strings);
  $parameters = array_replace($parsed_current_query_strings, array('page' => $new_page));
  $parameters['current_nid'] = $vars['row']->entity;
  if ($vars['view']->current_display == "allpage") {
      unset($parameters['page']);  // Page parameter is for search results
      $paranew = array(
        'field_zotero_collections' => 'All',
        'current_nid' => $parameters['current_nid'],
      );
      $parameters = $paranew;        
  }
  return $parameters;
}

function sources_theme_get_publication_type($vars) {
  $publication_type_name = '';
  if (!empty($vars['row']->_entity_properties['biblio_publication_type'])) {
    $publication_type_id = $vars['row']->_entity_properties['biblio_publication_type'];
    $publication_type_name = db_query('SELECT name FROM {biblio_types} WHERE tid = :tid', array(':tid' => $publication_type_id))->fetchField();
  }
  return $publication_type_name;
}

/**
 * Returns source title with publication year and format.
 *
 */
function sources_theme_get_source_title_info($year, $publication_format) {
  if (!empty($publication_format)) {
    $publication_format = '<span class="publication-type">' . $publication_format . '</span>';
  }
  $publication_info = array($year, $publication_format);
  $source_title_info = '';
  $info_index = 1;
  foreach ($publication_info as $info) {
    if (!empty($info)) {
      if ($info_index > 1) {
        $source_title_info .= ', ' . $info;
      }
      else {
        $source_title_info .= $info;
      }
      $info_index++;
    }
  }
  if (!empty($source_title_info)) {
      $source_title_info = ' (' . $source_title_info . ')';
  }
  return $source_title_info;
}
