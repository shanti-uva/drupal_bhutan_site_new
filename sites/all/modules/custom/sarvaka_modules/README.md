drupal_shanti_sarvaka_modules
=============================
This is a suite of modules for integrating with the Drupal Shanti Sarvaka theme. It contains

1. explore_menu: a module that creates a block for placing in the top bar that creates the link and drop down explore menu.
2. features: Drupal Features created for Shanti Mandala Sites
    2a: Shanti Admin Features: provides enhanced views for shanti/admin/content and shanti/admin/people with VBO capabilities
    2b: Shanti Pager: a feature the defines the core_pager using the Pagerer module
3. jira_collector: provides a block with a button for submitting a JIRA for bugs.
4. shanti_carousel: Defines a general carousel block to use in any Shanti mandala site
5. user_prefs: a module for adding the user preference form to the multi-level menu in the top bar. It saves the settings on a user by user basis and makes them available in the Drupal.settings JS object. (Note: Need to add PHP api functions to access the settings via PHP.)

Combined into a single repo so that they can be added en-masse to sites using the Shanti Sarvaka Theme 