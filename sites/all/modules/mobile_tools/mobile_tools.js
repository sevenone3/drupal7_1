(function($) {
  Drupal.behaviors.mobile_tools = {
    attach: function(context) {
      // Check the redirection settings
      if (Drupal.settings.mobile_tools.redirect == true && Drupal.settings.mobile_tools.path != '') {
        if (Drupal.settings.mobile_tools.auto == true) {
          // Perform the redirect
          window.location.replace(Drupal.settings.mobile_tools.path);
        }
        else {
          // Show the modal
          mobile_tools_generate_dialog_markup();
          var redirect_button_text = Drupal.t("Redirect");
          $('#mobile_tools_modal').dialog({
            modal: true,
            buttons: {
              "Redirect to optimized site" : function() {
                window.location.replace(Drupal.settings.mobile_tools.path);
              },
              "Continue to the requested page": function() {
                $(this).dialog('close');
              }
            },
            closeOnEscape: true,
            title: Drupal.t('Redirect'),
            minWidth: 400,
            resizeable: false,
            dialogClass: 'mobile_tools_redirect'
          });
        }
      }
    }
  }


/**
 * Generate the dialog markup
 */
function mobile_tools_generate_dialog_markup() {
  // @todo make the text configurable
  $('body').append('<div id="mobile_tools_modal">' + Drupal.t('Do you wish to be redirected to a version of this site optimized for your device?') + '</div>');
}

})(jQuery);