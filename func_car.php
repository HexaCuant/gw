<?
function listacar($proyecto_id)
{
 $conn = conecta();
 //$sql="select distinct caracter_id from caracteres_proy where proyecto_id =".$proyecto_id;
 $sql="select id,name from caracteres";
 echo $sql;
 $res=pg_query($conn,$sql);
 $rows=pg_NumRows($res);
?>
<form action="index.php?option=1" method="post">
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
 desconecta($conn);
 //formnewcar();
}

function formnewcar()
{
 ?>
 <form action="index.php?option=1" method="post">
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
				desconecta($conn);
				refresh();
}

function testcar($id){
				if(isset($_POST['abrircar'])){
								$_SESSION['caractivo']=$id;
								$conn=conecta();
								$sql="select name,public,visible,sexo,ambiente from caracteres where id=".$id;
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: carácter no encontrado";
								else{
												$_SESSION['name_caractivo'] = pg_fetch_result($res,0,0);
												$public =  pg_fetch_result($res,0,1);
												$visible =  pg_fetch_result($res,0,2);
												$sexo =  pg_fetch_result($res,0,3);
												$ambiente =  pg_fetch_result($res,0,4);
								}
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
								if(!$res) echo "Error: carácter no encontrado";
								desconecta($conn);
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
								desconecta($conn);
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
			<form action="index.php?option=1" method="post">
			<p>Sexo: <input type="checkbox" name="sexo"  <?if($sexo == "t") print("checked")?>></input></p>
			<p>Visible: <input type="checkbox" name="visible" <?if($visible == "t") print("checked")?>></input>
			Público: <input type="checkbox" name="public" <?if($public == "t") print("checked")?>></input>
			</p>
			<p>Ambiente: <input type="text" name="ambiente" value="<?=$ambiente?>"></input></p>
      <input type="submit" value="Guardar Cambios" name="datoscar"></input><input type="submit" value="Cerrar" name="cerrarcar"></input></p>
			</form>

      <form action=index.php?option=1" method="post">
			<p><input type="submit" value="Ver Genes" name="vergenes"></input></p>
			<p><input type="submit" value="Nuevo gen" name="nuevogen"></input></p>
      </form>

</div><?
}

function testvergenes(){
				if(isset($_POST['vergenes'])){
								$conn = conecta();
								$sql="select gen_id from genes_car where car_id =".$_SESSION['caractivo'];
								$res = pg_query($conn,$sql);
								$filas = pg_num_rows($res);
?><table>
				<tr><th>Id</th><th>Nombre</th><th>chr</th><th>pos</th><th>cod</th></tr><?
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
				<tr><td><?=$idglobal?></td><td><?=$name?></td><td><?=$chr?></td><td><?=$pos?></td><td><?=$code?></td></tr>
<?
																
								}
?></table><?
								desconecta($conn);
				}
}


function testgen(){
				if(isset($_POST['nuevogen'])){
?>
<form action="index.php?option=1" method="post">
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
								desconecta($conn);
				}
}

function testalelo(){
				if(isset($_POST['newalelo'])){
								echo "nuevo alelo";
				}
}

