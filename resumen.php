<div class="derecha">

<?php
$pathProyectos = "/var/www/proyectosGengine/";
$proy_id = $_SESSION['proactivo'];
$path = $pathProyectos.$proy_id."/".$proy_id."stats.json";


require_once('func_resumen.php');

if(file_exists($path)){
  //leerStats($path);
  
  $generaciones = array_keys($_SESSION['stats']);
  foreach($generaciones as $generacion){
    debug("Generación: ".$generacion);
    debug('Parentales');
    debug_r($_SESSION['stats'][$generacion]['parentales']);
    debug("generación");
    debug_r($_SESSION['stats'][$generacion]['generacion']);

  }
}else{
debug("ERROR: Archivo no encontrado: ".$path);
}


?>
