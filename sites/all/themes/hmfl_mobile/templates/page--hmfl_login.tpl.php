<?php
//   var_dump(get_defined_vars());
// var_dump($variables);
?>
        <link type="text/css" rel="stylesheet" href="<?php print base_path().path_to_theme(); ?>/css/mobile_reset.css"/>
                <link type="text/css" rel="stylesheet" href="<?php print base_path().path_to_theme(); ?>/css/mobile.css"/>

        <section class="login_container">
            <img class="txt-logo" src="<?php print base_path().path_to_theme(); ?>/images/mobile/txt.png" />
            <div class="logo" style="height:312px;line-height:400px;">
                <img src="<?php print base_path().path_to_theme(); ?>/images/mobile/logo.png" />
            </div>
                   <form action="#" method="post" id="lgform">
            <div class="login-form">
              <?php echo drupal_render($variables['page']['content']['system_main']['development']); ?>
              <?php echo drupal_render($variables['page']['content']['system_main']['plot']); ?>
              <?php echo drupal_render($variables['page']['content']['system_main']['password']); ?>
                <?php
//                 echo drupal_render($variables['page']['content']['system_main']['actions']);
echo drupal_render_children($variables['page']['content']['system_main']);
?>
            </div>
            </form>
        </section>

<script type="text/javascript">
$=jQuery;

  jQuery(document).ready(function () {

    var filePath = "<?php echo base_path().file_stream_wrapper_get_instance_by_uri('public://')->getDirectoryPath(); ?>/";
    $("select[name=development]").change(function(){
//     alert("<?php echo url("development_json/");?>"+$(this).val());
      $.ajax({url:"<?php echo url("development_json/");?>"+$(this).val(),success:function(data){
        var img = $("<img />");
        if(data.field_development_logo.length){
          img.attr("src",filePath+field_development_logo.und[0].filename,filePath);

        }
        img.attr("src","<?php print base_path().path_to_theme(); ?>/images/owner/dllogo.png");
        $(".logo").html(img);

      }});
    });

  });
</script>