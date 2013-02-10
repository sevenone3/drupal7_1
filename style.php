<?php
 define('DRUPAL_ROOT','C:\inetpub\hmfl');
 require_once './includes/bootstrap.inc';
 drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
 header("Content-type: text/css; charset: UTF-8"); 
 $id = 1;
 $result = db_query("SELECT * FROM hmfl_styling WHERE developer_id = '$id'");
  foreach($result as $res) {
    if($res->top_strip) {
	  $check = 1;
	  $ban = explode('/',$res->top_banner);
	  $top_strip = explode('/',$res->top_strip);
	  $development_logo = $res->developement_logo;
	  $developer_logo = $res->developer_logo;
	  $header_text = explode('/',$res->header_text);
	  $body_text = explode('/',$res->body_text);
	  $link_text = explode('/',$res->link_text);
	}
  }
?>

.short-menu {
  background: #<?php print $top_strip[0]; ?>;
  border-color: #<?php print $top_strip[1]; ?>;
}
.short-menu a {
  color: #<?php print $top_strip[2]; ?>;
}
.short-menu a:hover {
  color: #<?php print $top_strip[3]; ?>;
}
.development-logo img{
  background-color: #<?php print $development_logo; ?>;
}

<?php if ($developer_logo == 'off'): ?>
  .developer_logo {
     display: none;
  }
<?php endif; ?>

.title-plot {
   color: #<?php print $header_text[0]; ?>;
   font-family: "<?php print $header_text[1]; ?>";
   font-size: <?php print $header_text[2]; ?>px;
   font-weight: <?php print $header_text[3]; ?>;
}
.body_text_plot {
   color: #<?php print $body_text[0]; ?>;
   font-family: "<?php print $body_text[1]; ?>";
   font-size: <?php print $body_text[2]; ?>px;
   font-weight: <?php print $body_text[3]; ?>;
} 
.link_plot a {
   font-family: "<?php print $body_text[1]; ?>";
   color: #<?php print $link_text[0]; ?>;
   font-weight: <?php print $link_text[1]; ?>;
   <?php if($link_text[2] == 'yes'): ?>
   text-decoration: underline;
   <?php endif; ?>
   <?php if($link_text[2] != 'yes'): ?>
     text-decoration: none;
   <?php endif; ?>
   font-size: 14px;
} 
.link_plot a:hover {
  font-family: "<?php print $body_text[1]; ?>";
  color: #<?php print $link_text[3]; ?>;
  font-weight: <?php print $link_text[4]; ?>;
   <?php if($link_text[5] == 'yes'): ?>
   text-decoration: underline;
   <?php endif; ?>
   <?php if($link_text[5] != 'yes'): ?>
     text-decoration: none;
   <?php endif; ?>
   font-size: 14px;
}