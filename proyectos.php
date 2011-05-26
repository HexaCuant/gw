<?
testborrarpro();

//seleccionar los proyectos del ususario

listapro($_SESSION['userid']);
formnewpro();
if($_POST['newpro'] == "Nuevo Proyecto")
{
	insertpro($_POST['proname']);
}

?>
