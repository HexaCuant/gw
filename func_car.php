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
  $id = pg_result($res,$i,0);
  $name = pg_result($res,$i,1);
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
								$sql="select name from caracteres where id=".$id;
								$res = pg_query($conn,$sql);
								if(!$res) echo "Error: carácter no encontrado";
								else $_SESSION['name_caractivo'] = pg_result($res,0,0);
				}else{
								if(isset($_POST['cerrarcar'])){
												unset($_SESSION['caractivo']);
												unset($_SESSION['name_caractivo']);
								}
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

?>
			<h2>Carácter:<?=$_SESSION['name_caractivo']?></h2>
      <form action="index.php?option=1" method="post">
			<p>Sexo: <input type="checkbox" name="sexo"></input></p>
			<p>Ambiente: <input type="text" name="ambiente"></input></p>
      <input type="submit" value="Guardar Cambios" name="datosgen"></input><input type="submit" value="Cerrar" name="cerrarcar"></input></p>
			</form>

      <form action=index.php?option=1" method="post">
			<p><input type="submit" value="Nuevo gen" name="nuevogen"></input></p>
      </form>

</div><?
}

function testgen(){
				if(isset($_POST['nuevogen'])){
?>
<?echo "pulsado nuevo gen";?>
<form action="index.php?option=1" method="post">
<p>nombre:<input type="text" name="nombregen"></input></p>
<p>Cromosoma:<input type="text" name="chrgen"></input></p>
<p>Posición:<input type="text" name="chrgen"></input></p>

</form>
<?
				}

}

