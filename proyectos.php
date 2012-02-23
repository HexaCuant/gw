<?
testborrarpro();
testabrepro();
testcierrapro();

//seleccionar los proyectos del ususario
if(isset($_SESSION['proactivo'])){
								?><h2>Caracteres del proyecto: <?=$_SESSION['proname']?> (id=<?=$_SESSION['proactivo']?>)</h2><?
								formcierrapro();
								listacar_proy($_SESSION['proactivo']);
				}else{
								 listapro($_SESSION['id']);
								 formnewpro();
								 if(isset($_POST['newpro'])){
												 if($_POST['newpro'] == "Nuevo Proyecto"){
																	insertpro($_POST['proname']);
								 }
				}
}


?>
