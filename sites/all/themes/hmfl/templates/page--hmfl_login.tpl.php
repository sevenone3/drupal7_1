
<?php
global $base_url;
global $user;
$request = explode("/",request_path());
 //if (!$user->uid) {
 //  if ($request[0] == 'developer' && !$request[1]) {
   // drupal_access_denied();
  // }
 //}
$eid = db_query("SELECT id as id1 FROM hmfl_developer WHERE title = '$request[1]' ");
foreach ( $eid as $id) {
  $new_id = $id->id1;
}
$result = db_query("SELECT field_developer_logo_fid AS fid FROM field_data_field_developer_logo WHERE entity_id = '$new_id'");
foreach ($result as $res) {
   $fid = $res->fid;
 }
 $imgpath = file_load($fid)->uri;
 $logo_url = file_create_url($imgpath);
$i=0;
$j=0;
 $development = db_query("SELECT id AS dev_id FROM hmfl_development WHERE developer_id = '$new_id' ");
 foreach($development as $dev) {
   // $dev_arr[] = $dev->dev_id;
	$devel_add = db_query("SELECT field_development_address_value AS address FROM field_data_field_development_address WHERE entity_id = '$dev->dev_id' ");
    foreach($devel_add as $dev_add) {
	 // $dev_add_arr[] = $dev_add->address;
	  $address = str_replace(" ", "+", $dev_add->address);
     $address = str_replace(" ", "+", $address);
      $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
     $json = json_decode($json);
      $lat[] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
      $long[] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	//  $i = $i + 1;
	//  $j= $j + 1;
	}
}
$result = db_query("SELECT field_developer_logo_fid AS fid FROM field_data_field_developer_logo WHERE entity_id = 4");
foreach ($result as $res) {
   $classic_fid = $res->fid;
 }
 $img_classic = file_load($classic_fid)->uri;
 $logo_classic = file_create_url($img_classic);
?>
<link href="<?php print base_path().path_to_theme(); ?>/css/main_owner.css" type="text/css" rel="stylesheet"/>

<div class="login_container">
    <div class="mdtxt"><img src="<?php print base_path().path_to_theme(); ?>/images/owner/mdtxt.png" alt=""/></div>

    <div class="cl_left fleft">
        <div class="mdlogo">
            <?php if($request[0]): ?>
			<img height="98" width ="266" src="<?php print $logo_url; ?>" alt=""/>
           <?php endif; ?>
		   <?php if(!$request[0]): ?>
		   <img height="98" width ="266" src="<?php print $logo_classic; ?>" alt=""/>
		   <?php endif; ?>
		</div>
        <form action="#" method="post" id="lgform">
            <div class="formfield">
            <?php echo drupal_render($variables['page']['content']['system_main']['development']); ?>
            <br class="clear"/>   
            </div>
            <div class="formfield">
                <?php echo drupal_render($variables['page']['content']['system_main']['plot']); ?>
                <br class="clear"/>
            </div>
            <div class="formfield">
                <?php echo drupal_render($variables['page']['content']['system_main']['password']); ?>
                <br class="clear"/>
            </div>
            <div class="formfield">
                <?php
//                 echo drupal_render($variables['page']['content']['system_main']['actions']);
echo drupal_render_children($variables['page']['content']['system_main']);
?>
                <br class="clear"/>
            </div>
        </form>
        <p>Welcome to <?php print $request[1]; ?> Homefiles. Please use the login above to access important information relating to your new home and its surrounding area.</p>
        <p>To make full use of this website you will require Adobe Reader (version 6 or above), or an alternative pdf reader.</p>

    </div>
    <div class="cl_right fleft">
        <div id="map" style="width:620px; height:490px;"></div>
        <p align="right" style="text-align:right;">Select your development from the drop down menu on the left to see the location of your new home</p>
    </div>
    <br class="clear"/>
</div>

<?php if($request[1]): ?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
$=jQuery;
  var geocoder = new google.maps.Geocoder()
  function findLocation(a,callback){
  geocoder.geocode( { "address": a}, function(results, status){
    if (status == google.maps.GeocoderStatus.OK)
    {
      callback(results[0].geometry.location.lat(),results[0].geometry.location.lng());
    }
    else
    {
      alert("Failed to lookup address :  " + status);
    }
  });
}
  jQuery(document).ready(function () {
    var mapContainer = jQuery("#map");
    var lat, lng;
    var lat = <?php echo json_encode($lat) ?>;
    var lng = <?php echo json_encode($long) ?>;
    var i ,marker;
	latLng = new google.maps.LatLng(<?php print $lat[0] ?>,<?php print $long[0] ?>);
	mapOptions = {zoom: 2, center: latLng,mapTypeId: google.maps.MapTypeId.ROADMAP,scrollwheel:false};
	map = new google.maps.Map(mapContainer.get(0),mapOptions);
for (i = 0; i < lat.length; i++) {  
 	latLng = new google.maps.LatLng(lat[i],lng[i]);
    
    marker = new google.maps.Marker({position:latLng,title:"location"});
    marker.setMap(map);
	//alert(latLng);
}
});
</script>
<?php endif; ?>
<?php 
if(!$request[0]) {
$c_development = db_query("SELECT id AS dev_id FROM hmfl_development WHERE developer_id = 4 ");
 foreach($c_development as $dev) {
   // $dev_arr[] = $dev->dev_id;
	$devel_add = db_query("SELECT field_development_address_value AS address FROM field_data_field_development_address WHERE entity_id = '$dev->dev_id' ");
    foreach($devel_add as $dev_add) {
	 // $dev_add_arr[] = $dev_add->address;
	  $address = str_replace(" ", "+", $dev_add->address);
     $address = str_replace(" ", "+", $address);
      $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false&region=$region");
     $json = json_decode($json);
      $lat[] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
      $long[] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
	//  $i = $i + 1;
	//  $j= $j + 1;
	}
}
}
?>
<?php if(!$request[0]): ?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
 jQuery(document).ready(function () {
    var mapContainer = jQuery("#map");
    var lat, lng;
    var lat = <?php echo json_encode($lat) ?>;
    var lng = <?php echo json_encode($long) ?>;
    var i ,marker;
	latLng = new google.maps.LatLng(<?php print $lat[0] ?>,<?php print $long[0] ?>);
	mapOptions = {zoom: 2, center: latLng,mapTypeId: google.maps.MapTypeId.ROADMAP,scrollwheel:false};
	map = new google.maps.Map(mapContainer.get(0),mapOptions);
for (i = 0; i < lat.length; i++) {  
 	latLng = new google.maps.LatLng(lat[i],lng[i]);
    
    marker = new google.maps.Marker({position:latLng,title:"location"});
    marker.setMap(map);
	//alert(latLng);
}
});
</script>
<?php endif; ?>

<?php if($request[0] == 'developer' && $request[2] == 'user'): ?>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  $=jQuery;
  var geocoder = new google.maps.Geocoder()
  function findLocation(a,callback){
  geocoder.geocode( { "address": a}, function(results, status){
    if (status == google.maps.GeocoderStatus.OK)
    {
      callback(results[0].geometry.location.lat(),results[0].geometry.location.lng());
    }
    else
    {
      alert("Failed to lookup address :  " + status);
    }
  });
}
  jQuery(document).ready(function () {
    var mapContainer = jQuery("#map");
    var lat, lng;
    lat = 40.69847032728747;
    lng = -73.9514422416687;
    latLng = new google.maps.LatLng(lat,lng);
    mapOptions = {zoom: 15, center: latLng,mapTypeId: google.maps.MapTypeId.ROADMAP,scrollwheel:false};
    map = new google.maps.Map(mapContainer.get(0),mapOptions);
    marker = new google.maps.Marker({position:latLng,title:"location"});
    marker.setMap(map);
    var filePath = "<?php echo base_path().file_stream_wrapper_get_instance_by_uri('public://')->getDirectoryPath(); ?>/";
    $("select[name=development]").change(function(){
//     alert("<?php echo url("development_json/");?>"+$(this).val());
      $.ajax({url:"<?php echo url("development_json/");?>"+$(this).val(),success:function(data){
        var address = data.field_development_address.und[0].value;
        findLocation(address,function(lat,lng){
          latLng = new google.maps.LatLng(lat,lng);
          marker.setPosition(latLng);
          map.setCenter(latLng);
        });
      }});
    });

  });
  </script>
 <?php endif; ?>
