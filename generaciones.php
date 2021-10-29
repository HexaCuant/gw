<?php 

if(isset($_SESSION['proactivo'])){
				if (($_SESSION['proactivo'] > 0)){
								?><h2>Proyecto activo: <?php echo $_SESSION['proname']?></h2><?php 
				}
}


								?><div class="derecha"><?php 
if(isset($_SESSION['proactivo'])){
				if (($_SESSION['proactivo'] > 0)){
								if (isset($_POST['abrirgeneracion']) || ($_SESSION['generacionactiva']>0)){
												if (isset($_POST['abrirgeneracion'])) $_SESSION['generacionactiva']=$_POST['abrirgeneracion'];
												abrirgeneracion($_SESSION['generacionactiva']);
								}
								if (isset($_POST['cerrargeneracion'])){
												$_SESSION['generacionactiva']=0;
												refresh();
								}
												?></div><?php 
								?><fieldset><legend>Crear Generación aleatoria</legend><?php 
								formnewrandom();
								?></fieldset><?php 
								testnewrandom();
								testlistgeneraciones();
								?><br /><fieldset><legend>Crear un cruce</legend><?php 
								crearcruce();
				//				cruce();
                ?></fieldset><?php 
								?><br/><fieldset><legend>Crear múltiples cruces</legend><?php 
                formnewmultiple();
                ?></fieldset><?php
                testnewmultiple();

				}
}
else{
?>No hay proyectos activos<?php 
}
?><a name="fin">
