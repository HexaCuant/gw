<?
testborrarpro();
testabrepro();
testcierrapro();

//seleccionar los proyectos del ususario

if ( !isset($_SESSION['proactivo']))
{
 listapro($_SESSION['userid']);
 formnewpro();
 if($_POST['newpro'] == "Nuevo Proyecto")
 {
	insertpro($_POST['proname']);
 }
}
else
{
formcierrapro();
?>Listar datos del proyecto <? echo $_SESSION['proactivo'];
}


?>
