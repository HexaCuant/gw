<?php

function out($a,$fh){
  echo $a;
  fwrite($fh,$a);
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
