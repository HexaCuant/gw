<?php



function leerStats($path){

  echo "Leer el archivo ".$path;
}

function crearStats($path){

  echo "Crear el archivo".$path;
  $fh = fopen($path,'w');
  if($fh){
    debug("Creado ".$path); 
    $sql = "select generacion_id from generaciones_proy where proy_id = ".$_SESSION['proactivo'];
    debug($sql);
    $conn = conecta();
    $res = pg_query($conn,$sql);
    if($res){
      debug("conectado");
      $generaciones = pg_fetch_all($res);
      foreach($generaciones as $generacion){
      debug($generacion['generacion_id']);
      }
    }else{
      debug("ERROR: no ha podido conectarse con la BD");
    }
  pg_close($conn);
  }else{
  echo "ERROR: no ha podido crearse el archivo de estadísticas: ".$path;
  }
  fclose($fh);
}





function debug($msg){
echo "<br/>".$msg."<br/>";
}
?>
