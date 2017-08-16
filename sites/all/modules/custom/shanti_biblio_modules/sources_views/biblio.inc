<?php
/**
 * Returns auto complete search values for content titles with info
 */
function biblio_title_list_callback($string = '') {
  $string = ucfirst($string);
  $matches = array();

  if (!empty($string) && strlen($string) >= 3) {
    // Retrieve query result of each type of information
    $titles = biblio_list_query('custom-titles', $string);
    $authors = biblio_list_query('authors', $string);
    $publishers = biblio_list_query('publishers', $string);
    $places_published = biblio_list_query('placed_published', $string);
    $zotero_vocabulary = taxonomy_vocabulary_machine_name_load('zotero_tags');
    $vid = $zotero_vocabulary->vid;
    $tags = biblio_list_query('tags', $string, $vid);
    // Retrieve list of information for option list
    $title_option_list = biblio_title_list_option($titles);
    $author_option_list = biblio_author_list_option($authors);
    $publisher_option_list = biblio_publisher_list_option($publishers);
    $placed_published_option_list = biblio_placed_published_list_option($places_published);
    $tag_option_list = biblio_tag_list_option($tags);
    // Merge and assign option list
    $options = $title_option_list + $author_option_list + $publisher_option_list + $placed_published_option_list + $tag_option_list;
    foreach ($options as $key => $value) {
      $matches[$key] = $value;
    }
  }
  drupal_json_output($matches);
}

/**
 * Returns list callback query.
 *
 * @param $query_type
 *   The type of query to return.
 *
 * @param $string
 *   The filter string to use on query.
 *
 * @param $vid
 *   Vocabulary id of taxonomy tag.
 *
 * @return query result.
 */
function biblio_list_query($query_type, $string, $vid = '') {
  switch ($query_type) {
    case 'custom-titles':
      $query = db_query_range('SELECT n.title, n.nid, dbi.biblio_year FROM {node} as n
        LEFT JOIN {search_api_db_database_node_index} as dbi ON
        n.nid = dbi.biblio_year WHERE n.title LIKE :value ORDER BY title ASC',
        0, 3, array(':value' => db_like($string) . '%'));
      break;
    case 'titles':
      $query = db_query_range('SELECT title, nid FROM {node} WHERE title LIKE :value
        ORDER BY title ASC', 0, 3, array(':value' => db_like($string) . '%'));
      break;
    case 'authors':
      $query = db_query_range('SELECT DISTINCT value FROM {search_api_db_database_node_index_biblio_authors}
        WHERE value LIKE :value ORDER BY value ASC', 0, 3, array(':value' => db_like($string) . '%'));
      break;
    case 'publishers':
      $query = db_query_range('SELECT DISTINCT dbi.biblio_publisher FROM {search_api_db_database_node_index} dbi
        WHERE dbi.biblio_publisher LIKE :value ORDER BY dbi.biblio_publisher ASC', 0, 3, array(':value' => db_like($string) . '%'));
      break;
    case 'placed_published':
      $query = db_query_range('SELECT DISTINCT dbi.biblio_place_published FROM {search_api_db_database_node_index} dbi
        WHERE dbi.biblio_place_published LIKE :value ORDER BY dbi.biblio_place_published ASC', 0, 3, array(':value' => db_like($string) . '%'));
      break;
    case 'tags':
      $query = db_query_range('SELECT name, tid FROM {taxonomy_term_data} WHERE name
        LIKE :name and vid = :vid_val ORDER BY name ASC', 0, 3, array(':name' => db_like($string) . '%', ':vid_val' => $vid));
      break;
  }
  return $query;
}

/**
 * Returns array list of biblio content titles.
 *
 */
function biblio_title_list_option($list) {
  $option_list = array();
  if ($list->rowCount() != 0) {
    $biblio_full_page = sources_views_get_views_results('biblio_search_api', 'biblio_full');
    foreach ($list as $title_key => $node) {
      $title_authors = biblio_title_authors($node->nid);
      $current_page = biblio_current_page($biblio_full_page, $node->nid);
      $publication_year = ($node->value != 0) ? '(' . $node->value . ')' : '';
      $option_header_text = ($title_key == 0) ? t('Titles') : '';
      $base_class_name = 'titles';
      $option_list['node=' . $node->nid . '=' . $current_page] = biblio_title_list_option_output($option_header_text, $base_class_name, $title_key, $node->title, $publication_year, $title_authors);
    }
  }
  return $option_list;
}

/**
 * Returns array list of biblio content authors.
 *
 */
function biblio_author_list_option($list) {
  $option_list = array();
  if ($list->rowCount() != 0) {
    foreach ($list as $author_key => $author) {
      $option_header_text = ($author_key == 0) ? t('Authors') : '';
      $base_class_name = 'authors';
      $option_list['author=' . $author->value] = biblio_title_list_option_output($option_header_text, $base_class_name, $author_key, $author->value);
    }
  }
  return $option_list;
}

/**
 * Returns array list of biblio content publishers.
 *
 */
function biblio_publisher_list_option($list) {
  $option_list = array();
  if ($list->rowCount() != 0) {
    foreach ($list as $publisher_key => $publisher) {
      $option_header_text = ($publisher_key == 0) ? t('Publishers') : '';
      $base_class_name = 'publishers';
      $option_list['publisher=' . $publisher->value] = biblio_title_list_option_output($option_header_text, $base_class_name, $publisher_key, $publisher->value);
    }
  }
  return $option_list;
}

/**
 * Returns array list of biblio content publish places.
 *
 */
function biblio_placed_published_list_option($list) {
  $option_list = array();
  if ($list->rowCount() != 0) {
    foreach ($list as $placed_published_key => $place) {
      $option_header_text = ($placed_published_key == 0) ? t('Place of Publication') : '';
      $base_class_name = 'place-published';
      $option_list['publishplace=' . $place->value] = biblio_title_list_option_output($option_header_text, $base_class_name, $placed_published_key, $place->value);
    }
  }
  return $option_list;
}

/**
 * Returns array list of biblio content tags.
 *
 */
function biblio_tag_list_option($list) {
  $option_list = array();
  if ($list->rowCount() != 0) {
    foreach ($list as $tag_key => $tag) {
      $option_header_text = ($tag_key == 0) ? t('Tags') : '';
      $base_class_name = 'tags';
      $option_list['tag=' . $tag->tid] = biblio_title_list_option_output($option_header_text, $base_class_name, $tag_key, $tag->name);
    }
  }
  return $option_list;
}

/**
 * Returns html format of custom option output.
 *
 * @param $option_header_text
 *   Option header text of individual option type.
 *
 * @param $base_class_name
 *   Base class name of individual option type.
 * 
 * @param $key
 *   Array key value.
 *
 * @param $value
 *   Option value.
 *
 * @param $year
 *   Publication year value.
 *
 * @param $author
 *   Author names.
 * 
 * @return custom display output for option list.
 */
function biblio_title_list_option_output($option_header_text, $base_class_name, $key, $value, $year = '', $author = '') {
  $option_values = array(
    'header_label' => $option_header_text,
    'base_class_name' => $base_class_name,
    'key' => $key,
    'value' => $value,
  );
  if (!empty($year)) {
    $option_values = $option_values + array('year' => $year);
  }
  if (!empty($author)) {
    $option_values = $option_values + array('author' => $author);
  }
  return theme('sources_custom_option_list', array('option' => $option_values));
}

/**
 * Returns author list based on node id.
 *
 * @param $nid
 *   Node id to be used on querying author list.
 *
 * @return list of author names.
 */
function biblio_title_authors($nid) {
  $title_authors = db_query_range('SELECT value FROM {search_api_db_database_node_index_biblio_authors}
    WHERE item_id = :nid', 0, 3, array(':nid' => $nid));
  $authors = '';
  foreach ($title_authors as $author_data_key => $author_data) {
    $authors .= ($author_data_key != 0) ? ', ' . $author_data->value : $author_data->value;
  }
  return $authors;
}

/**
 * Returns current page of sources result.
 *
 * @param $list
 *   List of biblio content.
 *
 * @param $nid
 *   Node id of individual sources content.
 *
 * @return Current page value.
 */
function biblio_current_page($list, $nid) {
  foreach ($list as $biblio_full_result_key => $value) {
    if ($value->entity == $nid) {
      $current_page = $biblio_full_result_key;
      return $current_page;
    }
  }
}

/**
 * Returns auto complete search values for plain titles
 */
function biblio_plain_title_list_callback($string = '') {
  $string = ucfirst($string);
  $matches = array();
  if (!empty($string) && strlen($string) >= 3) {
    $titles = biblio_list_query('titles', $string);
    if ($titles->rowCount() != 0) {
      $biblio_full_page = sources_views_get_views_results('biblio_search_api', 'biblio_full');
      foreach ($titles as $title_key => $node) {
        $current_page = biblio_current_page($biblio_full_page, $node->nid);
        $matches[$node->title] = '<span class="title-group">' . $node->title . '</span>';
      }
    }
  }
  drupal_json_output($matches);
}

/**
 * Returns auto complete search values for content authors
 */
function biblio_author_list_callback($string = '') {
  $string = ucfirst($string);
  $matches = array();
  if (!empty($string) && strlen($string) >= 3) {
    $authors = biblio_list_query('authors', $string);
    if ($authors->rowCount() != 0) {
      foreach ($authors as $author_key => $author) {
        $matches[$author->value] = '<span class="authors-group">' . $author->value . '</span>';
      }
    }
  }
  drupal_json_output($matches);
}

/**
 * Returns auto complete search values for content publishers
 */
function biblio_publisher_list_callback($string = '') {
  $string = ucfirst($string);
  $matches = array();
  if (!empty($string) && strlen($string) >= 3) {
    $publishers = biblio_list_query('publishers', $string);
    if ($publishers->rowCount() != 0) {
      foreach ($publishers as $publisher_key => $publisher) {
        $matches[$publisher->value] = '<span class="publishers-group">' . $publisher->value . '</span>';
      }
    }
  }
  drupal_json_output($matches);
}

/**
 * Returns auto complete search values for content published places
 */
function biblio_place_published_list_callback($string = '') {
  $string = ucfirst($string);
  $matches = array();
  if (!empty($string) && strlen($string) >= 3) {
    $placed_published = biblio_list_query('placed_published', $string);
    if ($placed_published->rowCount() != 0) {
      foreach ($placed_published as $placed_published_key => $place) {
        $matches[$place->value] = '<span class="place-published-group">' . $place->value . '</span>';
      }
    }
  }
  drupal_json_output($matches);
}

/**
 * Returns auto complete search values for content abstracts
 */
function biblio_tags_list_callback($string = '') {
  $string = ucfirst($string);
  $matches = array();
  if (!empty($string) && strlen($string) >= 3) {
    $zotero_vocabulary = taxonomy_vocabulary_machine_name_load('zotero_tags');
    $vid = $zotero_vocabulary->vid;
    $tags = biblio_list_query('tags', $string, $vid);
    if ($tags->rowCount() != 0) {
      foreach ($tags as $tag_key => $tag) {
        //$matches['tag=' . $tag->tid] = '<span class="tags-group">' . $tag->name . '</span>';
        $matches[$tag->name] = '<span class="tags-group">' . $tag->name . '</span>';
      }
    }
  }
  drupal_json_output($matches);
}

