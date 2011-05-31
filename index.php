<?php
require_once("func.php");
if(isset($_GET['option'])) $option=$_GET['option']; else $option=0;
if(isset($_POST['user'])) $user=$_POST['user']; else $user="";
if(isset($_POST['pass'])) $pass=$_POST['pass']; else $pass="";

cabecera($option);

if($_POST['login'] == 'identificar')
{
				if(!isset($_SESSION['ident']))
				{
				 $_SESSION['cod_auth'] = autentifica($user,$pass);
				if ($_SESSION['cod_auth'] > 0)
				{
								$_SESSION['ident']=TRUE;
				}
				$_SESSION['user']=$user;
				}
}
else if($_POST['login'] == 'Nuevo Usuario')
{
				$_SESSION['cod_auth'] = inserta($user,$pass);
				echo $_SESSION['cod_auth'];
				if ($_SESSION['cod_auth'] > 0)
				{
								$_SESSION['ident']=TRUE;
				}
				$_SESSION['user']=$user;
				}

else if($_POST['login'] == 'salir')
{
 session_unset($_SESSION['ident']);
 $user="";
 $pass="";
 $_SESSION['cod_auth']=0;
}

?>




<h1>Genweb</h1>
<?//control de usuarios
autentificaform();
if($_SESSION['ident'])
{
?>
	<?menu($option);?>

<div class="tab">
<?
				switch ($option)
				{
         case 1;
				 include("caracteres.php");
     break;

         case 2;
				 include("proyectos.php");
     break;

         case 3;
				 echo "opcion 3";
     break;
				}

?>

</div>

<?
}
?>

</body></html>
