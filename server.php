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
Hora cierre: " . $mascerca->funcionamiento_hora_cierre . "
Dirección: " . $mascerca->local_direccion;


//$chatfuel->sendTextCard('' . $res . '', 'button');
$chatfuel->sendText('' . $res . '');
$chatfuel->createButtonToURL('Como llegar','https://www.google.cl/maps/@-33.440616,-70.6514212,15z');
// 		 $chatfuel->sendText('' . $mascerca->local_nombre . ''
// . '' . $mascerca->localidad_nombre 
// . '' . $mascerca->local_direccion
// . '' . $mascerca->funcionamiento_hora_apertura
// . '' . $mascerca->funcionamiento_hora_cierre 
// . '' . $mascerca->local_telefono . '');



//echo $closest . ' - ' . $mascerca->local_direccion . ', ' . $mascerca->comuna_nombre ;


// if( $data == null ){
// 	$chatfuel->sendText('nada');
// } else {
// 	foreach ($data as $result) {
//     if ($result->comuna_nombre == $comuna){
// 		 $chatfuel->sendText('' . $result->local_nombre . '
// ' . $result->comuna_nombre . 
// '' . $result->local_lat . 
// '' . $result->local_lng . '');

// 		//  $chatfuel->sendText([
// 		// 	 '' . $result->local_nombre . '',
// 		// 	 '' . $result->comuna_nombre . '',
// 		// 	 '' . $result->localidad_nombre . '',
// 		// 	 '' . $result->local_direccion . '',
// 		// 	 '' . $result->funcionamiento_hora_apertura . '',
// 		// 	 '' . $result->funcionamiento_hora_cierre . '',
// 		// 	 '' . $result->local_telefono . ''
// 		//  ]);
//           //echo $result->comuna_nombre . '   |   ' . $result->local_nombre . '   |   ' . $result->local_direccion . '   |   ' . $result->funcionamiento_hora_apertura . '<br>';
//       }
//   }
// }

  

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