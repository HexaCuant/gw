<div class="derecha">

<?php
$pathProyectos = "/var/www/proyectosGengine/";
$proy_id = $_SESSION['proactivo'];
$path = $pathProyectos.$proy_id."/".$proy_id.".stats";


require_once('func_resumen.php');

if(file_exists($path)){
leerStats($path);
}else{
crearStats($path);
}


?>
