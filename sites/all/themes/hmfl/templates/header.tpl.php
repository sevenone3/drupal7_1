<div class="page">
  <div class="header">
    <div class="headerPannel">
      <div class="title"><a href="<?php echo url("dashboard");?>">myHomefiles</a></div>

                  <?php print theme('links__system_main_menu', array(
              'links' => $main_menu,
              'attributes' => array(
                'id' => 'menu',
                'class' => array('menu'),
              )
            )); ?>
    </div>

  </div>

  <div class="pageContent"> 
<!--   <h3 style="margin-bottom:50px;"><?php echo $title; ?></h3> -->