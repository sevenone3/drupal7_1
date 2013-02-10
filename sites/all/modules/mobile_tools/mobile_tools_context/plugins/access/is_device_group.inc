<?php

/**
 * @file
 * Plugin to provide access control based on active device group.
 */

/**
 * Implements hook_ctools_access().
 */
$plugin = array(
  'title' => t("Device Group"),
  'description' => t('Control access based on the active device group.'),
  'callback' => 'mobile_tools_context_device_group_access_check',
  'default' => array(
    'description' => '',
    'browser' => '',
  ),
  'settings form' => 'mobile_tools_context_device_group_access_check_settings',
  'summary' => 'mobile_tools_context_device_group_access_check_summary',
  'all contexts' => TRUE,
);

/**
 * Settings form for the 'by perm' access plugin
 *
 * @todo Need a way to provide a list of all available contexts to be used by
 *       the eval-ed PHP.
 */
function mobile_tools_context_device_group_access_check_settings($form, &$form_state, $conf) {
  $perms = array();

  // Build an options array based on the list of defined device groups
  $options = array();
  $device_groups = mobile_tools_device_group_load_all();

  foreach ($device_groups as $key => $device_group) {
    $options[$device_group->dgid] = $device_group->title;
  }

  $form['settings']['description'] = array(
    '#type' => 'textfield',
    '#title' => t('Administrative description'),
    '#default_value' => $conf['description'],
    '#description' => t('A description for this test for administrative purposes.'),
  );
  $form['settings']['device_group'] = array(
    '#type' => 'select',
    '#title' => t('Device Group'),
    '#default_value' => isset($conf['device_group']) ? $conf['device_group']: '',
    '#options' => $options,
    '#multiple' => TRUE,
    '#description' => t('Access will be granted if the client matches the desired group(s).'),
  );

  return $form;
}

/**
 * Check for access.
 */
function mobile_tools_context_device_group_access_check($conf, $contexts) {
  $device_group = mobile_tools_get_active_device_group();

  // Check if the active device group is in the list of defined groups
  if (FALSE!== $device_group && isset($conf['device_group'][$device_group->dgid]) && $device_group->dgid == $conf['device_group'][$device_group->dgid]) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Provide a summary description based upon the checked roles.
 */
function mobile_tools_context_device_group_access_check_summary($conf, $contexts) {
  return check_plain($conf['description']);
}