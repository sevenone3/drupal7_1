<?php

/**
 * @file
 * Defines a default mobile context
 */

/**
 * Implementats hook_context_default_contexts().
 */
function mobile_tools_httpheaders_context_default_contexts() {
  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'mobile_tools';
  $context->description = 'Default context to include header data for Mobile Tools.';
  $context->tag = 'mobile tools';
  $context->conditions = array(
    'sitewide' => array(
      'values' => array(
        1 => 1,
      ),
    ),
  );
  $context->reactions = array(
    'http_header' => array(
      'http_header' => array(
        'extra_headers' => '',
        'mobile_tools_headers' => 1,
        'mobile_tools_device_group_headers' => 1,
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('development');

  $export = array();
  $export[$context->name] = $context;
  
  return $export;
}
