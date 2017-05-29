<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
use chatxbot\Chatfuel;

 
if (isset($_GET['type']) && ! empty($_GET['type']) && isset($_GET['comuna']) && ! empty($_GET['comuna'])  ){
  $type = strtoupper($_GET['type']);
  $comuna = strtoupper($_GET['comuna']);
  


if($type == '1'){
	$json = file_get_contents('http://farmanet.minsal.cl/maps/index.php/ws/getLocales');
} elseif ($type == '2'){
	$json = file_get_contents('http://farmanet.minsal.cl/maps/index.php/ws/getLocalesTurnos');
} 


$chatfuel = new Chatfuel(TRUE);

$data = json_decode($json);

$ref = array(-33.449474, -70.65527);

$items = array(
    '0' => array('item1','otheritem1details....','55.645645','-42.5323'),
    '1' => array('item1','otheritem1details....','100.645645','-402.5323')
);

$distances = array_map(function($item) use($ref) {
    $a = array_slice($item, -2);
    return distance($a, $ref);
}, $data);

asort($distances);

echo 'Close item is: ', var_dump($data[key($distances)]);


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