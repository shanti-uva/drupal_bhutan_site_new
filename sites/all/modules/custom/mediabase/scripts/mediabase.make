; This file was auto-generated by drush make
core = 7.x

api = 2
projects[drupal][version] = "7.39"

; Modules
projects[admin_menu][subdir] = "contrib"
projects[admin_menu][version] = "3.0-rc4"

projects[apachesolr][subdir] = "contrib"
projects[apachesolr][version] = "1.x-dev"
projects[apachesolr][download][revision] = "0efe4ac8c1e4cf52783f0e1387e709f762ba0cbe"

projects[apachesolr_realtime][subdir] = "contrib"
projects[apachesolr_realtime][version] = "1.1"

projects[apachesolr_views][subdir] = "contrib"
projects[apachesolr_views][version] = "1.0-beta2"
projects[apachesolr_views][patches][] = "https://drupal.org/files/use_arguments-1750952-13.patch"

projects[service_links][subdir] = "contrib"
projects[service_links][version] = "2.2"

projects[better_exposed_filters][subdir] = "contrib"
projects[better_exposed_filters][version] = "3.0-beta3"

projects[ctools][subdir] = "contrib"
projects[ctools][version] = "1.2"

projects[context][subdir] = "contrib"
projects[context][version] = "3.0-beta6"

projects[facetapi][subdir] = "contrib"
projects[facetapi][version] = "1.2"

projects[date][subdir] = "contrib"
projects[date][version] = "2.6"

projects[db_maintenance][subdir] = "contrib"
projects[db_maintenance][version] = "1.1"

projects[devel][subdir] = "contrib"
projects[devel][version] = "1.3"

projects[diff][subdir] = "contrib"
projects[diff][version] = "3.2"

projects[entity][subdir] = "contrib"
projects[entity][version] = "1.3"

projects[entityreference][subdir] = "contrib"
projects[entityreference][version] = "1.0-rc3"

projects[expire][subdir] = "contrib"
projects[expire][version] = "2.0-rc3"

projects[facetapi_slider][subdir] = "contrib"

projects[features][subdir] = "contrib"
projects[features][version] = "2.2"

projects[field_collection] = "1.x-dev"
; on local I have updated to 7.x-1.0-beta8
projects[field_collection][subdir] = "contrib"
projects[field_collection][download][type] = "git" 
projects[field_collection][download][revision] = "4e0a52349a3f97b346622cda2e0e9ceb24787604"
projects[field_collection][patch][] = "https://raw.github.com/pinedrop/mediabase/master/patches/missing-bundle-patch-for-field-collection.patch"
projects[field_collection][patch][] = "https://drupal.org/files/issues/field_collection-non-object-field_collection_field_get_entity-1880312-9.patch"
projects[field_collection][patch][] = "https://www.drupal.org/files/field_collection-broken_beta5_upgrade-1877800-31.patch"

projects[field_group][version] = "1.x-dev"
projects[field_group][subdir] = "contrib"
projects[field_group][download][type] = "git" 
projects[field_group][download][revision] = "09f351080692305bd3447d61d0b18d021f0a72fb"

projects[kaltura][subdir] = "contrib"
projects[kaltura][version] = "2.0"
projects[kaltura][patches][] = "https://drupal.org/files/kaltura-undefined_variable-1547186.patch"

projects[filefield_sources][subdir] = "contrib"
projects[filefield_sources][version] = "1.7"

projects[fivestar][subdir] = "contrib"
projects[fivestar][version] = "2.0-alpha2"

projects[flag][subdir] = "contrib"
projects[flag][version] = "2.0"

projects[flexslider][subdir] = "contrib"
projects[flexslider][version] = "1.0-rc3"

projects[forward][subdir] = "contrib"
projects[forward][version] = "2.0"

projects[i18n][subdir] = "contrib"
projects[i18n][version] = "1.11"

projects[i18nviews][subdir] = "contrib"
projects[i18nviews][version] = "3.x-dev"

projects[job_scheduler][subdir] = "contrib"
projects[job_scheduler][version] = "2.0-alpha3"

projects[jquery_update][subdir] = "contrib"
projects[jquery_update][version] = "2.4"

projects[json2][subdir] = "contrib"
projects[json2][version] = "1.1"

projects[libraries][subdir] = "contrib"
projects[libraries][version] = "2.2"

projects[masquerade][subdir] = "contrib"
projects[masquerade][version] = "1.0-rc4"

projects[menu_target][subdir] = "contrib"
projects[menu_target][version] = "1.4"

projects[module_filter][subdir] = "contrib"
projects[module_filter][version] = "1.7"

projects[nice_menus][subdir] = "contrib"
projects[nice_menus][version] = "2.5"

projects[og][subdir] = "contrib"
projects[og][version] = "1.5"

projects[og_views][subdir] = "contrib"
projects[og_views][version] = "1.0"

projects[pagerer][subdir] = "contrib"
projects[pagerer][version] = "1.1"

projects[pathauto][subdir] = "contrib"
projects[pathauto][version] = "1.2"

projects[purl][subdir] = "contrib"
projects[purl][version] = "1.0-beta1"

projects[realname][subdir] = "contrib"
projects[realname][version] = "1.0"

projects[services][subdir] = "contrib"
projects[services][version] = "3.3"

projects[services_views][subdir] = "contrib"
projects[services_views][version] = "1.0-beta2"

projects[simplehtmldom][subdir] = "contrib"
projects[simplehtmldom][version] = "2.1"

projects[spaces][version] = "3.x-dev"
projects[spaces][subdir] = "contrib"
projects[spaces][download][type] = "git" 
projects[spaces][download][revision] = "eac3a7ed7cda08edf80d3946dfa55bcc9dec1ca7"
projects[spaces][patch][] = "https://raw.github.com/pinedrop/mediabase/master/patches/spaces_og_use_a_different_space_type_plugin.patch"

projects[strongarm][subdir] = "contrib"
projects[strongarm][version] = "2.0"

projects[token][subdir] = "contrib"
projects[token][version] = "1.4"

projects[transliteration][subdir] = "contrib"
projects[transliteration][version] = "3.2"

projects[variable][subdir] = "contrib"
projects[variable][version] = "2.5"

projects[views][subdir] = "contrib"
projects[views][version] = "3.8"

projects[views_bulk_operations][subdir] = "contrib"
projects[views_bulk_operations][version] = "3.0-beta3"

projects[views_data_export][subdir] = "contrib"
projects[views_data_export][version] = "3.0-beta6"
projects[views_data_export][patches][] = "http://drupal.org/files/views_data_export-Fix_Mysql_specific_code-1690438-4.patch"

projects[views_datasource][subdir] = "contrib"

projects[views_slideshow][subdir] = "contrib"
projects[views_slideshow][version] = "3.0"

projects[votingapi][subdir] = "contrib"
projects[votingapi][version] = "2.6"

; Custom Modules

projects[kmaps_modules][type] = "module"
projects[kmaps_modules][download][type] = "git"
projects[kmaps_modules][download][url] = "https://github.com/shanti-uva/drupal_kmaps_modules.git"
;projects[kmaps_modules][download][branch] = "release"
projects[kmaps_modules][subdir] = "contrib/shanti"

projects[kmaps_navigator][type] = "module"
projects[kmaps_navigator][download][type] = "git"
projects[kmaps_navigator][download][url] = "https://github.com/shanti-uva/drupal_kmaps_navigator.git"
projects[kmaps_navigator][download][branch] = "mediabase"
projects[kmaps_navigator][subdir] = "contrib/shanti"

projects[shanti_kmaps_admin][type] = "module"
projects[shanti_kmaps_admin][download][type] = "git"
projects[shanti_kmaps_admin][download][url] = "https://github.com/shanti-uva/drupal_shanti_kmaps_admin.git"
projects[shanti_kmaps_admin][download][branch] = "release"
projects[shanti_kmaps_admin][subdir] = "contrib/shanti"

projects[shanti_kmaps_fields][type] = "module"
projects[shanti_kmaps_fields][download][type] = "git"
projects[shanti_kmaps_fields][download][url] = "https://github.com/shanti-uva/drupal_shanti_kmaps_fields.git"
projects[shanti_kmaps_fields][download][branch] = "AV-MANU-19"
projects[shanti_kmaps_fields][subdir] = "contrib/shanti"

projects[kmaps_facets][type] = "module"
projects[kmaps_facets][download][type] = "git"
projects[kmaps_facets][download][url] = "https://github.com/shanti-uva/drupal_shanti_kmaps_facets.git"
projects[kmaps_facets][download][branch] = "release"
projects[kmaps_facets][subdir] = "contrib/shanti"

projects[mediabase][type] = "module"
projects[mediabase][download][type] = "git"
projects[mediabase][download][url] = "https://github.com/shanti-uva/drupal_mediabase.git"
;projects[mediabase][download][branch] = "release"
projects[transcripts_ui][subdir] = "mediabase"

projects[sarvaka_modules][type] = "module"
projects[sarvaka_modules][download][type] = "git"
projects[sarvaka_modules][download][url] = "https://github.com/shanti-uva/drupal_shanti_sarvaka_modules.git"
;projects[sarvaka_modules][download][branch] = "release"
projects[sarvaka_modules][subdir] = "contrib/shanti"

projects[transcripts_apachesolr][type] = "module"
projects[transcripts_apachesolr][download][type] = "git"
projects[transcripts_apachesolr][download][url] = "https://github.com/pinedrop/transcripts_apachesolr.git"
;projects[sarvaka_modules][download][branch] = "release"
projects[transcripts_apachesolr][subdir] = "transcripts"

projects[transcripts_ui][type] = "module"
projects[transcripts_ui][download][type] = "git"
projects[transcripts_ui][download][url] = "https://github.com/pinedrop/transcripts_ui.git"
;projects[sarvaka_modules][download][branch] = "release"
projects[transcripts_ui][subdir] = "transcripts"

; Custom Themes
projects[sarvaka_mediabase][type] = "theme"
projects[sarvaka_mediabase][download][type] = "git"
projects[sarvaka_mediabase][download][url] = "https://github.com/shanti-uva/drupal_shanti_sarvaka_mediabase.git"
;projects[sarvaka_mediabase][download][branch] = "release"

projects[shanti_sarvaka][type] = "theme"
projects[shanti_sarvaka][download][type] = "git"
projects[shanti_sarvaka][download][url] = "https://github.com/shanti-uva/drupal_shanti_sarvaka_theme.git"
;projects[shanti_sarvaka][download][branch] = "release"

; Old Mediabase Custom theme based on Omega
projects[mb-html5][download][type] = "git"
projects[mb-html5][download][url] = "git://github.com/shanti-uva/drupal_mediabase_theme_old.git"
projects[mb-html5][type] = "theme"

; Omega base theme for old mediabase theme
projects[omega][version] = "3.1"
projects[omega][type] = "theme"

; Libraries

; Ajax solr has custom code in it. Must put symlink in libraries folder to ../modules/contrib/shanti/kmaps_modules/libraries/ajaxsolr

; Fancytree has custom code in it. Must put symlink in libraries folder to ../modules/contrib/shanti/kmaps_modules/libraries/fancytree

libraries[flexslider][download][type] = "git"
libraries[flexslider][download][url] = "git://github.com/woothemes/FlexSlider.git"                         
libraries[flexslider][directory_name] = "flexslider"
libraries[flexslider][type] = "library"

libraries[jquery.cycle][download][type] = "file"
libraries[jquery.cycle][download][url] = "https://github.com/malsup/cycle/tarball/master"                         
libraries[jquery.cycle][directory_name] = "jquery.cycle"
libraries[jquery.cycle][type] = "library"

libraries[json2][dowload][type] = "git"
libraries[json2][dowload][url] = "https://github.com/douglascrockford/JSON-js.git"
libraries[json2][directory_name] = "json2"
libraries[json2][type] = "library"

libraries[saxon][download][type] = "file"
libraries[saxon][download][url] = "http://downloads.sourceforge.net/project/saxon/Saxon-HE/9.4/SaxonHE9-4-0-3J.zip"
libraries[saxon][directory_name] = "saxon"
libraries[saxon][type] = "library"

libraries[simplehtmldom][download][type] = "file"
libraries[simplehtmldom][download][url] = "http://sourceforge.net/p/simplehtmldom/code/HEAD/tree/tags/v_1_11/simple_html_dom.php"
libraries[simplehtmldom][directory_name] = "simplehtmldom"
libraries[simplehtmldom][type] = "library"

libraries[tinymce][download][type] = "file"
libraries[tinymce][download][url] = "http://download.moxiecode.com/tinymce/tinymce_3.5.8.zip"
libraries[tinymce][directory_name] = "tinymce"
libraries[tinymce][type] = "library"

