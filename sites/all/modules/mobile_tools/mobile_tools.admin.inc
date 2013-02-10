<?php
/**
 * @file
 *   Adminstrative pages for Mobile Tools
 */

/**
 * Lists the existing device groups
 */
function mobile_tools_device_groups_list() {
  $rows = array();
  $header = array(
    t('Name'),
    t('Machine Name'),
    t('Modifier'),
    t('Theme'),
    t('Description'),
    t('Weight'),
    array('data' => t('Operations'), 'colspan' => 2
    )
  );
  $device_groups = mobile_tools_device_group_load_all();

  foreach ($device_groups as $device_group) {
    $is_overridden = ($device_group->export_type & EXPORT_IN_CODE) ? TRUE : FALSE;
    if ($device_group->theme == MOBILE_TOOLS_FLAG_DEFAULT_SITE_THEME) {
      $device_group->theme = t('Default site theme');
    }
    $rows[] = array(
      $device_group->title,
      $device_group->dgid,
      $device_group->purl_modifier,
      $device_group->theme,
      $device_group->description,
      $device_group->weight,
      // @todo Fix this. Raw user input with potential for XSS attack
      l(t('edit'), "admin/config/system/mobile-tools/edit/" . $device_group->dgid),
      (!isset($optionset->in_code_only)) ? l(t($is_overridden ? 'revert' : 'delete'), "admin/config/system/mobile-tools/delete/" . $device_group->dgid) : '',
    );
  }

  $build['device_groups_table'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $rows,
    '#empty' => t('No device groups available. <a href="@link">Add device group</a>.', array('@link' => url('admin/config/system/mobile-tools/add'))),
  );

  return $build;
}

/**
 * Admin form for global Mobile Tools settings
 */
function mobile_tools_admin_settings_form($form, &$form_state) {

  $form['mobile_tools_enable_view_modes'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable entity build modes'),
    '#description' => t('Create an entity build mode for each device group.'),
    '#default_value' => variable_get('mobile_tools_enable_view_modes', FALSE),
    '#access' => user_access('administer mobile tools'),
  );

  // Load the list of detector modules
  $detectors = _mobile_tools_get_device_detectors();

  $form['mobile_tools_enable_redirection'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable device redirection'),
    '#description' => empty($detectors) ? t('To enable device redirection you must enable a device detector module (ex: Mobile Tools Browscap).') : t('Allow users to be redirected to the appropriate device group URL.'),
    '#default_value' => variable_get('mobile_tools_enable_redirection', FALSE),
    '#disabled' => empty($detectors),
    '#access' => user_access('administer mobile tools'),
  );

  $form['redirection'] = array(
    '#type' => 'fieldset',
    '#title' => t('Redirection'),
    '#description' => t('Configure redirection settings.'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#access' => user_access('administer mobile tools'),
    '#states' => array(
      'visible' => array(
        ':input[name=mobile_tools_enable_redirection]' => array('checked' => TRUE),
      ),
    ),
    '#access' => user_access('administer mobile tools'),
  );

  $form['redirection']['mobile_tools_redirection_type'] = array(
    '#type' => 'radios',
    '#title' => t('Redirection Type'),
    '#description' => t('Redirect the user using a server-side redirect or using client-side javascript.'),
    '#options' => array(
      MOBILE_TOOLS_REDIRECT_SERVER_SIDE => t('Server Side'),
      MOBILE_TOOLS_REDIRECT_CLIENT_SIDE => t('Client Side'),
    ),
    '#default_value' => variable_get('mobile_tools_redirection_type', MOBILE_TOOLS_REDIRECT_SERVER_SIDE),
    '#access' => user_access('administer mobile tools'),
  );
  
  $form['redirection']['mobile_tools_redirection_auto'] = array(
    '#type' => 'checkbox',
    '#title' => t('Automatic redirection'),
    '#description' => t('Enable automatic redirection. This settings is enabled automatically for server-side redirection.'),
    '#default_value' => variable_get('mobile_tools_redirection_auto', TRUE),
    '#states' => array(
      'enabled' => array(
        ':input[name=mobile_tools_redirection_type]' => array('value' => MOBILE_TOOLS_REDIRECT_CLIENT_SIDE),
      ),
    ),
    '#access' => user_access('administer mobile tools'),
  );

  $form['mobile_tools_global_httpheaders'] = array(
    '#type' => 'textarea',
    '#title' => t('HTTP Headers'),
    '#description' => t('A set of global HTTP headers to include when a device group is active.'),
    '#cols' => 60,
    '#rows' => 5,
    '#default_value' => variable_get('mobile_tools_global_httpheaders', FALSE),
    '#access' => user_access('administer mobile tools'),
  );

  return system_settings_form($form);
}

/**
 * Form to add a new device group
 */
function mobile_tools_device_group_form($form, &$form_state, $device_group = NULL) {

  // If a device group is specified, load it
  if (is_object($device_group)) {
    // Do nothing
  }
  else {
    $device_group = _mobile_tools_empty_device_group();
  }

  // Load the list of theme options
  $themes = list_themes();
  $theme_options = array(MOBILE_TOOLS_FLAG_DEFAULT_SITE_THEME => t('Default site theme'));

  foreach ($themes as $theme) {
    // Ensure the theme is enabled
    if ($theme->status == TRUE) {
      $theme_options[$theme->name] = $theme->name;
    }
  }

  $form['title'] = array(
    '#title' => t('Device Group Name'),
    '#type' => 'textfield',
    '#required' => TRUE,
    '#description' => t('Human-readable name for this device group'),
    '#default_value' => isset($form_state['values']['title']) ? $form_state['values']['title'] : $device_group->title,
  );

  $form['dgid'] = array(
    '#title' => t('Machine Name'),
    '#type' => 'machine_name',
    '#required' => TRUE,
    '#maxlength' => 32,
    '#description' => t('Machine-readable name for this device group'),
    '#disabled' => empty($device_group->dgid) ? FALSE : TRUE,
    '#machine_name' => array(
      'exists' => 'mobile_tools_device_group_exists',
      'source' => array('title'),
    ),
    '#default_value' => isset($form_state['values']['dgid']) ? $form_state['values']['dgid'] : $device_group->dgid,
  );

  $form['purl_modifier'] = array(
    '#type' => 'textfield',
    '#title' => t('URL Modifier'),
    '#description' => t('Enter the modifer to be used in your URL (ex: mobile)'),
    '#required' => TRUE,
    '#default_value' => isset($form_state['values']['purl_modifier']) ? $form_state['values']['purl_modifier'] : $device_group->purl_modifier,
  );

  // Check if themekey is available
  if (module_exists('themekey')) {
    $form['theme'] = array(
      '#type' => 'select',
      '#title' => t('Theme'),
      '#description' => t('Select a theme to activate for this device group'),
      '#options' => $theme_options,
      '#default_value' => isset($form_state['values']['title']) ? $form_state['values']['theme'] : $device_group->theme,
    );
  }
  else {
    $form['theme_notice'] = array(
      '#title' => t('Theme'),
      '#markup' => '<p>' . t('To enable theme switching you must first enable the !themekey module.', array('!themekey' => l(t('ThemeKey'), 'http://drupal.org/project/themekey'))) . '</p>',
    );
  }

  $form['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#description' => t('Enter the device group description here'),
    '#default_value' => isset($form_state['values']['description']) ? $form_state['values']['description'] : $device_group->description,
  );

  $options = array();
  for ($x = -50; $x < 51; $x++) {
    $options[$x] = $x;
  }

  $detectors = _mobile_tools_get_device_detectors();

  if (!empty($detectors)) {
    $detection_options['none'] = t('Disabled');

    // Set the detection options
    foreach($detectors as $module => $detector) {
      $detection_options[$module] = $detector['name'];
    }

    // Set the default value for detection
    // Check for a previous value in the form_state
    if (isset($form_state['values']['detector'])) {
      $detection_default = $form_state['values']['detector'];
    }
    // Next, check the device group
    elseif (isset($device_group->detector)) {
      $detection_default = $device_group->detector;
    }
    // Finally, default to 'none'
    else {
      $detection_default = 'none';
    }


    $form['detector'] = array(
      '#type' => 'select',
      '#title' => t('Device detection'),
      '#description' => t('Enable device detection'),
      '#options' => $detection_options,
      '#default_value' => $detection_default,
    );

    foreach ($detectors as $detector => $detector_form) {
      if (isset($detector_form['form callback'])) {
        $form[$detector]['#type'] = 'fieldset';
        $form[$detector]['#title'] = $detectors[$detector]['name'];
        $form[$detector]['#states'] = array(
            'visible' => array(
              ':input[value="' . $detector . '"]' => array('checked' => TRUE),
            ),
        );

        $detector_form_settings = array();
        if (isset($device_group->detection_settings[$detector])) {
          $detector_form_settings = $device_group->detection_settings[$detector];
        }
        $form_addition = call_user_func($detector_form['form callback'], $detector_form_settings);
        // Remember the additional field names so we can use them later.
        $form[$detector]['#fields'] = array_keys($form_addition);
        $form[$detector]['#activation callback'] = isset($detector_form['activation callback']) ? $detector_form['activation callback'] : NULL;
        $form[$detector] += $form_addition;
      }
    }

  }
  else {
    $form['detection_notice'] = array(
      '#title' => t('Device detection'),
      '#markup' => '<p>' . t('To enable device dection you must first enable a module that supports Mobile Tools device detection, such as !browscap and Mobile Tools Browscap modules.', array('!browscap' => l(t('Browscap'), 'http://drupal.org/project/browscap'))) . '</p>',
    );
  }

  $form['weight'] = array(
    '#type' => 'select',
    '#title' => t('Weight'),
    '#description' => t('Adjust the weight of the device group. Lower weights give higher priority to the device group.'),
    '#options' => $options,
    '#default_value' => isset($form_state['values']['weight']) ? $form_state['values']['weight'] : $device_group->weight,
  );

  // If a device group was loaded and it has an httpheaders field, parse it
  // into a string for the form's textarea.
  $headers = "";
  if (is_object($device_group) &&
      isset($device_group->httpheaders) &&
      !empty($device_group->httpheaders)
  ) {
    foreach ($device_group->httpheaders as $header => $value) {
      $headers .= $header . ":" . $value . "\n";
    }
  }

  $form['httpheaders'] = array(
    '#type' => 'textarea',
    '#title' => t('HTTP Headers'),
    '#description' => t('A set of HTTP headers to include when this device group is active.'),
    '#cols' => 60,
    '#rows' => 5,
    '#default_value' => isset($form_state['values']['httpheaders']) ? $form_state['values']['httpheaders'] : $headers,
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save device group'),
  );

  $form['actions']['cancel'] = array(
    '#markup' => l(t('Cancel'), 'admin/config/system/mobile-tools'),
  );

  return $form;
}

/**
 * Validation handler for mobile_tools_device_group_from
 */
function mobile_tools_device_group_form_validate($form, &$form_state) {
  $values = $form_state['values'];

  // Check the weight value
  if (!is_numeric($values['weight']) || intval($values['weight']) < -50 || intval($values['weight']) > 50) {
    form_set_error('weight', t('Weight must be an integer value between -50 and 50'));
  }
}

/**
 * Handles form submission for a new device group
 */
function mobile_tools_device_group_form_submit($form, &$form_state) {
  // Try to load an existing device group.
  if (!$device_group = ctools_export_crud_load('mobile_tools_device_group',
      $form_state['values']['dgid'])
  ) {
    // If none was found, create a new one.
    $device_group = ctools_export_crud_new('mobile_tools_device_group');
  }

  // Reset these every time.
  $device_group->detection_settings = array();
  $device_group->dgid = $form_state['values']['dgid'];
  $device_group->title = $form_state['values']['title'];
  $device_group->purl_modifier = $form_state['values']['purl_modifier'];
  $device_group->description = $form_state['values']['description'];
  $device_group->weight = intval($form_state['values']['weight']);

  // Only save a value if a detection method was selected
  if ($form_state['values']['detector'] != 'none') {
    $device_group->detector = $form_state['values']['detector'];
    // Grab the detection methods' settings fields and add them to our serialized
    // field for db storage.
    $device_group->detection_settings[$device_group->detector]['activation callback'] = $form[$device_group->detector]['#activation callback'];
    if (isset($form[$device_group->detector]['#fields']) ) {
      foreach ($form[$device_group->detector]['#fields'] as $field) {
        $device_group->detection_settings[$device_group->detector][$field] = $form_state['values'][$field];
      }
    }
  }
  else {
    // Clear out the detection settings
    $device_group->detector = '';
    $device_group->detection_settings = '';
  }


  // Parse the httpheaders field into an array to be stored
  $headers = array();
  foreach (explode("\r\n", $form_state['values']['httpheaders']) as $header) {
    if (!empty($header)) {
      // Split the line only by non-escaped colon
      $split_header = preg_split('/(?<!\\\):/', $header);
      $headers[$split_header[0]] = $split_header[1];
    }
  }
  $device_group->httpheaders = $headers;

  // Set the default value for the theme if ThemeKey isn't present
  $theme = MOBILE_TOOLS_FLAG_DEFAULT_SITE_THEME;

  if (!empty($form_state['values']['theme']) && $form_state['values']['theme'] != MOBILE_TOOLS_FLAG_DEFAULT_SITE_THEME) {
    $theme = $form_state['values']['theme'];
  }

  $device_group->theme = $theme;

  mobile_tools_device_group_save($device_group);

  drupal_set_message(t("Device group %title has been saved.",
    array('%title' => check_plain($form_state['values']['title']))));

  $form_state['redirect'] = 'admin/config/system/mobile-tools';
}

/**
 * Shows the confirmation form for the deletion of device groups
 *
 * @param $form
 * @param &$form_state
 * @param $name
 */
function mobile_tools_device_group_delete_confirm($form, &$form_state, $device_group) {
  $form['dgid'] = array('#type' => 'value', '#value' => $device_group->dgid);

  // Deleting an export in code will revert it.
  $op = ($device_group->export_type & EXPORT_IN_CODE) ? 'Revert' : 'Delete';

  $message = t("Are you sure you want to @action the device group %title?",
    array('@action' => t(drupal_strtolower($op)), '%title' => $device_group->title));
  $caption = '<p>' . t('This action cannot be undone.') . '</p>';

  return confirm_form($form,
                      $message,
                      'admin/config/system/mobile-tools',
                      $caption,
                      t($op));
}

/**
 * Handles form submission for the deletion of device groups
 *
 * @param $form
 * @param &$form_state
 */
function mobile_tools_device_group_delete_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm'] &&
      isset($form_state['values']['dgid']) &&
      !empty($form_state['values']['dgid']) &&
      $device_group = ctools_export_crud_load('mobile_tools_device_group', $form_state['values']['dgid'])
  ) {
    ctools_export_crud_delete('mobile_tools_device_group', $device_group);

    // Deleting an export in code will revert it.
    $op = ($device_group->export_type & EXPORT_IN_CODE) ? 'Reverted' : 'Deleted';
    $message = t("Device group %dgid has been @action.",
    array('%dgid' => check_plain($form_state['values']['dgid']),
          '@action' => t(drupal_strtolower($op))));
    drupal_set_message($message);
  }
  else {
    drupal_set_message(t("There was an error deleting device group %dgid.",
      array('%dgid' => check_plain($form_state['values']['dgid']))));
  }
  $form_state['redirect'] = 'admin/config/system/mobile-tools';
}