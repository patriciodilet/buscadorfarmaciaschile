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

if( $data == null ){
	$chatfuel->sendText('nada');
} else {
	
	foreach ($data as $result) {

    if ($result->comuna_nombre == $comuna){
		 $chatfuel->sendText('' . $result->local_nombre . '
' . $result->comuna_nombre . '');

		//  $chatfuel->sendText([
		// 	 '' . $result->local_nombre . '',
		// 	 '' . $result->comuna_nombre . '',
		// 	 '' . $result->localidad_nombre . '',
		// 	 '' . $result->local_direccion . '',
		// 	 '' . $result->funcionamiento_hora_apertura . '',
		// 	 '' . $result->funcionamiento_hora_cierre . '',
		// 	 '' . $result->local_telefono . ''
		//  ]);
          //echo $result->comuna_nombre . '   |   ' . $result->local_nombre . '   |   ' . $result->local_direccion . '   |   ' . $result->funcionamiento_hora_apertura . '<br>';
      }
  }
}

  

}
	
 
   
   ?>