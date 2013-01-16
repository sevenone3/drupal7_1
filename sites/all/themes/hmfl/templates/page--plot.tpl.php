<?php
  $element = $page['content']['system_main']['#element'];
  $group = hmfl_plot_group_load($element->group_id);
//   var_dump($group);
  $development = entity_load("hmfl_development",array($element->development_id));
  $development = !empty($development)?$development[$element->development_id]:new stdClass;
//   var_dump($development);
  $user_profile = entity_load("user",array($element->user_id));
  $user_profile = !empty($user_profile)?$user_profile[$element->user_id]:new stdClass;
  $developer = entity_load("hmfl_developer",array($development->developer_id));
  $developer = !empty($developer)?$developer[$development->developer_id]:new stdClass;
  $development->address = $development->field_development_address['und'][0];
?>

<?php
global $user;
$plot_id = arg(1);
$result = db_query("SELECT uid as uid FROM hmfl_plot_temporary_password WHERE id = '$plot_id'");
foreach ($result as $res) {
  if ($user->uid != $res->uid) {
     $flag = 1;
  }
}
if ($flag) {
  drupal_goto('node/123');
}   
?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>
<script type="text/javascript">
$=jQuery;
var geocoder;
var directionsService;
var directionsDisplay;
var map;
function findLocation(a,callback){
  geocoder.geocode( { 'address': a}, function(results, status){
    if (status == google.maps.GeocoderStatus.OK)
    {
      callback(results[0].geometry.location.lat(),results[0].geometry.location.lng());
    }
    else
    {
      alert("Geocode was not successful for the following reason: " + status);
    }
  });
}
function getDirection()
{
  var d=$("#plot_location .src_address").html();
  if(!d.length)
  {
    alert("please select destination");
    return;
  }
  var a=$("#plot_location input[name=dest_address]").val();
  if(!a.length)
  {
    alert("please input source address");
    return;
  }
  var request = { origin:a,destination:d,travelMode: google.maps.DirectionsTravelMode.TRANSIT,provideRouteAlternatives: true};

  directionsService.route(request, function(response, status){
    if (status == google.maps.DirectionsStatus.OK)
    {
      directionsDisplay.setDirections(response);
    }
    else
    {
      alert("can't find a direction");
    }
  });
}
$(document).ready(function(){
  geocoder = new google.maps.Geocoder()
  var mapContainer = jQuery("#map");

  findLocation('<?php echo $development->address; ?>',function(lat,lng){
    latLng = new google.maps.LatLng(lat,lng);
    mapOptions = {zoom: 15, center: latLng,mapTypeId: google.maps.MapTypeId.ROADMAP,scrollwheel:false};
    map = new google.maps.Map(mapContainer.get(0),mapOptions);
    map2 = new google.maps.Map(jQuery("#map2").get(0),mapOptions);
    marker = new google.maps.Marker({position:latLng,title:"location"});
    marker2 = new google.maps.Marker({position:latLng,title:"location"});
    marker.setMap(map);
    marker2.setMap(map2);
    var request = {
      location: latLng,
      radius: '5000',
      types:["accounting","airport","amusement_park","aquarium","art_gallery","atm","bakery","bank","bar","beauty_salon","bicycle_store","book_store","bowling_alley","bus_station","cafe","campground","car_dealer","car_rental","car_repair","car_wash","casino","cemetery","church","city_hall","clothing_store","convenience_store","courthouse","dentist","department_store","doctor","electrician","electronics_store","embassy","establishment","finance","fire_station","florist","food","funeral_home","furniture_store","gas_station","general_contractor","grocery_or_supermarket","gym","hair_care","hardware_store","health","home_goods_store","hospital"]
    };
    service = new google.maps.places.PlacesService(map2);
    service.nearbySearch(request, function(results, status) {
      if (status == google.maps.places.PlacesServiceStatus.OK) {
//       console.log("result : ",results);
        for (var i = 0; i < results.length; i++) {
          var place = results[i];
//           console.log(results[i].name);
          var m =  new google.maps.Marker({position:results[i].geometry.location,title:results[i].name,icon:results[i].icon});
          m.setMap(map2);
//           createMarker(results[i]);
        }
      }
    });
    geocoder = new google.maps.Geocoder();
    var mapControllsContainer = jQuery("#map_controlls");
    $(".pageRight .src_address").click(function(){
      map.setCenter(marker.getPosition());
    });
    $(".btnDocs,.btnLocal, .btnTransport").click(function(){
      $(".widgets .active").removeClass("active");
      $(".pageRight>div").hide();
      $()
      $(".pageRight #"+$(this).attr("rel")).show();
      $(this).addClass("active");
      google.maps.event.trigger(map, 'resize');
      map.setCenter(marker.getPosition());
      google.maps.event.trigger(map2, 'resize');
      map2.setCenter(marker2.getPosition());
    });

    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map)
    directionsDisplay.setPanel($("#dir-container").get(0));
  });


});
</script>
    <link rel="stylesheet" type="text/css" href="<?php print base_path().path_to_theme(); ?>/css/main_homeowner.css" />
 <div class="page">
  <div class="header">
    <div class="headerPannel">
      <div class="title">myHomefiles</div>
           <div class="short-menu fright">
                     <a href="<?php print '/'.request_path(); ?>">Home</a> | <a href="/user/logout">Logout</a>
                            </div>
    </div>
    <div class='banner'>
        <img style="height:250px;width:960px" src="<?php echo file_create_url($development->field_development_logo['und'][0]['uri']); ?>" />
    </div>
  </div>
  <div class="pageContent">
    <div class="pageLeft">
      <ul class="widgets">
        <li><a href="javascript:void(0)" class="btn btnDocs active" rel="plot_info"><img src="<?php print base_path().path_to_theme(); ?>/images/icon1.png" alt="btn" /><span>View your Home Owner Documents</span></a></li>
 <!--       <li><a href="#" class="btn btnCCare"><img src="<?php print base_path().path_to_theme(); ?>/images/icon11.png" alt="btn" /><span>Upload your own documents</span></a></li>-->
        <li><a href="<?php echo url("contact"); ?>" class="btn btnCCare"><img src="<?php print base_path().path_to_theme(); ?>/images/icon2.png" alt="btn" /><span>Customer Care</span></a></li>
        <li><a href="javascript:void(0)" class="btn btnTransport" rel="plot_transport"><img src="<?php print base_path().path_to_theme(); ?>/images/icon3.png" alt="btn" /><span>Transport</span></a></li>
        <li><a href="javascript:void(0)" class="btn btnLocal" rel="plot_location"><img src="<?php print base_path().path_to_theme(); ?>/images/icon4.png" alt="btn" /><span>Local Information</span></a></li>
        <li><a href="<?php echo url("announcements"); ?>" class="btn btnEvents"><img src="<?php print base_path().path_to_theme(); ?>/images/icon5.png" alt="btn" /><span>Announcements and Events</span></a></li>
        <li><a href="<?php echo url("forum"); ?>" class="btn btnForum"><img src="<?php print base_path().path_to_theme(); ?>/images/icon6.png" alt="btn" /><span>Message Forum</span></a></li>
        <li><a href="<?php echo url("faq"); ?>" class="btn btnHelp"><img src="<?php print base_path().path_to_theme(); ?>/images/icon7.png" alt="btn" /><span>Help and FAQ</span></a></li>
        <li>
          <div class="userInfo">
            <div class="userInfoTitle">Your Details</div>
            <div class="userInfoContent">
                <?php echo $user_profile->name;?><br/>
                <?php echo $user_profile->mail;?><br /><br />
                <?php 
                echo "{$group->address}, {$element->name}";?>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div class="pageRight">
      <div id="plot_info">
      <img style="max-height:200px;max-width:200px" src="<?php echo file_create_url($developer->field_developer_logo['und'][0]['uri']); ?>" />
      <div class="title">
        <h3>Your home owner documents for:</h3>
        <h3><?php echo $group->address; ?></h3>
      </div>
      <p>In this section you can download, view, and print your Home Owners Manual and various other documents that have been made available to you by <?php echo $developer->title; ?></p>
      <p>For help on downloading and using your documents take a look at <a href="#">How to use Homefiles</a></p>
      <ul class="fileList">
        <?php foreach($group->pdf as $pdf): ?>
        <?php $file=file_load($pdf['fid']); ?>
          <li><a href="<?php echo file_create_url($file->uri); ?>"> <?php echo $pdf['title']; ?><span class="size"><?php echo ceil($file->filesize/1048576);?>Mb</span></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="title">
        <h3>Advice on policy number</h3>
      </div>
      <p>To save yourself time and hassle it is a good idea to store your account & policy numbers online so that you can access them from anywhere at any time.</p>
      <p>To do this simply follow the <a href="#">My Details</a> link at the top of this page</p>
      </div>
      <div id="plot_transport" style="display:none">
        <div id="map" style="height:400px;width:550px;float:right;"></div>
        <div class="map_controlls" style="margin-top:20px;float:right;" >
          <a class="src_address" href="javascript:void(0)" style="cursor:pointer;color:black;display:inline-block;max-width:230px"><?php  echo $development->address['value']; ?></a>
           to <input type="text" name="dest_address" /> <input type="button" value="get directions" onclick="getDirection()" />
        </div>
        <div id="dir-container"></div>
      </div>
      <div id="plot_location" style="display:none">
        <div id="map2" style="height:400px;width:550px;float:right;"></div>
      </div>
    </div>
    <div class="clearer"></div>
  </div>
</div>
<div class="footer">This website and all its content is the intellectual property of Classic Folios Ltd. The re-distribution of any content is prohibited</div>