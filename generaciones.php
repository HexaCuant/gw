<?
if (($_SESSION['proactivo'] > 0)){
				?><h2>Proyecto activo: <?=$_SESSION['proname']?></h2><?
}

?><h2>Crear Generación aleatoria</h2><?

if (($_SESSION['proactivo'] > 0)){
				if (isset($_POST['abrirgeneracion'])){
								?><div class="derecha"><?
								abrirgeneracion($_POST['abrirgeneracion']);
								?></div><?
				}
				formnewrandom();
				testnewrandom();
				testlistgeneraciones();
}
else{
?>No hay proyectos activos<?
}
?><a name="fin">
