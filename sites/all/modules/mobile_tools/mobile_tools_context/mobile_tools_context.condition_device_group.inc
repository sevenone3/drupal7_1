<?php
/**
 * @file
 * Integration with the Context module
 *
 * Triggers a context defined device groups
 */
class mobile_tools_context_condition_device_group extends context_condition {

  /**
   * Default values
   */
  function condition_values() {
    $device_groups = mobile_tools_device_group_load_all();

    foreach ($device_groups as $device_group) {
      $values = $device_group->dgid;
    }

    return $values;
  }

  /**
   * Process condition logic
   */
  function execute($device_group) {
    if ($this->condition_used()) {
      foreach ($this->get_contexts($device_group->dgid) as $context) {
        $this->condition_met($context, $device_group->dgid);
      }
    }
  }

  /**
   * Configuration form
   */
  function condition_form($context) {
    $form = parent::condition_form($context);

    // Build an options array based on the list of defined device groups
    $options = array();
    $device_groups = mobile_tools_device_group_load_all();

    foreach ($device_groups as $key => $device_group) {
      $options[$device_group->dgid] = $device_group->title;
    }

    $form['#type'] = 'checkboxes';
    $form['#description'] = t('Trigger this context when the given device groups are active.');
    $form['#options'] = $options;
    $form['#default_value'] = $this->fetch_from_context($context, 'values');

    return $form;
  }

  /**
   * Form submit handler
   */
  function condition_form_submit($values) {
    return $values;
  }
}
