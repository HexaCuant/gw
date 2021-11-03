<div class="derecha">

<?php
$pathProyectos = "/var/www/proyectosGengine/";
$proy_id = $_SESSION['proactivo'];
$path = $pathProyectos.$proy_id."/".$proy_id."stats.json";
$outfilename = $pathProyectos.$proy_id."/".$proy_id."resumen_punto.html";

require_once('func_resumen.php');
require_once('debug.php');

if(file_exists($path)){
  //leerStats($path);
$fh = fopen($outfilename,'w');
  $sql = "select name from caracteres where id in (select caracter_id from caracteres_proy where proyecto_id = ".$_SESSION['proactivo'].")";
  $conn = conecta();
  $res = pg_query($conn,$sql);
  $fenotipos = pg_fetch_all($res);
  $num_fenotipos = sizeof($fenotipos);
  $colspan = 1+2*$num_fenotipos;
  out(
  '<div class=?tab=><table>
    <tr>
<th></th>
   <th colspan="'.$colspan.'">Datos Parentales</th>
   <th colspan="'.$colspan.'">Datos Generacion</th>
</tr>
<tr>
<th></th>
<th></th>',$fh);

  foreach($fenotipos as $fen){
 out("<th colspan='2'>".$fen['name']."</th>",$fh);
  }
  out("<th></th>",$fh);
  foreach($fenotipos as $fen){
 out("<th colspan='2'>".$fen['name']."</th>",$fh);
  }
out("</tr><tr>",$fh);
  out('<th>Gen.</th><th>Num.</th>',$fh);
  foreach($fenotipos as $fen){
 out("<th>Media</th><th>Varianza</th>",$fh);
  }
  out("<th>Num.</th>",$fh);
  foreach($fenotipos as $fen){
 out("<th>Media</th><th>Varianza</th>",$fh);
  }
out("</tr>",$fh);
  pg_close($conn);

  $generaciones = array_keys($_SESSION['stats']);
  foreach($generaciones as $generacion){
    out("<tr>",$fh);
    out("<td>".$generacion."</td><td>",$fh);
    if(isset($_SESSION['stats'][$generacion]['parentales'])) out($_SESSION['stats'][$generacion]['parentales']['numindiv'],$fh);
    out("</td>",$fh);
    foreach ($fenotipos as $fen){
      out("<td>",$fh);
    if(isset($_SESSION['stats'][$generacion]['parentales'])) out($_SESSION['stats'][$generacion]['parentales'][$fen['name']]['mean'],$fh);
    out("</td><td>",$fh);
    if(isset($_SESSION['stats'][$generacion]['parentales'])) out($_SESSION['stats'][$generacion]['parentales'][$fen['name']]['var'],$fh);
    }
    out("</td><td>",$fh);
    out($_SESSION['stats'][$generacion]['generacion']['numindiv'],$fh);
     out("</td>",$fh);
    foreach ($fenotipos as $fen){
      out("<td>",$fh);
    out($_SESSION['stats'][$generacion]['generacion'][$fen['name']]['mean'],$fh);
    out("</td><td>",$fh);
    out($_SESSION['stats'][$generacion]['generacion'][$fen['name']]['var'],$fh);
    }
   out("</td></tr>",$fh);

    /*
    debug_r($_SESSION['stats'][$generacion]['parentales']) ;
    debug("Generación: ".$generacion);
    debug('Parentales');
    debug_r($_SESSION['stats'][$generacion]['parentales']);
    debug("generación");
    debug_r($_SESSION['stats'][$generacion]['generacion']);
     */
  }
  out("</table></div>",$fh);
  fclose($fh);
  //debug_r($_SESSION['stats']);
  $puntofilename = '/proyectosGengine/'.$proy_id."/".$proy_id."resumen_punto.html";
  ?><p><a href="<?php echo $puntofilename?>">Descargar datos</a> (puntos decimales)</p><?php 
  //archivo con comas decimales
  $outcomafilename = $pathProyectos.$proy_id."/".$proy_id."resumen_coma.html";
  $comafilename = '/proyectosGengine/'.$proy_id."/".$proy_id."resumen_coma.html";
  $fh = fopen($outfilename,"r");
  $fhcoma = fopen($outcomafilename,"w");
  if(!$fh){echo "ERROR abriendo:".$outfilename;}
  if(!$fhcoma){echo "ERROR abriendo:".$outcomafilename;}
  while (($line = fgets($fh)) !== false){
    $comaline = preg_replace('@\.@',',',$line);
    fwrite($fhcoma,$comaline);
  }
  fclose($fh);
  fclose($fhcoma);
  ?><p><a href="<?php echo $comafilename?>">Descargar datos</a> (comas decimales)</p><?php 
 


}else{
debug("ERROR: Archivo no encontrado: ".$path);
}


?>
