<?
testborrarpro();
testabrepro();
 testcierrapro();

//seleccionar los proyectos del ususario
if (($_SESSION['proactivo'] > 0))
{
				?><h2>Caracteres del proyecto <?=$_SESSION['proname']?></h2><?
formcierrapro();
 formnewpro();
?>Listar datos del proyecto <? echo $_SESSION['proactivo'];
				listacar_proy($_SESSION['proactivo']);
}
else
{
 listapro($_SESSION['userid']);
 if($_POST['newpro'] == "Nuevo Proyecto")
 {
	insertpro($_POST['proname']);
 }
}


?>
