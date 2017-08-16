<?php
/**
 * @file
 * shanti_texts_features.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function shanti_texts_features_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "ds" && $api == "ds") {
    return array("version" => "1");
  }
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  if ($module == "file_entity" && $api == "file_default_displays") {
    return array("version" => "1");
  }
  if ($module == "page_manager" && $api == "pages_default") {
    return array("version" => "1");
  }
  if ($module == "quicktabs" && $api == "quicktabs") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function shanti_texts_features_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_image_default_styles().
 */
function shanti_texts_features_image_default_styles() {
  $styles = array();

  // Exported image style: front_carousel.
  $styles['front_carousel'] = array(
    'label' => 'Front Carousel',
    'effects' => array(
      4 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 280,
          'height' => 210,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_node_info().
 */
function shanti_texts_features_node_info() {
  $items = array(
    'book' => array(
      'name' => t('Book page'),
      'base' => 'node_content',
      'description' => t('<em>Books</em> have a built-in hierarchical navigation. Use for handbooks or tutorials.'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}

/**
 * Implements hook_default_search_api_autocomplete_search().
 */
function shanti_texts_features_default_search_api_autocomplete_search() {
  $items = array();
  $items['search_api_views_full_text_local'] = entity_import('search_api_autocomplete_search', '{
    "machine_name" : "search_api_views_full_text_local",
    "name" : "Full Text Local",
    "index_id" : "full_text_local",
    "suggester_id" : "server",
    "type" : "search_api_views",
    "enabled" : "1",
    "options" : {
      "suggester_configuration" : { "fields" : [] },
      "min_length" : "3",
      "results" : 0,
      "custom" : { "display" : "block_1" },
      "submit_button_selector" : ":submit",
      "autosubmit" : 1
    }
  }');
  $items['search_api_views_full_text_local_2'] = entity_import('search_api_autocomplete_search', '{
    "machine_name" : "search_api_views_full_text_local_2",
    "name" : "Full Text Local 2",
    "index_id" : "full_text_local_2",
    "suggester_id" : "server",
    "type" : "search_api_views",
    "enabled" : "1",
    "options" : {
      "min_length" : "3",
      "results" : 1,
      "custom" : { "display" : "block" },
      "submit_button_selector" : ":submit",
      "autosubmit" : 1,
      "suggester_configuration" : []
    }
  }');
  return $items;
}

/**
 * Implements hook_default_search_api_index().
 */
function shanti_texts_features_default_search_api_index() {
  $items = array();
  $items['full_text_local'] = entity_import('search_api_index', '{
    "name" : "Full Text Local",
    "machine_name" : "full_text_local",
    "description" : null,
    "server" : "local_database",
    "item_type" : "node",
    "options" : {
      "index_directly" : 1,
      "cron_limit" : "50",
      "fields" : {
        "field_book_author" : { "type" : "list\\u003Ctext\\u003E" },
        "field_book_content:value" : { "type" : "text" },
        "field_dc_date_orginial_year" : { "type" : "date" },
        "field_dc_date_publication_year" : { "type" : "date" },
        "search_api_language" : { "type" : "string" }
      },
      "data_alter_callbacks" : {
        "search_api_alter_bundle_filter" : {
          "status" : 1,
          "weight" : "-10",
          "settings" : { "default" : "0", "bundles" : { "book" : "book" } }
        },
        "search_api_alter_node_access" : { "status" : 0, "weight" : "0", "settings" : [] },
        "search_api_alter_node_status" : { "status" : 1, "weight" : "0", "settings" : [] },
        "search_api_alter_add_hierarchy" : { "status" : 0, "weight" : "0", "settings" : { "fields" : [] } },
        "search_api_alter_add_viewed_entity" : { "status" : 0, "weight" : "0", "settings" : { "mode" : "full" } },
        "search_api_alter_add_url" : { "status" : 0, "weight" : "0", "settings" : [] },
        "search_api_alter_add_aggregation" : { "status" : 0, "weight" : "0", "settings" : [] }
      },
      "processors" : {
        "search_api_case_ignore" : {
          "status" : 1,
          "weight" : "0",
          "settings" : { "fields" : { "field_book_content:value" : true } }
        },
        "search_api_html_filter" : {
          "status" : 1,
          "weight" : "10",
          "settings" : {
            "fields" : { "field_book_content:value" : true },
            "title" : 0,
            "alt" : 1,
            "tags" : "h1 = 5\\r\\nh2 = 3\\r\\nh3 = 2\\r\\nstrong = 2\\r\\nb = 2\\r\\nem = 1.5\\r\\nu = 1.5"
          }
        },
        "search_api_tokenizer" : {
          "status" : 1,
          "weight" : "20",
          "settings" : {
            "fields" : { "field_book_content:value" : true },
            "spaces" : "[^[:alnum:]]",
            "ignorable" : "[\\u0027]"
          }
        },
        "search_api_stopwords" : {
          "status" : 1,
          "weight" : "30",
          "settings" : {
            "fields" : { "field_book_content:value" : true },
            "file" : "",
            "stopwords" : "but\\r\\ndid\\r\\nthe this that those\\r\\netc"
          }
        },
        "search_api_highlighting" : {
          "status" : 1,
          "weight" : "35",
          "settings" : {
            "prefix" : "\\u003Cstrong class=\\u0022full-text-search-hit\\u0022\\u003E",
            "suffix" : "\\u003C\\/strong\\u003E",
            "excerpt" : 1,
            "excerpt_length" : "256",
            "exclude_fields" : { "field_book_author" : "field_book_author" },
            "highlight" : "always"
          }
        }
      }
    },
    "enabled" : "1",
    "read_only" : "0"
  }');
  $items['full_text_local_2'] = entity_import('search_api_index', '{
    "name" : "Full Text Local 2",
    "machine_name" : "full_text_local_2",
    "description" : "A full text index of SHANTI Texts.",
    "server" : "local_database",
    "item_type" : "node",
    "options" : {
      "datasource" : { "bundles" : [ "book" ] },
      "index_directly" : 1,
      "cron_limit" : "50",
      "fields" : {
        "author" : { "type" : "integer", "entity_type" : "user" },
        "field_book_content:value" : { "type" : "text" },
        "search_api_access_node" : { "type" : "list\\u003Cstring\\u003E" },
        "search_api_language" : { "type" : "string" },
        "search_api_url" : { "type" : "uri" },
        "status" : { "type" : "boolean" },
        "title" : { "type" : "text" }
      },
      "data_alter_callbacks" : {
        "search_api_alter_bundle_filter" : {
          "status" : 0,
          "weight" : "-10",
          "settings" : { "default" : "1", "bundles" : [] }
        },
        "search_api_alter_node_access" : { "status" : 1, "weight" : "0", "settings" : [] },
        "search_api_alter_node_status" : { "status" : 1, "weight" : "0", "settings" : [] },
        "search_api_alter_add_hierarchy" : { "status" : 0, "weight" : "0", "settings" : { "fields" : [] } },
        "search_api_alter_add_viewed_entity" : { "status" : 0, "weight" : "0", "settings" : { "mode" : "full" } },
        "search_api_alter_add_url" : { "status" : 1, "weight" : "0", "settings" : [] },
        "search_api_alter_add_aggregation" : { "status" : 0, "weight" : "0", "settings" : [] }
      },
      "processors" : {
        "search_api_html_filter" : {
          "status" : 1,
          "weight" : "-50",
          "settings" : {
            "fields" : { "title" : true, "field_book_content:value" : true },
            "title" : 1,
            "alt" : 1,
            "tags" : "h1 = 5\\r\\nh2 = 3\\r\\nh3 = 2\\r\\nstrong = 2\\r\\nb = 2\\r\\nem = 1.5\\r\\nu = 1.5"
          }
        },
        "search_api_case_ignore" : {
          "status" : 1,
          "weight" : "-49",
          "settings" : { "fields" : { "title" : true, "field_book_content:value" : true } }
        },
        "search_api_tokenizer" : {
          "status" : 0,
          "weight" : "-48",
          "settings" : {
            "fields" : { "title" : true, "field_book_content:value" : true },
            "spaces" : "[^[:alnum:]]",
            "ignorable" : "[\\u0027]"
          }
        },
        "search_api_stopwords" : {
          "status" : 0,
          "weight" : "-47",
          "settings" : {
            "fields" : { "title" : true, "field_book_content:value" : true },
            "file" : "",
            "stopwords" : "but\\r\\ndid\\r\\nthe this that those\\r\\netc"
          }
        },
        "search_api_highlighting" : {
          "status" : 1,
          "weight" : "-46",
          "settings" : {
            "prefix" : "\\u003Cstrong class=\\u0022full-text-search-hit\\u0022\\u003E",
            "suffix" : "\\u003C\\/strong\\u003E",
            "excerpt" : 1,
            "excerpt_length" : "256",
            "exclude_fields" : [],
            "highlight" : "always"
          }
        }
      }
    },
    "enabled" : "1",
    "read_only" : "0"
  }');
  return $items;
}

/**
 * Implements hook_default_search_api_server().
 */
function shanti_texts_features_default_search_api_server() {
  $items = array();
  $items['local_database'] = entity_import('search_api_server', '{
    "name" : "Local Database",
    "machine_name" : "local_database",
    "description" : "",
    "class" : "search_api_db_service",
    "options" : {
      "database" : "default:default",
      "min_chars" : "3",
      "partial_matches" : 0,
      "autocomplete" : { "suggest_suffix" : 1, "suggest_words" : 1 },
      "indexes" : {
        "full_text_local" : {
          "search_api_language" : {
            "table" : "search_api_db_full_text_local",
            "column" : "search_api_language",
            "type" : "string",
            "boost" : "1.0"
          },
          "field_book_content:value" : {
            "table" : "search_api_db_full_text_local_text",
            "type" : "text",
            "boost" : "1.0"
          },
          "field_book_author" : {
            "table" : "search_api_db_full_text_local_text",
            "type" : "list\\u003Ctext\\u003E",
            "boost" : "1.0"
          },
          "field_dc_date_orginial_year" : {
            "table" : "search_api_db_full_text_local",
            "column" : "field_dc_date_orginial_year",
            "type" : "date",
            "boost" : "1.0"
          },
          "field_dc_date_publication_year" : {
            "table" : "search_api_db_full_text_local",
            "column" : "field_dc_date_publication_year",
            "type" : "date",
            "boost" : "1.0"
          }
        },
        "full_text_local_2" : {
          "title" : {
            "table" : "search_api_db_full_text_local_2_text",
            "type" : "text",
            "boost" : "1.0"
          },
          "search_api_language" : {
            "table" : "search_api_db_full_text_local_2",
            "column" : "search_api_language",
            "type" : "string",
            "boost" : "1.0"
          },
          "field_book_content:value" : {
            "table" : "search_api_db_full_text_local_2_text",
            "type" : "text",
            "boost" : "1.0"
          },
          "status" : {
            "table" : "search_api_db_full_text_local_2",
            "column" : "status",
            "type" : "boolean",
            "boost" : "1.0"
          },
          "author" : {
            "table" : "search_api_db_full_text_local_2",
            "column" : "author",
            "type" : "integer",
            "boost" : "1.0"
          },
          "search_api_url" : {
            "table" : "search_api_db_full_text_local_2",
            "column" : "search_api_url",
            "type" : "uri",
            "boost" : "1.0"
          },
          "search_api_access_node" : {
            "table" : "search_api_db_full_text_local_2_search_api_access_node",
            "column" : "value",
            "type" : "list\\u003Cstring\\u003E",
            "boost" : "1.0"
          }
        }
      }
    },
    "enabled" : "1"
  }');
  return $items;
}
