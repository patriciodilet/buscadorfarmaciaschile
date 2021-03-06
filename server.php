<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
use chatxbot\Chatfuel;

 
if (isset($_GET['type']) && ! empty($_GET['type']) && 
	isset($_GET['lat']) && ! empty($_GET['lat'])  &&
	isset($_GET['lon']) && ! empty($_GET['lon']) ){

  $type = $_GET['type'];
  $lat = $_GET['lat'];
  $lon = $_GET['lon'];
  


if($type == 'Todas'){
	$json = file_get_contents('http://farmanet.minsal.cl/maps/index.php/ws/getLocales');
} elseif ($type == 'Turno'){
	$json = file_get_contents('http://farmanet.minsal.cl/maps/index.php/ws/getLocalesTurnos');
} 


$chatfuel = new Chatfuel(TRUE);

$data = json_decode($json);

$ref = array($lat, $lon);


$closest = null;
$mascerca = null;
foreach ($data as $result) {
	$b = array($result->local_lat , $result->local_lng);
	$distance = distance($ref, $b);
	if ($closest === null || $closest > $distance) {
        $closest = $distance;
		$mascerca = $result;
    }
}

$res = "Farmacia: " . $mascerca->local_nombre . "
Comuna: " . $mascerca->localidad_nombre . "
Dirección: " . $mascerca->local_direccion . "
Hora apertura: " . $mascerca->funcionamiento_hora_apertura . "
Hora cierre: " . $mascerca->funcionamiento_hora_cierre;
 
$indicaciones = 'https://www.google.cl/maps/dir/' . $lat . ',' . $lon . '/' . $mascerca->local_lat . ',' . $mascerca->local_lng . '';  
 
 $chatfuel->sendTextCard('' . $res . '', array(
	$chatfuel->createButtonToURL('Como llegar', $indicaciones),
	$chatfuel->createButtonToBlock('Otra búsqueda','comenzar')
 ));
 
 
}
	
 
 function distance($a, $b)
{
    list($lat1, $lon1) = $a;
    list($lat2, $lon2) = $b;

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    return $miles;
}
   
   ?>