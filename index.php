<?php
require_once("func.php");
if(isset($_GET['option'])) $option=$_GET['option']; else $option=0;
if(isset($_POST['user'])) $user=$_POST['user']; else $user="";
if(isset($_POST['pass'])) $pass=$_POST['pass']; else $pass="";

cabecera($option);

if(isset($_POST['login'])){
if($_POST['login'] == 'identificar'){
				if(!isset($_SESSION['ident'])){
								$_SESSION['cod_auth'] = autentifica($user,$pass);
								if ($_SESSION['cod_auth'] > 0){
												$_SESSION['ident']=TRUE;
								$_SESSION['user']=$user;
								$_SESSION['id']=getid($user);
								}else{
												echo "<h3>La identificación no es correcta</h3>";
								}
				}
}



else if($_POST['login'] == 'Nuevo Usuario')
{
				$_SESSION['cod_auth'] = inserta($user,$pass);
//				echo $_SESSION['cod_auth'];
				if ($_SESSION['cod_auth'] > 0)
				{
								$_SESSION['ident']=TRUE;
				}
				$_SESSION['user']=$user;
				$_SESSION['id']=getid($user);
}

else if($_POST['login'] == 'salir')
{
 session_unset($_SESSION['ident']);
 $user="";
 $pass="";
 $_SESSION['cod_auth']=0;
}
}
?>




<h1>Genweb</h1>
<?php //control de usuarios
autentificaform();
if(isset($_SESSION['ident'])){
if($_SESSION['ident'])
{
?>
	<?php menu($option);?>

<div class="tab">
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
				}

?>

</div>

<?php 
}
}
?>

</body></html>
