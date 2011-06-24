<?
testborrarpro();
testabrepro();
 testcierrapro();

//seleccionar los proyectos del ususario
if (($_SESSION['proactivo'] > 0))
{
				?><h2>Caracteres del proyecto: <?=$_SESSION['proname']?></h2><?
formcierrapro();
				listacar_proy($_SESSION['proactivo']);
}
else
{
 listapro($_SESSION['userid']);
 formnewpro();
 if($_POST['newpro'] == "Nuevo Proyecto")
 {
	insertpro($_POST['proname']);
 }
}


?>
