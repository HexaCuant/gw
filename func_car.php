<?
function listacar($proyecto_id)
{
 $conn = conecta();
 //$sql="select distinct caracter_id from caracteres_proy where proyecto_id =".$proyecto_id;
 $sql="select id,name from caracteres order by id";
 echo $sql;
 $res=pg_query($conn,$sql);
 $rows=pg_NumRows($res);
?>
<form action="index.php?option=1#fin" method="post">
 <table>
 <tr><th>Id</th><th>Carácter</th><th>Borrar</th><th>Abrir</th></tr>
 <?
 for ($i=0;$i<$rows;$i++)
 {
  $id = pg_fetch_result($res,$i,0);
  $name = pg_fetch_result($res,$i,1);
	?><tr><td><?=$id?></td><td><?=$name?></td><?
  carbutton($id);
	?></tr><?
 }
 ?>
 </table>
</form>
 <?
 pg_close($conn);
 //formnewcar();
}

function formnewcar()
{
 ?>
 <form action="index.php?option=1#fin" method="post">
 <p>Nombre:<input type="text" name="carname"></input></p>
<p>Visible:<input type="checkbox" name="visible"></input>
Público:<input type="checkbox" name="publico"></input></p>
 <p><input type="submit" value="Nuevo Carácter" name="newcar" /><input type="reset" value="borrar" /></p>
 </form>
 <?
}

function	insertcar($carname)
{
				$conn = conecta();
				if (isset($_POST['visible'])) $visible = "t"; else $visible="f";
				if (isset($_POST['publico'])) $publico = "t"; else $publico="f";
				$sql="insert into caracteres (name,creatorid,public,visible) values ('".$carname."','".$_SESSION['userid']."','".$publico."','".$visible."')";
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
				}
				else if(isset($_POST['borrarcar']) && isset($_POST['confirmado'])){
								$id=$_POST['borrarcar'];
								$sql="delete from caracteres where id=".$id;
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
								$sql = "update caracteres set visible='".$visible."', public='".$public."', sexo='".$sexo."', ambiente ='".$ambiente."' where id = ".$_SESSION['caractivo'];
								$res = pg_query($conn,$sql);
								pg_close($conn);
								if(!$res) echo "Error: carácter no encontrado";
				}
				if(isset($_SESSION['caractivo'])) datoscar($_SESSION['caractivo']);
}

function carbutton($id){
?>
<td><input type="submit" name="borrarcar" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
<td><input type="submit" name="abrircar" value="<?=$id?>"</td> 
<?
}



function datoscar($car_id){
				        $conn = conecta();
								$sql="select name,public,visible,sexo,ambiente from caracteres where id=".$car_id;
								$res = pg_query($conn,$sql);
								pg_close($conn);
								if(!$res) echo "Error: carácter no encontrado";
								else{
												$_SESSION['name_caractivo'] = pg_fetch_result($res,0,0);
												$public =  pg_fetch_result($res,0,1);
												$visible =  pg_fetch_result($res,0,2);
												$sexo =  pg_fetch_result($res,0,3);
												$ambiente =  pg_fetch_result($res,0,4);
								}
?>
			<h2>Carácter:<?=$_SESSION['name_caractivo']?></h2>
			<form action="index.php?option=1#fin" method="post">
			<p>Sexo: <input type="checkbox" name="sexo"  <?if($sexo == "t") print("checked")?>></input></p>
			<p>Visible: <input type="checkbox" name="visible" <?if($visible == "t") print("checked")?>></input>
			Público: <input type="checkbox" name="public" <?if($public == "t") print("checked")?>></input>
			</p>
			<p>Ambiente: <input type="text" name="ambiente" value="<?=$ambiente?>"></input></p>
      <input type="submit" value="Guardar Cambios" name="datoscar"></input><input type="submit" value="Cerrar" name="cerrarcar"></input></p>

<?
								if($_SESSION['vergenes']){
?>
			<p><input type="submit" value="Ocultar Genes" name="ocultargenes"></input></p><?
								}
								else{
?>
												<p><input type="submit" value="Ver Genes" name="vergenes"></input></p><?
								}?>
			<p><input type="submit" value="Nuevo gen" name="nuevogen"></input></p>
      </form>

</div><?
}

function testvergenes(){
				if(isset($_SESSION['caractivo'])){
				if(isset($_POST['vergenes']) || $_SESSION['vergenes']){
								$_SESSION['vergenes']=TRUE;
								$conn = conecta();
								$sql="select gen_id from genes_car where car_id =".$_SESSION['caractivo']." order by gen_id";
								echo $sql;
								$res = pg_query($conn,$sql);
								$filas = pg_num_rows($res);
?>
<form action="index.php?option=1#fin" method="post">
<table>
				<tr><th>Id</th><th>Nombre</th><th>chr</th><th>pos</th><th>cod</th><th>Borrar</th><th>Abrir</th></tr><?
								for ($i=0;$i<$filas;$i++){
												$gen_id = pg_fetch_result($res,$i,0);
												$sql = "select * from genes where idglobal =".$gen_id;
												$resgen = pg_query($conn,$sql);
												if(!$resgen) echo "ERROR: No se insertó la información del gen en la BD";
												$idglobal=pg_fetch_result($resgen,0);
												$name=pg_fetch_result($resgen,2);
												$chr=pg_fetch_result($resgen,3);
												$pos=pg_fetch_result($resgen,4);
												$code=pg_fetch_result($resgen,5);
?>
				<tr><td><?=$idglobal?></td><td><?=$name?></td><td><?=$chr?></td><td><?=$pos?></td><td><?=$code?></td>
<?genbutton($idglobal)?>
</tr>
<?
								}
?></table>

				</form><?
				pg_close($conn);
				}
				}
				if(isset($_POST['ocultargenes'])){
								$_SESSION['vergenes']=FALSE;
								echo "ocultar";
								refresh();
				}
				if(isset($_POST['vergenes'])){
								echo "ver";
								refresh();
				}
//				if(isset($_POST['cerrargen'])){
//								$_SESSION['genactivo'] = 0;
//								$_SESSION['genname'] = "";
//								refresh();
//				}
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
<?
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
?>
<td><input type="submit" name="borrargen" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
<td><input type="submit" name="abrirgen" value="<?=$id?>"</td> 
<?
}


function alelobutton($id){
?>
<td><input type="submit" name="borraralelo" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
<?
}

function testalelo(){
				if(isset($_POST['newalelo'])){
								echo "nuevo alelo";
				}
}



function testabrirgen($id){
				if(isset($_POST['abrirgen']) || $_SESSION['genactivo'] != 0){
								if ($_SESSION['genactivo'] == 0) $_SESSION['genactivo']=$id;
								$conn=conecta();
								$sql="select name from genes where idglobal=".$_SESSION['genactivo'];
								echo $sql;
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: gen no encontrado";
								else $_SESSION['genname'] = pg_fetch_result($res,0);
								$sql="select id_alelo from alelos_gen where id_gen=".$_SESSION['genactivo'];
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: alelos no encontrados";
								else{
												$filas = pg_num_rows($res);
?>
				<h2>Gen: <?=$_SESSION['genname']?></h2>
<?
												//if($_SESSION['genactivo'] != 0){
//				echo "<input type=\"submit\" name=\"cerrargen\" value=\"Cerrar\"></input>";
//}
?>
<form action="index.php?option=1#fin" method="post">

<input type="submit" name="cerrargen" value="Cerrar"></input>
<br />
<br />

<table>
				<tr><th>Id</th><th>Nombre</th><th>Valor</th><th>Dominancia</th><th>Borrar</th></tr><?
												for($i=0;$i<$filas;$i++){
																$id_alelo = pg_fetch_result($res,$i,0);
																$sql = "select * from alelos where id=".$id_alelo;
																$resalelo = pg_query($conn,$sql);
																if(!$resalelo) echo "ERROR: Alelo no encontrado";
																$id = pg_fetch_result($resalelo,0);
																$name = pg_fetch_result($resalelo,1);
																$valor = pg_fetch_result($resalelo,2);
																$dominancia = pg_fetch_result($resalelo,3);
?>
				<tr><td><?=$id?></td><td><?=$name?></td><td><?=$valor?></td><td><?=$dominancia?></td>
<?alelobutton($id)?>
</tr>
<?
												}
								pg_close($conn);
												?></table>

<h2>Nuevo Alelo</h2>
<p>Nombre: <input type="text" name="nombrealelo"></input></p>
<p>Valor: <input type="text" name="valor"></input></p>
<p>Dominancia: <input type="text" name="dominancia"></input></p>
<input type="submit" name="nuevoalelo" value="Nuevo Alelo"></input>
																
																</form><?
								}
								echo "nuevo:".$_POST['nuevoalelo'];
				}
				if(isset($_POST['nuevoalelo'])) {
								echo "pulsado nuevo alelo";
								$name = $_POST['nombrealelo'];
								$valor = $_POST['valor'];
								$dominancia = $_POST['dominancia'];
								$conn = conecta();
								$sql = "insert into alelos (name, valor, dominancia) values ('".$name."','".$valor."','".$dominancia."')";
								echo $sql;
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
								}
								pg_close($conn);
								refresh();


				}
				if(isset($_POST['borraralelo']) && isset($_POST['confirmado'])){
								$id=$_POST['borraralelo'];
								$conn=conecta();
								$sql="delete from alelos where id=".$id;
								$res=pg_query($conn,$sql);
								if (!$res) echo "ERROR: No se pudo borrar el carácter"; 
								$sql="delete from alelos_gen where id_gen = ".$_SESSION['genactivo']." and id_alelo = ".$id;
								$res=pg_query($conn,$sql);
								if (!$res) echo "ERROR: No se pudo borrar el carácter"; 
												pg_close($conn);
								refresh();
				}
				if(isset($_POST['cerrargen'])){
								$_SESSION['genactivo'] = 0;
								$_SESSION['genname'] = "";
								refresh();
				}
}
