<?php // Embed Gmap map object ?>
<?php echo $this->Html->script('https://maps.googleapis.com/maps/api/js?v=3&key=' . Configure::read('Setup.googlemapskey'), ['block' => 'scriptBottom']); ?>

<?php $this->Html->scriptStart(['block' => 'scriptBottom']); ?>
<?php // Initialize Gmap code ?>

function initialize() {

var infowindow = new google.maps.InfoWindow();

/* OPZIONI GOOGLE MAPS */
var myOptions = {
center: new google.maps.LatLng(45.5018826, 10.9076801),
zoom: 13,
scrollwheel: false, 
};


let map = new google.maps.Map(document.getElementById("gmap"),myOptions);

let pointers = [];

<?php
$counter = 0;
$centerLat = 0;
$centerLng = 0;

foreach($items as $i => $item):
$content =  '<div class="infoWindow">';
$content .=  $this->Html->tag('h4', $item->title);			
if(!empty($item->address)) $content .= $this->Html->tag('div', $item->address);			
$content .= $this->Frontend->seolink(__('Vedi su Google Maps'), 'https://www.google.com/maps/place/' . $item->geocode, array('target' => '_blank'));
$content .=  '</div>';
$content = trim(preg_replace('/\s\s+/', ' ', $content));
$coords = explode(',', $item->geocode);
if(!empty($coords[0]) && !empty($coords[1])) {
  $lat = trim($coords[0]);
  $lng = trim($coords[1]);
  $centerLat += $lat;
  $centerLng += $lng;
  $counter++;
  ?>

  pointers[<?php echo $item->id; ?>] = new google.maps.Marker({
    position: new google.maps.LatLng(<?php  echo $lat ?>, <?php echo $lng ?>),
    map: map,
    icon: '/img/pointer.png',
    h4ref: <?php echo $item->id; ?>,
  });

  google.maps.event.addListener(pointers[<?php echo $item->id ?>], 'click', function(){
    map.panTo(this.position);
    infowindow.setContent('<?php echo str_replace("'", "\'", $content)?>');
    infowindow.open(map, pointers[<?php echo $item->id ?>]);
  });
<?php }  ?>

<?php endforeach; ?>		
map.setCenter(new google.maps.LatLng(<?php echo round($centerLat/$counter, 6) ?>, <?php echo round($centerLng/$counter, 6) ?>));
}

initialize();

<?php $this->Html->scriptEnd(); ?>
