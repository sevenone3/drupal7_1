(function($) {
  Drupal.behaviors.mobile_tools_browscap = {
    attach: function(context) {
      $('#edit-mobile-tools-browscap-user-agents').chosen({
        no_results_text: Drupal.t("No results matched"),
        search_contains: true,
        allow_single_deselect: true,
      });
      $('#edit-mobile-tools-browscap-parents').chosen({
        no_results_text: Drupal.t("No results matched"),
        search_contains: true,
        allow_single_deselect: true,
      });
    }
  }
})(jQuery);
