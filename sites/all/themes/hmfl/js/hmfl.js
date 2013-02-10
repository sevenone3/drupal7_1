
(function($) {
 $(document).ready(function(){    
  $('#edit-developments').change(function(){
   var dev_id = $('#edit-developments').val();
   $.get(Drupal.settings.basePath+'developer/'+dev_id+'/assign-pdfs', function(data) {

     });
});

$('.delete,.delete-developer-admin,.delete-development').click(function(){
  var answer = confirm("Are you sure?");
  if(!answer){
      //false when they click cancel 
      return false;
  }
  else {
    window.location.href = this.href;}
});

$('.developer-ajax-edit').editable('/developer/ajax-update', { 
         submit    : 'OK',
         indicator : '<img src="/sites/all/themes/hmfl/images/ajax-loader.gif">',
         tooltip   : 'Click to edit...',
		 style: 'inherit',
});
$('.ajax-edit-admin').editable('/dev/admin/ajax-update', { 
         submit    : 'OK',
         indicator : '<img src="/sites/all/themes/hmfl/images/ajax-loader.gif">',
         tooltip   : 'Click to edit...',
		 style: 'inherit',
});
$('.admin-full-name').editable('/dev/admin/full/name/ajax-update', { 
         submit    : 'OK',
         indicator : '<img src="/sites/all/themes/hmfl/images/ajax-loader.gif">',
         tooltip   : 'Click to edit...',
		 style: 'inherit',
});
$('.ajax-edit-email').editable('/dev/admin/email/ajax-update', { 
         submit    : 'OK',
         indicator : '<img src="/sites/all/themes/hmfl/images/ajax-loader.gif">',
         tooltip   : 'Click to edit...',
		 style: 'inherit',
});
$('.development-title-ajax-edit').editable('/dev/title/ajax-update', { 
         submit    : 'OK',
         indicator : '<img src="/sites/all/themes/hmfl/images/ajax-loader.gif">',
         tooltip   : 'Click to edit...',
		 style: 'inherit',
});

});
})(jQuery);