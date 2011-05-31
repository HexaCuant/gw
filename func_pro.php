<?
function listapro($id)
{
 $conn = conecta();
 $sql="select * from proyectos where userid =".$id;
 echo $sql;
 $res=pg_query($conn,$sql);
 $rows=pg_NumRows($res);
?>
<form action="index.php?option=2" method="post">
 <table>
 <tr><th>Id</th><th>Nombre Proyecto</th><th>Borrar</th><th>Abrir</th></tr>
 <?
 for ($i=0;$i<$rows;$i++)
 {
  $id = pg_result($res,$i,0);
  $proname = pg_result($res,$i,1);
	?><tr><td><?=$id?></td><td><?=$proname?></td><?
  probutton($id);
	?></tr><?
 }
 ?>
 </table>
</form>
 <?
 desconecta($conn);
}

function probutton($id)
{
?>
				<td><input type="submit" name="borrarpro" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
				<td><input type="submit" name="abrirpro" value="<?=$id?>"</td> 
<?
}

function formnewpro()
{
 ?>
 <form action="index.php?option=2" method="post">
 <p>Nombre:<input type="text" name="proname"></input></p>
 <p><input type="submit" value="Nuevo Proyecto" name="newpro" /><input type="reset" value="borrar" /></p>
 </form>
 <?
}


function formcierrapro()
{
 $conn = conecta();
 $sql = "select proname from proyectos where id=".$_SESSION['proactivo'];
 $res = pg_query($conn,$sql);
 if(!$res) echo "ERROR: (func:209) No se encontró el proyecto";
 else
 {
	 $proname = pg_result($res,0);
 }
?>
 <form action="index.php?option=2" method="post">
 <p><input type="submit" value="Cerrar Proyecto" name="cerrarpro" />
 </form>
<?
}

function testcierrapro()
{
 if(isset($_POST['cerrarpro']))
 {
//	$_SESSION['proactivo']=0;
  unset($_SESSION['proactivo']);
 }
}


function	insertpro($proname)
{
				$conn = conecta();
				$sql="insert into proyectos (proname,userid) values ('".$proname."','".$_SESSION['userid']."')";
				$res = pg_query($conn,$sql);
				if(!$res) echo "Error en la inserción del proyecto";
				desconecta($conn);
				refresh();
}



function testborrarpro()
{
 if (isset($_POST['borrarpro']) && isset($_POST['confirmado']))
 {
 	$id=$_POST['borrarpro'];
	$sql = "delete from proyectos where id=".$id;
	$conn=conecta();
	pg_query($conn,$sql);
	desconecta($conn);
	refresh();
 }
}

function testabrepro()
{
 if (isset($_POST['abrirpro']))
 {
				 $proyecto_id = $_POST['abrirpro'];
				 $_SESSION['proactivo']=$proyecto_id;
				 listacar($proyecto_id);
 }
}
