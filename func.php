<?php
session_start();

require_once("func_car.php");
require_once("func_pro.php");
require_once("func_generaciones.php");

function cabecera()
{
 ?>
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 <html>
  <head>
  <link rel="stylesheet" type="text/css" media="screen" href="genweb.css" />
	<meta http-equiv="cache-control" content="no-cache" />
  <title>GenWeb</title>
  </head>
	<body>
 <?
}

function conecta()
{
 $conn = pg_connect("dbname=genweb user=genweb password=genweb");
 if(pg_ErrorMessage($conn))
 {
  echo "<p><b>Ocurrió un error en la conexión a la base de datos</b></p>";
	exit;
 }
 return $conn;
}

function menu($option)
{
?>
<div class="divmenu">
<ul class="menu2011">
<li <?if ($option==1)  print("class=\"verde\"");?>><a href="index.php?option=1">Caracteres</a></li>
<li <?if ($option==2)  print("class=\"verde\"");?>><a href="index.php?option=2">Proyectos</a></li>
<li <?if ($option==3)  print("class=\"verde\"");?>><a href="index.php?option=3">Generaciones</a></li>
</ul>
</div>
<?
}


function autentificaform()
{
if($_SESSION['ident'])
{
?>
				<h2>Identificado como <?=$_SESSION['user']?>
<form action="index.php" method="post">
<input type="submit" value="salir" name="login" />
</form>
</h2>
<?
}
else
{
?>
 <h2>Identifícate</h2>
<form action="index.php" method="post">
<p>Usuario<input type="text" name="user"></input></p>
<p>Contrase&ntilde;a<input type="password" name="pass"></input></p>
<p><input type="submit" value="identificar" name="login" /><input type="reset" value="borrar" /></p>
<!--<p><input type="submit" value="he olvidado mi contrase&ntilde;a" name="olvido" /></p>-->
<p><input type="submit" value="Nuevo Usuario" name="login"/></p>
</form>
<?
}
?>

<?
}

function autentifica($user,$pass)
{
 $conn = conecta();
 $sql = "select id,cod_auth from users where username='$user' and pass='$pass'";
 $res=pg_query($conn,$sql);
 $rows=pg_NumRows($res);
 if ($rows == 1)
 {
				 $_SESSION['userid'] = pg_result($res,1);
				 $cod_auth = pg_result($res,1);
         pg_close($conn);
				 return $cod_auth;
 }
 else
 {
	pg_close($conn);
  return 0;
 }
}

function getid($user){
				$conn=conecta();
				$sql = "select id from users where username = '".$user."'";
				$res = pg_query($conn,$sql);
				$id = pg_result($res,0);
				return $id;
}

function inserta($user,$pass)
{
				if(trim($user)=="" || trim($pass)==""){
								echo "<h3>ERROR: El nombre y/o la contraseña no pueden estar vacíos</h3>";
								return 0;
				}

				$conn = conecta();
				$sql="select * from users where username='".$user."'";
				$res = pg_query($conn,$sql);
				if($res){
								echo "<h3>ERROR: El nombre de usuario ya existe.</h3>";
								return 0;
				}
				else{
 $sql = "insert into users (cod_auth,username,pass) values (1, '$user','$pass')";
 $res=pg_query($conn,$sql);
 pg_close($conn);
				}
 if($res) return 1;
 else
  {
				 echo "error en la inserción";
				 return 0;
  }
 }


function refresh()
{
				echo "<meta http-equiv=\"refresh\" content=\"0\">";
}

