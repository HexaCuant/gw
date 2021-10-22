<?php

function leerStats($path){
  $fh = fopen($path);
  print(sizeof($_SESSION['stats']));
  foreach($_SESSION['stats'] as $generacion){
    print($_SESSION['stats'][0]);
    //debug_r($generacion);
  }
}



function debug($msg){
echo "<br/>".$msg."<br/>";
}

function debug_r($a){
  echo "<pre>";
  print_r($a);
  echo "</pre>";
}
?>
