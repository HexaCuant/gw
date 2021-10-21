<?php 
function listapro($id)
{
  $conn = conecta();
  $sql="select * from proyectos where userid =".$id;
  $res=pg_query($conn,$sql);
  $rows=pg_NumRows($res);
?>
<form action="index.php?option=2" method="post">
 <table>
 <tr><th>Id</th><th>Nombre Proyecto</th><th>Borrar</th><th>Abrir</th></tr>
<?php 
  for ($i=0;$i<$rows;$i++)
  {
    $id = pg_fetch_result($res,$i,0);
    $proname = pg_fetch_result($res,$i,1);
    ?><tr><td><?php echo $id?></td><td><?php echo $proname?></td><?php 
    probutton($id);
    ?></tr><?php 
  }
?>
 </table>
</form>
<?php 
  pg_close($conn);
}

function probutton($id)
{
?>
        <td><input type="submit" name="borrarpro" value="<?php echo $id?>"></input><input type="checkbox" name="confirmado"></input></td>
        <td><input type="submit" name="abrirpro" value="<?php echo $id?>"</td> 
<?php 
}

function formnewpro()
{
?>
 <form action="index.php?option=2" method="post">
 <p>Nombre:<input type="text" name="proname"></input></p>
 <p><input type="submit" value="Nuevo Proyecto" name="newpro" /><input type="reset" value="borrar" /></p>
 </form>
<?php 
}


function formcierrapro()
{
  $conn = conecta();
  $sql = "select proname from proyectos where id=".$_SESSION['proactivo'];
  $res = pg_query($conn,$sql);
  if(!$res) echo "ERROR: (func:209) No se encontró el proyecto";
  else
  {
    $proname = pg_fetch_result($res,0,0);
  }
?>
 <form action="index.php?option=2" method="post">
 <p><input type="submit" value="Cerrar Proyecto" name="cerrarpro" />
 </form>
<?php 
}

function testcierrapro()
{
  if(isset($_POST['cerrarpro']))
  {
    $_SESSION['proactivo']=0;
    unset($_SESSION['proactivo']);
    $_SESSION['proname']="";
    //	refresh();
  }
}


function	insertpro($proname)
{
  $conn = conecta();
  $sql="insert into proyectos (proname,userid) values ('".$proname."','".$_SESSION['id']."')";
  $res = pg_query($conn,$sql);
  if(!$res) echo "Error en la inserción del proyecto";
  pg_close($conn);
  createdir();
  refresh();
}

function createdir(){
  $sql = "select last_value from proyecto_id";
  $conn = conecta();
  $res = pg_query($conn,$sql);
  $id = pg_fetch_result($res,0,0);
  pg_close($conn);
  $path = "/var/www/proyectosGengine/".$id;
  mkdir($path);
}

function testborrarpro()
{
  if (isset($_POST['borrarpro']) && isset($_POST['confirmado']))
  {
    $id=$_POST['borrarpro'];
    $sql = "delete from proyectos where id=".$id;
    $conn=conecta();
    pg_query($conn,$sql);
    pg_close($conn);
    $_SESSION['proactivo']=0;
    unset($_SESSION['proactivo']);
    $_SESSION['proname']="";
    unset($_SESSION['proname']);

    refresh();
  }
}

function testabrepro(){
  if (isset($_POST['abrirpro'])){
    $_SESSION['proactivo'] = $_POST['abrirpro'];
    $proyecto_id = $_POST['abrirpro'];
    $path = "/var/www/proyectosGengine/".$proyecto_id."/".$proyecto_id."stats.json";
    $_SESSION['stats'] = json_decode(file_get_contents($path),true);
  }
  if(isset($_SESSION['proactivo'])){
    $conn = conecta();
    $sql = "select proname from proyectos where id=".$_SESSION['proactivo'];
    $res = pg_query($conn,$sql);
    $_SESSION['proname'] = pg_fetch_result($res,0,0);
    pg_close($conn);
  }
}
