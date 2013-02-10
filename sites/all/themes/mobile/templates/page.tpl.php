<?php
global $base_url; 
?>

<link rel="stylesheet" type="text/css" href="<?php print $base_url.'/sites/all/themes/mobile/styles/responsive.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url.'/sites/all/themes/mobile/styles/reset.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url.'/sites/all/themes/mobile/styles/misc.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url.'/sites/all/themes/mobile/styles/layout.css'; ?>" />
<link rel="stylesheet" type="text/css" href="<?php print $base_url.'/sites/all/themes/mobile/styles/type.css'; ?>" />

       <?php if ($action_links): ?>
        <ul class="action-links">
          <?php print render($action_links); ?>
        </ul>
      <?php endif; ?>
        <div class="page_border">
            <div class="page_container">
                <div class="content">
                    <div class="header">
                        <div class="logo">
                            <h1 style="font-size: 26px;font-weight: bold;color:#9c9c9c;">myHomefiles</h1>
                        </div>
                        <div class="short-menu">

                           <?php print theme('links__system_main_menu', array(
                              'links' => $main_menu,
                              'attributes' => array(
                                'id' => 'menu',
                                'class' => array('scont'),
                              )
                            )); ?>
                            <br class="clear"/>
                        </div>
                        <br class="clear"/>
                        
                        
                     
                    </div>
                    <div class="main-content">

                        <div class="box-container">
                            <?php if(!empty($tabs['#primary'])||!empty($tabs['#secondary'])): ?>
                            <div class="colored">
                                <div style="float:left">
                                </div>
                                <div style="text-align: center;">
                                <?php print render($tabs); ?>
                        
                                </div>
                                <div style="clear:both"></div> 
                            </div>
                            <?php endif; ?>
                            <div>

                                <div class="box3">
                                <?php print render($page['content']);?>
                                </div>


                                <br class="clear"/>
                            </div>

                            <br class="clear"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>