<?
if (($_SESSION['proactivo'] > 0)){
				?><h2>Proyecto activo: <?=$_SESSION['proname']?></h2><?
}
?><div class="derecha"><?
testcar($_POST['abrircar']);
?></div><?
if(isset($_SESSION['genactivo'])) echo "activo: ".$_SESSION['genactivo'];
?><div class="derecha"><?
testgen();
testvergenes();
testalelo();
//if(isset($_POST['abrirgen'])){
				testabrirgen($_POST['abrirgen']);
//}

?></div><?

if($_POST['newcar'] == "Nuevo Carácter")
{
				echo "pulsado nuevo caracter";
	insertcar($_POST['carname']);
}
//seleccionar los proyectos del ususario
?><div class="izquierda"><?
listacar($_SESSION['userid']);
?></div><?

formnewcar();

//if(isset($_SESSION['caractivo'])) datoscar($_SESSION['caractivo']);

?>
<a name="fin">
