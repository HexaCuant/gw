<?php 

error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!isset($_SESSION['generacionactiva'])) $_SESSION['generacionactiva']=0;



if(!isset($_SESSION['conexiones'])) $_SESSION['conexiones']=FALSE;
if(!isset($_SESSION['vergenes'])) $_SESSION['vergenes']=FALSE;

function listacar()
{
				$conn = conecta();
				if(isset($_POST['borrarcar']) && isset($_POST['confirmado'])){
								$sql="delete from caracteres where id=".$_POST['borrarcar'];
								$res=pg_query($conn,$sql);
				}	
				//$sql="select distinct caracter_id from caracteres_proy where proyecto_id =".$proyecto_id;
				$sql="select id,name from caracteres where creatorid = '".$_SESSION['id']."' or public order by id";
 $res=pg_query($conn,$sql);
 pg_close($conn);
 $rows=pg_NumRows($res);
?>
<form action="index.php?option=1#fin" method="post">
 <table>
 <tr><th>Id</th><th>Carácter</th><th>Borrar</th><th>Abrir</th>
<?php 
 if(isset($_SESSION['proactivo'])){
				if($_SESSION['proactivo']){
								?><th>Seleccionar</th><?php 
				}
 }
?>
</tr>
 <?php 
 for ($i=0;$i<$rows;$i++)
 {
  $id = pg_fetch_result($res,$i,0);
  $name = pg_fetch_result($res,$i,1);
	?><tr><td><?php echo $id?></td><td><?php echo $name?></td><?php 
  carbutton($id);
	?></tr><?php 
 }
 ?>
 </table>
</form>
 <?php 
 //formnewcar();
}


function listacar_proy($proyecto_id){
				$conn = conecta();
				if(isset($_POST['borrarcar']) && isset($_POST['confirmado'])){
								$sql="delete from caracteres_proy where caracter_id = ".$_POST['borrarcar']." and proyecto_id = ".$_SESSION['proactivo'];
								$res=pg_query($conn,$sql);
				}
 				if(isset($_POST['actualizar'])){
								$nameambiente = $_POST['actualizar']."ambiente";
								if($_POST[$nameambiente]!= ""){
												$sql="update caracteres_proy set ambiente=".$_POST[$nameambiente]." where caracter_id = ".$_POST['actualizar']." and proyecto_id = ".$_SESSION['proactivo'];
												$res=pg_query($conn,$sql);
												refresh();
								}
				}
 $sql="select caracter_id,ambiente from caracteres_proy where proyecto_id =".$proyecto_id." order by caracter_id";
 $res=pg_query($conn,$sql);
 $rows=pg_NumRows($res);
?>
<form action="index.php?option=2#fin" method="post">
 <table>
 <tr><th>Id</th><th>Carácter</th><th>Ambiente</th><th>Borrar</th><th>Actualizar</th>
</tr>
 <?php 
 for ($i=0;$i<$rows;$i++)
 {
  $id = pg_fetch_result($res,$i,0);
  $sql="select name from caracteres where id =".$id;
	$resname=pg_query($conn,$sql);
	$name = pg_fetch_result($resname,0);
	$ambiente = pg_fetch_result($res,$i,1);
	?><tr><td><?php echo $id?></td><td><?php echo $name?></td><td><input type="text" name="<?php echo $id?>ambiente" value="<?php echo $ambiente?>"></input><?php 
  carbuttonproy($id);
	?></tr><?php 
 }
 ?>
 </table>
</form>
 <?php 
 pg_close($conn);
 //formnewcar();
}

function formnewcar()
{
 ?>
 <form action="index.php?option=1#fin" method="post">
 <p>Nombre:<input type="text" name="carname"></input></p>
<!--<p>Visible:<input type="checkbox" name="visible"></input>
Público:<input type="checkbox" name="publico"></input></p>-->
 <p><input type="submit" value="Nuevo Carácter" name="newcar" /><input type="reset" value="borrar" /></p>
 </form>
 <?php 
}

function	insertcar($carname)
{
				$conn = conecta();
				if (isset($_POST['visible'])) $visible = "t"; else $visible="f";
				if (isset($_POST['publico'])) $publico = "t"; else $publico="f";
				$sql="insert into caracteres (name,creatorid,public,visible) values ('".$carname."','".$_SESSION['id']."','".$publico."','".$visible."')";
				$res = pg_query($conn,$sql);
				if(!$res) echo "Error en la inserción del proyecto";
				pg_close($conn);
				refresh();
}

function testcar($id){
				if(isset($_POST['abrircar'])){
								$_SESSION['caractivo']=$id;
				}
				else	if(isset($_POST['cerrarcar'])){
												unset($_SESSION['caractivo']);
												unset($_SESSION['name_caractivo']);
												$_SESSION['vergenes']=FALSE;
												$_SESSION['conexiones']=FALSE;
				}
				else if(isset($_POST['borrarcar']) && isset($_POST['confirmado'])){
								unset($_SESSION['caractivo']);
								unset($_SESSION['name_caractivo']);
								$_SESSION['vergenes']=FALSE;
								$_SESSION['conexiones']=FALSE;

								$id=$_POST['borrarcar'];
								$sql="delete from caracteres where id=".$id;
								echo $sql."<br/>";
								$conn=conecta();
								$res=pg_query($conn,$sql);
				pg_close($conn);
								if (!$res) echo "ERROR: No se pudo borrar el carácter"; 
				}
				else if(isset($_POST['datoscar'])){
								if (isset($_POST['sexo'])) $sexo="t"; else $sexo = "f";
								if (isset($_POST['visible'])) $visible="t"; else $visible = "f";
								if (isset($_POST['public'])) $public="t"; else $public = "f";
								$ambiente = $_POST['ambiente'];
								$conn = conecta();
								$sql = "update caracteres set visible='".$visible."', public='".$public."', sexo='".$sexo."' where id = ".$_SESSION['caractivo'];
								$res = pg_query($conn,$sql);
								pg_close($conn);
								if(!$res) echo "Error: carácter no encontrado";
				}
				else if(isset($_POST['seleccionar'])){
								$sql = "insert into  caracteres_proy (caracter_id, proyecto_id) values (".$_POST['seleccionar'].", ".$_SESSION['proactivo'].")";
								$conn = conecta();
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: carácter no encontrado";
								else echo "insertado el carácter ".$_POST['seleccionar']." en el proyecto ".$_SESSION['proactivo'];
				}
				if(isset($_SESSION['caractivo'])) datoscar($_SESSION['caractivo']);
}

function getowner($id){
				$conn_owner = conecta();
				$sql="select creatorid from caracteres where id=".$id;
				$res=pg_query($conn_owner,$sql);
				pg_close($conn_owner);
				if (!$res) echo "ERROR: No se pudo borrar el carácter";
				else $_SESSION['owner']=pg_fetch_result($res,0,0);
}

function checkowner(){
				if($_SESSION['owner'] == $_SESSION['id']) return TRUE;
				else return FALSE;
}


function carbutton($id){
				getowner($id);
				if(checkowner()){
?>
<td><input type="submit" name="borrarcar" value="<?php echo $id?>"></input>
<input type="checkbox" name="confirmado"></input></td>
<?php 
				}else{
								?><td></td><?php 
				}

?>
<td><input type="submit" name="abrircar" value="<?php echo $id?>"></input></td> 
<?php 
				if(isset($_SESSION['proactivo'])){
								if($_SESSION['proactivo']){
												?><td><input type="submit" name="seleccionar" value="<?php echo $id?>"></input><?php 
								}
				}
}


function carbuttonproy($id){
?>
<td><input type="submit" name="borrarcar" value="<?php echo $id?>"></input><input type="checkbox" name="confirmado"></input></td>
<td><input type="submit" name="actualizar" value="<?php echo $id?>"></input></td>
<?php 
}

function datoscar($car_id){
				getowner($_SESSION['caractivo']);
				        $conn = conecta();
								$sql="select name,public,visible,sexo,creatorid from caracteres where id=".$car_id;
								$res = pg_query($conn,$sql);
								pg_close($conn);
								if(!$res) echo "Error: carácter no encontrado";
								else{
												$_SESSION['name_caractivo'] = pg_fetch_result($res,0,0);
												$public =  pg_fetch_result($res,0,1);
												$visible =  pg_fetch_result($res,0,2);
												$sexo =  pg_fetch_result($res,0,3);
												$creatorid =  pg_fetch_result($res,0,4);
								}
?>
			<h2>Carácter:<?php echo $_SESSION['name_caractivo']?></h2>
			<form action="index.php?option=1#fin" method="post">
			<p>Sexo: <input type="checkbox" name="sexo"  <?php if($sexo == "t") print("checked")?>></input></p>
			<!--<p>Visible: <input type="checkbox" name="visible"--> <?php #if($visible == "t") print("checked")?><!-- ></input>-->
			<!--Público: <input type="checkbox" name="public"--> <?php #if($public == "t") print("checked")?><!-- ></input>-->
			</p>
<?php 
if($creatorid == $_SESSION['id']){
?>
			<input type="submit" value="Guardar Cambios" name="datoscar"></input>
<?php 
}
?>
<input type="submit" value="Cerrar" name="cerrarcar"></input></p>

<?php 
if(($creatorid == $_SESSION['id']) || ($visible == 't')){
												if($_SESSION['vergenes']){
				?>
							<p><input type="submit" value="Ocultar Genes" name="ocultargenes"></input></p><?php 
												}else{
				?>
																<p><input type="submit" value="Ver Genes" name="vergenes"></input></p><?php 
												}
				}

if($creatorid == $_SESSION['id']){
?>
			<p><input type="submit" value="Nuevo gen" name="nuevogen"></input></p>
<?php }?>
      </form>

</div><?php 
}

function testvergenes(){
				if(isset($_POST['ocultargenes'])){
								$_SESSION['vergenes']=FALSE;
								$_SESSION['conexiones']=FALSE;
								refresh();
				}
				if(isset($_POST['vergenes'])) refresh();


				if(isset($_SESSION['caractivo'])){
								if(isset($_SESSION['vergenes'])){
								if(isset($_POST['vergenes']) || $_SESSION['vergenes']){
								$_SESSION['vergenes']=TRUE;
								$conn = conecta();
								$sql="select gen_id from genes_car where car_id =".$_SESSION['caractivo']." order by gen_id";
								$res = pg_query($conn,$sql);
				        pg_close($conn);
								$filas = pg_num_rows($res);
								?>
								<form action="index.php?option=1#fin" method="post">
								<table>
								<tr><th>Id</th><th>Nombre</th><th>chr</th><th>pos</th><th>cod</th><th>Borrar</th><th>Abrir</th></tr><?php 
								        for ($i=0;$i<$filas;$i++){
																$gen_id = pg_fetch_result($res,$i,0);
																$conngen=conecta();
												        $sqlgen = "select * from genes where idglobal =".$gen_id;
												        $resgen = pg_query($conngen,$sqlgen);
																pg_close($conngen);
																if(!$resgen) echo "ERROR: No se insertó la información del gen en la BD";
																$idglobal=pg_fetch_result($resgen,0);
																$name=pg_fetch_result($resgen,2);
																$chr=pg_fetch_result($resgen,3);
																$pos=pg_fetch_result($resgen,4);
																$code=pg_fetch_result($resgen,5);
?>
				<tr><td><?php echo $idglobal?></td><td><?php echo $name?></td><td><?php echo $chr?></td><td><?php echo $pos?></td><td><?php echo $code?></td>
<?php 
																genbutton($idglobal)
?>
</tr>
<?php 
												}
?></table>
<br />
<?php 
								if($_SESSION['conexiones']){
												?><input type="submit" name="ocultarconexiones" value="Ocultar Conexiones"></input><?php 

								}	
								else{
								?><input type="submit" name="verconexiones" value="Ver Conexiones"></input><?php 
								}
								?></form><?php 
								}
								}
				}
				//GUARDAR CONEXIONES
				if(isset($_POST['conexion'])){
								$SA=$_POST['SA'];
								$SB=$_POST['SB'];
								$gen=$_POST['transicion'];
								$conn = conecta();
								$sql = "insert into conexiones (estadoa, transicion, estadob, car_id) values (".$SA.",".$gen.",".$SB.",".$_SESSION['caractivo'].")";
								$res = pg_query($conn,$sql);
								if(!$res) echo "ERROR: No se insertó la conexión en la BD";
								pg_close($conn);
				}
				//CONEXIONES
				if(isset($_POST['ocultarconexiones'])){
								$_SESSION['conexiones']=FALSE;
								refresh();
				}

				if(isset($_POST['verconexiones'])){
								$_SESSION['conexiones']=TRUE;
								$_SESSION['sustratos']=$_POST['sustratos'];
								refresh();
				}
				if(isset($_POST['cambiarsustratos'])){
								$conn = conecta();
								$_SESSION['sustratos'] = $_POST['sustratos'];
								$sql = "update caracteres set sustratos=".$_SESSION['sustratos']." where id=".$_SESSION['caractivo'];
								$res = pg_query($conn,$sql);
								pg_close($conn);
								refresh();
				}
				if(isset($_POST['borrarconexion']) && isset($_POST['confirmado'])){
								$conn=conecta();
								$sql="delete from conexiones where id = ".$_POST['borrarconexion'];
								$res=pg_query($conn,$sql);
								pg_close($conn);
				}

				if(isset($_SESSION['caractivo']) && $_SESSION['conexiones']){
								//MOSTRAR LA CONEXIONES ESTABLECIDAS
								$conn = conecta();
								$sql="select estadoa,transicion,estadob,id from conexiones where car_id = ".$_SESSION['caractivo'];
								$res = pg_query($conn,$sql);
								if(!$res) echo "ERROR: buscando conexiones establecidas";
								pg_close($conn);
								$filas = pg_num_rows($res);
								if ($filas == 0) echo "<h2>No se han establecido conexiones</h2>";
								else{
												//mostrarlas
												?><h2>Conexiones</h2><?php 
												?><form action="index.php?option=1#fin" method="post"><?php 
												?><table><tr><th>S</th><th>gen</th><th>P</th><th>Borrar</th></tr><?php 
												for($i=0;$i<$filas;$i++){
																$SA=pg_fetch_result($res,$i,0);
																$gen=pg_fetch_result($res,$i,1);
																$SB=pg_fetch_result($res,$i,2);
																$id=pg_fetch_result($res,$i,3);
																?><tr><td>S<?php echo $SA?></td><td><?php echo $gen?></td><td>S<?php echo $SB?></td><?php 
																if(checkowner()){
																				?><td><input type="submit" name="borrarconexion" value="<?php echo $id?>"></input><input type="checkbox" name="confirmado"></input></td><?php 
																}else{
																				?><td></td><?php 
																}
												}
												?></table></form><?php 
								}


								//
								$conn = conecta();
								$sql = "select sustratos from caracteres where id=".$_SESSION['caractivo'];
								$res = pg_query($conn,$sql);
								$_SESSION['sustratos'] = pg_fetch_result($res,0);
								$sql="select gen_id from genes_car where car_id =".$_SESSION['caractivo']." order by gen_id";
								$res = pg_query($conn,$sql);
								$filas = pg_num_rows($res);
?>
<form action="index.php?option=1#fin" method="post">
<?php if(checkowner()){
?><input type="submit" name="cambiarsustratos" value="Cambiar Sustratos">Nº sustratos: </input><input type="text" name="sustratos"></input>
<table><tr>
<?php 
								for($i=0;$i<$_SESSION['sustratos'];$i++){
												?><th>S<?php echo $i?></th><?php 
								}
								?></tr><tr><?php 
								for($i=0;$i<$_SESSION['sustratos'];$i++){
												?><td><input type="radio" name="SA" value="<?php echo $i?>"></input></td><?php 
								}

?>
</tr></table>

<table><tr>
<?php 
								for ($i=0;$i<$filas;$i++){
												$id = pg_fetch_result($res,$i,0);
												$sqlgen="select name from genes where idglobal=".$id;
												$resgen=pg_query($conn,$sqlgen);
												$name=pg_fetch_result($resgen,0);
																?><th><?php echo $name?></th>
<?php 
								}
								?></tr><tr><?php 
								for ($i=0;$i<$filas;$i++){
												$id = pg_fetch_result($res,$i,0);
												?><td><input type="radio" name="transicion"value="<?php echo $id?>"></input></td><?php 

								}
								?></tr></table>
<table><tr>
<?php 
								for($i=0;$i<$_SESSION['sustratos'];$i++){
												?><th>S<?php echo $i?></th><?php 
								}
								?></tr><tr><?php 
								for($i=0;$i<$_SESSION['sustratos'];$i++){
												?><td><input type="radio" name="SB" value="<?php echo $i?>"></input></td><?php 
								}
?>
</tr></table>
<input type="submit" name="conexion" value="Guardar Conexión"></input>
<?php 
}
				}
				
}

function testgen(){
				if(isset($_POST['nuevogen'])){
?>
<form action="index.php?option=1#fin" method="post">
<p>Nombre:<input type="text" name="nombregen"></input></p>
<p>Cromosoma nº:<input type="text" name="chrgen"></input></p>
<p>
X<input type="checkbox" name"X"></input>
Y<input type="checkbox" name"Y"></input>
A<input type="checkbox" name"A" checked></input>
B<input type="checkbox" name"B" checked></input>
</p>
<p>Posición:<input type="text" name="posgen"></input></p>
<input type="submit" name="guardagen" value="Guardar datos"></input></p>
</form>
<?php 
				}
				else if(isset($_POST['guardagen'])){
								//recoge datos post
								$nombregen = $_POST['nombregen'];
								$chrgen = $_POST['chrgen'];
								$posgen = $_POST['posgen'];
								//guardar en BD
								$conn = conecta();
								$sql = "insert into genes (name,chr,pos) values ('".$nombregen."','".$chrgen."','".$posgen."')";
								$res = pg_query($conn,$sql);
								if(!$res) echo "ERROR: No se insertó la información del gen en la BD";
								$gen_id = 0;
								$sql="select last_value from gen_id";
								$res = pg_query($conn,$sql);
								if(!$res) echo "ERROR: No se insertó la información del gen en la BD";
								else $gen_id = pg_fetch_result($res,0,0);
								if ($gen_id != 0){
												$sql="insert into genes_car (gen_id, car_id) values (".$gen_id.", ".$_SESSION['caractivo'].")";
												$res = pg_query($conn,$sql);
												if(!$res) echo "ERROR: No se insertó la información del gen en la BD";
								}
								pg_close($conn);
				}
}


function genbutton($id){
if(checkowner()){
				?><td><input type="submit" name="borrargen" value="<?php echo $id?>"></input><input type="checkbox" name="confirmado"></input></td><?php 
}else{
				?><td></td><?php 
}
?><td><input type="submit" name="abrirgen" value="<?php echo $id?>"></input></td><?php 
}


function alelobutton($id){
				if(checkowner()){
								?><td><input type="submit" name="borraralelo" value="<?php echo $id?>"></input><input type="checkbox" name="confirmado"></input></td><?php 
				}else{
								?><td></td><?php 
				}
}

function testalelo(){
				if(isset($_POST['newalelo'])){
								echo "nuevo alelo";
				}
}



function testabrirgen($id){
				        if(!isset($_SESSION['genactivo'])) $_SESSION['genactivo']=0;
								$conn=conecta();
				if(isset($_POST['abrirgen']) || $_SESSION['genactivo'] != 0){
								if ($_SESSION['genactivo'] == 0) $_SESSION['genactivo']=$id;
								$sql="select name from genes where idglobal=".$_SESSION['genactivo'];
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: gen no encontrado";
								else $_SESSION['genname'] = pg_fetch_result($res,0);
								$sql="select id_alelo from alelos_gen where id_gen=".$_SESSION['genactivo'];
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: alelos no encontrados";
								else{
												$filas = pg_num_rows($res);
?>
				<h2>Alelos del gen: <?php echo $_SESSION['genname']?></h2>
<?php 
												//if($_SESSION['genactivo'] != 0){
//				echo "<input type=\"submit\" name=\"cerrargen\" value=\"Cerrar\"></input>";
//}
?>
<form action="index.php?option=1#fin" method="post">

<input type="submit" name="cerrargen" value="Cerrar"></input>
<br />
<br />

<table>
				<tr><th>Id</th><th>Nombre</th><th>Valor</th><th>Dominancia</th><th>Borrar</th></tr><?php 
												for($i=0;$i<$filas;$i++){
																$id_alelo = pg_fetch_result($res,$i,0);
																$sql = "select * from alelos where id=".$id_alelo;
																$resalelo = pg_query($conn,$sql);
																if(!$resalelo) echo "ERROR: Alelo no encontrado";
																$id = pg_fetch_result($resalelo,0);
																$name = pg_fetch_result($resalelo,1);
																$valor = pg_fetch_result($resalelo,2);
																$dominancia = pg_fetch_result($resalelo,3);
																?><tr><td><?php echo $id?></td><td><?php echo $name?></td><td><?php echo $valor?></td><td><?php echo $dominancia?></td><?php alelobutton($id)?></tr><?php 
												}
												?></table><?php 
												if(checkowner()){
																?><h2>Nuevo Alelo</h2>
																<p>Nombre: <input type="text" name="nombrealelo"></input></p>
																<p>Valor: <input type="text" name="valor"></input></p>
																<h3>Aditivo:
																Sí: <input type="radio" name="aditivo" value="1">
																No: <input type="radio" name="aditivo" value="0" checked></h3>
																<p>Dominancia: <input type="text" name="dominancia"></input></p>
																<p>Epistasis: <input type="text" name="epistasis" value=0></input></p>
																<input type="submit" name="nuevoalelo" value="Nuevo Alelo"></input>
																</form><?php 
												}
								}
				}
				if(isset($_POST['nuevoalelo'])) {
//								echo "pulsado nuevo alelo";
								$name = $_POST['nombrealelo'];
								$valor = $_POST['valor'];
								$dominancia = $_POST['dominancia'];
								$aditivo = $_POST['aditivo'];
								$epistasis = $_POST['epistasis'];
								if ($aditivo == 1) $dominancia = $dominancia+1000;
								if ($epistasis != 0) $dominancia = $dominancia + $epistasis*1000;
								$sql = "insert into alelos (name, valor, dominancia) values ('".$name."','".$valor."','".$dominancia."')";
								$res = pg_query($conn,$sql);
								if(!$res) echo "ERROR: No se insertó la información del gen en la BD";
								$sql="select last_value from alelo_id";
								$res = pg_query($conn,$sql);
								if(!$res) echo "ERROR: No se insertó la información del gen en la BD";
								else $alelo_id = pg_fetch_result($res,0,0);
								if ($alelo_id != 0){
												$sql="insert into alelos_gen (id_gen, id_alelo) values (".$_SESSION['genactivo'].", ".$alelo_id.")";
												$res = pg_query($conn,$sql);
												if(!$res) echo "ERROR: No se insertó la información del gen en la BD";
								refresh();
								}


				}
			
				if(isset($_POST['borraralelo']) && isset($_POST['confirmado'])){
								$id=$_POST['borraralelo'];
								$sql="delete from alelos where id=".$id;
								$res=pg_query($conn,$sql);
								if (!$res) echo "ERROR: No se pudo borrar el carácter"; 
								$sql="delete from alelos_gen where id_gen = ".$_SESSION['genactivo']." and id_alelo = ".$id;
								$res=pg_query($conn,$sql);
								if (!$res) echo "ERROR: No se pudo borrar el carácter"; 
								refresh();
				}
				if(isset($_POST['borrargen']) && isset($_POST['confirmado'])){
								$id=$_POST['borrargen'];
								$sql="delete from genes where idglobal=".$id;
								$res=pg_query($conn,$sql);
								if (!$res) echo "ERROR: borrar genes genes";
								$sql = "delete from genes_car where gen_id =".$id;
							  $res = pg_query($conn,$sql);
								if (!$res) echo "ERROR: borrar genes_car";
								$sql="select distinct id_alelo from alelos_gen where id_gen=".$id;
								$res=pg_query($conn,$sql);
								if (!$res) echo "ERROR: select alelos"; 
								else{
												$filas = pg_num_rows($res);
												for ($i=0;$i<$filas;$i++){
																$idborrar = pg_fetch_result($res,$i,0);
																$sql="delete from alelos where id=".$idborrar;
																$resalelos = pg_query($conn,$sql);
																if (!$resalelos) echo "ERROR: borra alelos";
																$sql = "delete from alelos_gen where id_gen =".$id;
																$resalelosgen = pg_query($conn,$sql);
																if (!$resalelosgen) echo "ERROR: borra alelos_gen";
												}

								}
								$_SESSION['genactivo'] = 0;
								$_SESSION['genname'] = "";
								refresh();
				}
				if(isset($_POST['cerrargen'])){
								$_SESSION['genactivo'] = 0;
								$_SESSION['genname'] = "";
								refresh();
				}
				pg_close($conn);
}
