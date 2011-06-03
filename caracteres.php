<?
//testborrarcar();
?><div class="derecha"><?
testcar($_POST['abrircar']);
?></div><?

?><div class="derecha"><?
testgen();
testalelo();
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
