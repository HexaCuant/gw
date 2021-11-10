<?php
require_once("func.php");
if(isset($_GET['option'])) $option=$_GET['option']; else $option=0;
if(isset($_POST['user'])) $user=$_POST['user']; else $user="";
if(isset($_POST['folder'])) $folder=$_POST['folder']; else $folder="";
$user = $user."-".$folder;
if(isset($_POST['pass'])) $pass=$_POST['pass']; else $pass="";

cabecera($option);

?>
<!--<p>dynamic</p>-->
<?php

if(isset($_POST['login'])){
  if($_POST['login'] == 'Entrar'){
    if(!isset($_SESSION['ident'])){
      $_SESSION['cod_auth'] = autentifica($user,$pass);
      #debug
      #echo "<br/>".$_SESSION['cod_auth'];
      #
      if ($_SESSION['cod_auth'] > 0){
        $_SESSION['ident']=TRUE;
        $_SESSION['user']=$user;
        $_SESSION['id']=getid($user);
      }else{
        echo "<h3>La identificación no es correcta</h3>";
      }
    }
  }



  else if($_POST['login'] == 'Nueva carpeta')
  {
    $_SESSION['cod_auth'] = inserta($user,$pass);
    echo $_SESSION['cod_auth'];
    if ($_SESSION['cod_auth'] > 0)
    {
      $_SESSION['ident']=TRUE;
    }
    $_SESSION['user']=$user;
    $_SESSION['id']=getid($user);
  }

  else if($_POST['login'] == 'salir')
  {
    #debug
    #echo $_SESSION['ident'];
    ###
    if (isset($_SESSION['ident'])) session_unset();
    $user="";
    $pass="";
    $_SESSION['cod_auth']=0;
  }
}
?>




<h1></h1>
<div class="centrado">
      <div class="login">
<?php //control de usuarios
autentificaform();
?></div></div><?php
if(isset($_SESSION['ident'])){
  if($_SESSION['ident'])
  {
?>
  <?php menu($option);?>

<div class="tab">
<a name="cruce">
<?php 
    switch ($option)
    {
      case 1;
      include("caracteres.php");
      break;

      case 2;
      include("proyectos.php");
      break;

      case 3;
      include("generaciones.php");
      break;

      case 4;
      include("resumen.php");
      break;
    }

?>

</div>

<?php 
  }
}
?>

</body></html>
