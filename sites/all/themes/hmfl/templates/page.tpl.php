<link type="text/css" rel="stylesheet" href="<?php print base_path().path_to_theme(); ?>/css/reset.css"/>
<link type="text/css" rel="stylesheet" href="<?php print base_path().path_to_theme(); ?>/css/main.css"/>

       <?php if ($action_links): ?>
        <ul class="action-links">
          <?php print render($action_links); ?>
        </ul>
      <?php endif; ?>
        <div class="page_border">
            <div class="page_container">
                <div class="content">
                    <div class="header">
                        <div class="logo fleft">
                            <h1 style="font-size: 26px;font-weight: bold;color:#9c9c9c;">myHomefiles</h1>
                        </div>
                        <div class="short-menu fright">

                           <?php print theme('links__system_main_menu', array(
                              'links' => $main_menu,
                              'attributes' => array(
                                'id' => 'menu',
                                'class' => array('fright','scont'),
                              )
                            )); ?>
                            <br class="clear"/>
                        </div>
                        <br class="clear"/>
                        <div class="top-menu fleft">
                            <ul>
                                <li class="no-left-padding"><a href="#" class="m-home">&nbsp;</a></li>
                            </ul>
                            <br class="clear"/>
                        </div>
                        <div class="top-menu fright">
                            <ul class="fright">
                                <li><a href="#" class="m-pdf">&nbsp;</a></li>
                                <li><a href="#" class="m-clock">&nbsp;</a></li>
                                <li><a href="#"  class="m-settings">&nbsp;</a></li>
                            </ul>
                            <br class="clear"/>
                        </div>
                        <br class="clear"/>
                    </div>
					<?php if ($page['quick_search']): ?>
                                   <div id="quick-searh-region">
                                     <?php print render($page['quick_search']); ?>
                                   </div>
                                <?php endif; ?>
                    <div class="main-content">
<?php //<a href="/find-nearby-places" rel="lightframe[|width: 1220px; height: 560px;]" title="my caption">click here!</a>; ?>
                        <div class="box-container">
                          
                            <div>

                                <div class="box3">
                                <?php if ($page['content_top']): ?>
                                   <div id="content-top">
                                     <?php print render($page['content_top']); ?>
                                   </div>
                                <?php endif; ?>
								
								
								<?php print $messages; ?>
								 

								 <?php if(!empty($tabs['#primary'])||!empty($tabs['#secondary'])): ?>
                            <div class="colored">
                                <div style="float:left">
                                </div>
                                
                                <?php print render($tabs); ?>
                        
                                
                                <div style="clear:both"></div> 
                            </div>
                            <?php endif; ?>
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