<?php

/**
 * @file
 * Spaces plugin implementation for Mobile Tools device groups
 *
 * @author Mathew Winstone <mwinstone@coldfrontlabs.ca>
 */

/**
 * Mobile Tools integration for Spaces.
 */
class space_mobile_tools extends space_type_purl {
  var $device = NULL;

  /**
   * Constructor
   */
  function __construct($type, $id = NULL) {
    parent::__construct($type, $id);
  }

  /**
   * Implementation of space->title().
   */
  function title() {
    return 'Mobile Tools Space';
  }

  /**
   * Override of activate().
   */
  function activate() {
    if (parent::activate()) {
      // Add configured options

      // @todo enable configured space settings

      return TRUE;
    }
    return FALSE;
  }

  /**
   * Override of load().
   */
  function load() {
    return TRUE;
  }

  /**
   * Implementation of space->router().
   */
  function router($op, $object = NULL, $is_active = TRUE) {
    switch ($op) {
      case 'init':
        return;
    }
  }
}
