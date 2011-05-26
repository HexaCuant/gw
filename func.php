<?php
session_start();

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

function desconecta($conn)
{
 pg_close($conn);
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
<p><input type="submit" value="he olvidado mi contrase&ntilde;a" name="olvido" /></p>
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
         desconecta($conn);
				 return $cod_auth;
 }
 else
 {
	desconecta($conn);
  return 0;
 }
}



function inserta($user,$pass)
{
 $conn = conecta();
 $sql = "insert into users (cod_auth,username,pass) values (1, '$user','$pass')";
 echo $sql;
 $res=pg_query($conn,$sql);
 desconecta($conn);
 if($res) return 1;
 else
  {
				 echo "error en la inserción";
				 return 0;
  }
 }

function listapro($id)
{
 $conn = conecta();
 $sql="select * from proyectos where userid =".$id;
 $res=pg_query($conn,$sql);
 $rows=pg_NumRows($res);
?>
<form action="index.php?option=2" method="post">
 <table>
 <tr><th>Id</th><th>Nombre Proyecto</th><th>Borrar</th><th>Abrir</th></tr>
 <?
 for ($i=0;$i<$rows;$i++)
 {
  $id = pg_result($res,$i,0);
  $proname = pg_result($res,$i,1);
	?><tr><td><?=$id?></td><td><?=$proname?></td><?
  probutton($id);
	?></tr><?
 }
 ?>
 </table>
</form>
 <?
 desconecta($conn);
}

function probutton($id)
{
?>
				<td><input type="submit" name="borrarpro" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
				<td><input type="submit" name="abrirpro" value="<?=$id?>"</td> 
<?
}

function formnewpro()
{
 ?>
 <form action="index.php?option=2" method="post">
 <p>Nombre:<input type="text" name="proname"></input></p>
 <p><input type="submit" value="Nuevo Proyecto" name="newpro" /><input type="reset" value="borrar" /></p>
 </form>
 <?
}


function	insertpro($proname)
{
				$conn = conecta();
				$sql="insert into proyectos (proname,userid) values ('".$proname."','".$_SESSION['userid']."')";
				echo $sql;
				$res = pg_query($conn,$sql);
				if(!$res) echo "Error en la inserción del proyecto";
				desconecta($conn);
				refresh();
}

function refresh()
{
 echo "<meta http-equiv=\"refresh\" content=\"0\">";
}


function testborrarpro()
{
 if (isset($_POST['borrarpro']) && isset($_POST['confirmado']))
 {
 	$id=$_POST['borrarpro'];
	$sql = "delete from proyectos where id=".$id;
	$conn=conecta();
	pg_query($conn,$sql);
	desconecta($conn);
	refresh();
 }
}
