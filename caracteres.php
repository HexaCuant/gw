<?php 
if(isset($_SESSION['proactivo'])){
				if (($_SESSION['proactivo'] > 0)){
								?><h2>Proyecto activo: <?php echo $_SESSION['proname']?></h2><?php 
				}
}
?><div class="derecha"><?php 
if(isset($_POST['abrircar'])) $_SESSION['caractivo'] = $_POST['abrircar'];
if(isset($_POST['cerrarcar'])){
				unset($_SESSION['caractivo']);
				$_SESSION['genactivo']=0;
				$_SESSION['conexiones']=FALSE;
				$_SESSION['vergenes']=FALSE;
}
if(isset($_SESSION['caractivo'])) testcar($_SESSION['caractivo']);
else if(isset($_POST['seleccionar'])) testcar($_POST['seleccionar']);
?></div><?php 
?><div class="derecha"><?php 
testgen();
testvergenes();
testalelo();
if (isset($_POST['abrirgen'])) testabrirgen($_POST['abrirgen']);
if (isset($_POST['borrargen'])) testabrirgen($_POST['borrargen']);
else if (isset($_SESSION['genactivo'])) if ($_SESSION['genactivo'] != 0) testabrirgen($_SESSION['genactivo']);
?></div><?php 
if(isset($_POST['newcar'])){
				if($_POST['newcar'] == "Nuevo Carácter")
				{
					insertcar($_POST['carname']);
				}
}
//seleccionar los proyectos del ususario
?><div class="izquierda"><?php 
listacar($_SESSION['id']);
?></div><?php 
?><fieldset><legend>Crear Nuevo carácter</legend><?php 
formnewcar();
?></fieldset><?php 
?>
<a name="fin">
