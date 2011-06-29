<?
if (($_SESSION['proactivo'] > 0)){
				?><h2>Proyecto activo: <?=$_SESSION['proname']?></h2><?
}


								?><div class="derecha"><?
if (($_SESSION['proactivo'] > 0)){
				if (isset($_POST['abrirgeneracion']) || ($_SESSION['generacionactiva']>0)){
								if (isset($_POST['abrirgeneracion'])) $_SESSION['generacionactiva']=$_POST['abrirgeneracion'];
								abrirgeneracion($_SESSION['generacionactiva']);
				}
				if (isset($_POST['cerrargeneracion'])){
								$_SESSION['generacionactiva']=0;
								refresh();
				}
								?></div><?
				?><fieldset><legend>Crear Generación aleatoria</legend><?
				formnewrandom();
				?></fieldset><?
				testnewrandom();
				testlistgeneraciones();
				?><br /><fieldset><legend>Crear un cruce</legend><?
				crearcruce();
//				cruce();
				?></fieldset><?
}
else{
?>No hay proyectos activos<?
}
?><a name="fin">
