<div class="derecha">

<?php
$pathProyectos = "/var/www/proyectosGengine/";
$proy_id = $_SESSION['proactivo'];
$path = $pathProyectos.$proy_id."/".$proy_id."stats.json";


require_once('func_resumen.php');

if(file_exists($path)){
  //leerStats($path);

  $sql = "select name from caracteres where id in (select caracter_id from caracteres_proy where proyecto_id = ".$_SESSION['proactivo'].")";
  $conn = conecta();
  $res = pg_query($conn,$sql);
  $fenotipos = pg_fetch_all($res);
  $num_fenotipos = sizeof($fenotipos);
  ?><div class=?tab=><table>
    <tr>
<th></th>
   <th colspan="<?php echo 1+2*$num_fenotipos?>">Datos Parentales</th>
   <th colspan="<?php echo 1+2*$num_fenotipos?>">Datos Generacion</th>
</tr>
<tr>
<th></th>
<th></th>
<?php
  foreach($fenotipos as $fen){
 echo "<th colspan='2'>".$fen['name']."</th>";
  }
  echo "<th></th>";
  foreach($fenotipos as $fen){
 echo "<th colspan='2'>".$fen['name']."</th>";
  }
echo "</tr><tr>";
  ?><th>Gen.</th><th>Num.</th><?php
  foreach($fenotipos as $fen){
 echo "<th>Media</th><th>Varianza</th>";
  }
  echo "<th>Num.</th>";
  foreach($fenotipos as $fen){
 echo "<th>Media</th><th>Varianza</th>";
  }
echo "</tr>";
  pg_close($conn);


  $generaciones = array_keys($_SESSION['stats']);
  foreach($generaciones as $generacion){
    echo "<tr>";
    echo "<td>".$generacion."</td><td>";
    echo $_SESSION['stats'][$generacion]['parentales']['numindiv'];
    echo "</td>";
    foreach ($fenotipos as $fen){
      echo "<td>";
    echo $_SESSION['stats'][$generacion]['parentales'][$fen['name']]['mean'];
    echo "</td><td>";
    echo $_SESSION['stats'][$generacion]['parentales'][$fen['name']]['var'];
    }
    echo "</td><td>";
    echo $_SESSION['stats'][$generacion]['generacion']['numindiv'];
     echo "</td>";
    foreach ($fenotipos as $fen){
      echo "<td>";
    echo $_SESSION['stats'][$generacion]['generacion'][$fen['name']]['mean'];
    echo "</td><td>";
    echo $_SESSION['stats'][$generacion]['generacion'][$fen['name']]['var'];
    }
   echo "</td></tr>";

    /*
    debug_r($_SESSION['stats'][$generacion]['parentales']) ;
    debug("Generación: ".$generacion);
    debug('Parentales');
    debug_r($_SESSION['stats'][$generacion]['parentales']);
    debug("generación");
    debug_r($_SESSION['stats'][$generacion]['generacion']);
     */
  }
  echo "</table></div>";
}else{
debug("ERROR: Archivo no encontrado: ".$path);
}


?>
