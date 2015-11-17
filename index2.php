<html>
	 <head>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" href="uniweb.css" media="all" />
probando
			<?php
				 include("funciones.inc");
				 error_reporting(E_ALL);
				 ini_set('display_errors', '1');
				 $salt="63·&%=.*=(&€";
			?>
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
			<script type="text/javascript" src="scripts.js"></script>
			<script>
</script>


</head>
<body>
	 <h1>Máster en Genética y Evolución</h1>
		<!--	 <p><a href="asignaciones.php">asignaciones tfm</a></p>-->
		<p><a href="horarios.php">horarios</a></p>
	 <div id="logo">
	 </div>
	 <?php
			//idenficar usuario
			//if($_SESSION['id']){
			?><h3 id="ident">Identificado como: Sin identificar</h3>
			<script>
				 $("#ident").html("Identificado como: ");
				 setident("<?php echo $_SESSION['activeuser']?>");
			</script>

			<?php
				 //}
				 //qué hacer al pulsar cada botón
				// echo "sesion-principio:".$_SESSION['id'];
				 if(isset($_POST['login'])){ //boton login
				// echo "en login";
				 $mail=$_POST['user'];
				 $password=md5($salt.$_POST['password'].$salt);
				// echo $mail."<br/>";
				// echo $password."<br/>";
				 $sql="select id,password from alumnos where mail='".$mail."'";
				 //echo $sql;
				 $conn=conecta();
				 $res = $conn->query($sql);
				 $resarray =$res->fetch(PDO::FETCH_ASSOC);
				 $id=$resarray['id'];
				 $test=$resarray['password'];
//				 echo "id:".$id."----test:".$test."-----filas:".$filas;
				 if ($test==$password){
						$_SESSION['id']=$id;
						$_SESSION['activeuser'] = $mail;
				 ?>
				 <script>
						$("#ident").html("Identificado como: ");
						setident("<?php echo $_SESSION['activeuser']?>");
				 </script>
				 <?php
				 }
			}
			else if(isset($_POST['salir'])){ //boton salir
			//echo "en salir";
			unset($_SESSION['id']);
			unset($_SESSION['activeuser']);
	 ?><script>setident("Sin identificar");</script><?php
}
else if(isset($_POST['alta'])){ //boton alta
$mail=$_POST['user'];
$conn=conecta();
$sql="select count(id) from alumnos where mail='".$mail."' and password=''";
$res=$conn->query($sql);
$filas=num_rows($conn,$sql);

if($filas != 1){
?><script>alert("No autorizado");</script><?php
								}else{
									 $sql="select id from alumnos where mail='".$mail."'";
									 $res = $conn->query($sql);
									 $id=$res->fetchColumn();
									 $password=md5($salt.$_POST['password'].$salt);
									 $sql="update alumnos set password='".$password."' where id='".$id."'";
									 //echo $sql;
									 $res=$conn->exec($sql);
									 //echo "filas afectadas: ".$res;
								?><script>alert("Alta correcta\nPuede ingresar con sus nuevas cedenciales")</script><?php
						 }
					}
			 ?>


			 <?php
					if(isset($_SESSION['id'])){ //si está identificado muestra toda la página
					if($_SESSION['id']!=0){
						 //echo "sesion:".$_SESSION['id'];
					?>
					<input type="hidden" id="userid" value="<?php echo $_SESSION['id']?>"/>
					<form method="post" action="index.php" name="lineas">
						 <input type="submit" name="salir" value="salir"/>
					</form>
					<h2>Selección de líneas para TFM</h2>
					<p>Según la normativa vigente, los Trabajos Fin de Máster (TFM) serán dirigidos por norma general por un único tutor, aunque podrán excepcionalente ser dirigidos por dos tutores si existe suficiente justificación para ello.</p>

					<p>Si has contactado con algún Tutor con el que vas a realizar tu Trabajo Fin de Máster (TFM) en una línea que no está incluída en la lista, Puedes dar de alta la línea de Investigación, y elegir al tutor de la lista desplegable. Después pulsa el boton "Registrar". La línea aparecerá en tu lista de líneas seleccionadas.</p> 
					

					<p>Si además, tu tutor es externo al máster y no se encuentra en la lista de tutores, deja la opción "no listado" en el campo "Tutor" y rellena sus datos en el apartado "Nuevo Tutor" antes de pulsar en "registrar". </p>
					<p>En el caso en que tengas dos tutores, ya que en este momento solo es necesaria la elección de las líneas, en caso de dar de alta a un tutor externo no incluído en la lista de tutores solo es necesario dar de alta al tutor principal. Más adelante se incluirá también al segundo tutor en caso de que así se acuerde.</p>

					<p>Si vas a realizar tu TFM en una de las líneas listadas y ya has contactado con algún tutor para que te dirija tu proyecto, busca la línea en esta misma página y pulsa sobre el cuadrado rojo con el número que identifica la línea. Aparecerá una lista de posibles tutores para esa línea. Comprueba que tu tutor está en ella, y pulsa sobre el botón rojo "Seleccionar". La línea aparecerá en tu lista de líneas seleccionadas.</p>

					<p>Si no has contactado aún con ninguno de los posibles tutores, puedes elegir hasta un máximo de tres líneas siguiendo ese mismo procedimiento (pulsar sobre el número de la línea y sobre el botón "Seleccionar". Las líneas elegidas tendrán un orden de prioridad que puedes modificar pulsando sobre los botones "+prioridad" o "-prioridad" junto a cada línea seleccionada. En cualquier momento puedes modificar las líneas elegidas pulsando sobre el botón "borrar" o seleccionando líneas nuevas.</p>

					<fieldset class="yatengo">
						 <legend>Ya tengo una línea no incluída en la lista</legend>
						 Línea: <input type="text" id="nuevaLinea"></input><br/>
						 Tutor: <select id="yatengoTutor">
								<option value='0' selected>No listado</option>
								<?php
									 $sql="select apellidos,nombre,id from tutores order by apellidos";
									 $conn=conecta();
									 $res=$conn->query($sql);
									 $tutoresArray=$res->fetchall();
									 foreach($tutoresArray as $tutor){
											echo "<option value='".$tutor['id']."'> ".$tutor['apellidos'].", ".$tutor['nombre']."</option>";
									 }
								?>
						 </select>
							<br/><h4>Nuevo Tutor:</h4>
						 Apellidos: <input type="text" id="nuevoTutorApellidos"></input><br/>
						 Nombre: <input type="text" id="nuevoTutorNombre"></input><br/>
						 Centro: <input type="text" id="nuevoTutorCentro"></input><br/>
						 <button id="nuevaLinea" onclick="tengoLinea('<?php echo $_SESSION['id']?>')">Registrar</button>
					</fieldset>
					<br/>
					<fieldset class="frame">
						 <legend>Líneas seleccionadas</legend>
						 <div id="seleccionadas">
								<?php lineasSeleccionadas($_SESSION['id']) ?>
						 </div>
					</fieldset>
					<?php
						 //selecciona las líneas de ls BD
						 $sql="select * from lineas";
						 $conn = conecta();
						 $res=$conn->query($sql);
						 $lineasArray=$res->fetchall();

						 $sql="select count(*) from lineas";
						 $resNum=$conn->query($sql);
						 $filas=$resNum->fetchColumn();
					?><ul><?php
						 for($i=0;$i<$filas;$i++){
								if($i==0) echo "<h2>Módulo Biosanitario</h2>";
								if($i==11) echo "<h2>Módulo Biosanitario-Evolutivo</h2>";
								if($i==18) echo "<h2>Módulo Agroalimentario-Evolutivo</h2>";
								if($i==31) echo "<h2>Módulo Evolutivo</h2>";
								if($i==48) echo "<h2>Líneas Adicionales</h2>";
								$id=$lineasArray[$i]['id'];
								$name=$lineasArray[$i]['name'];
						 ?><li id="li<?php echo $id?>"><button class="num" id="<?php echo $id?>"><?php echo $id?></button><?php echo $name?></li><?php
						 $sql_tutores_id="select id_tutor from tutores_lineas where id_linea='".$id."'";
						 $res_tutores=$conn->query($sql_tutores_id);
						 $tutoresArray=$res_tutores->fetchall();
						 $sql_num_tutores = "select count(id_tutor) from tutores_lineas where id_linea='".$id."'";
						 $res_num_tutores = $conn->query($sql_num_tutores);
						 $filas_tutores=$res_num_tutores->fetchcolumn();
					?><div id="tutores<?php echo $id?>" hidden><ul>

								<button class="sel" id="<?php echo $id?>">Seleccionar</button>

								<?php
									 //para cada línea selecciona los tutores
									 for($t=0;$t<$filas_tutores;$t++){
											$id_tutor=$tutoresArray[$t]['id_tutor'];
											$sql_who="select nombre,apellidos,centro from tutores where id='".$id_tutor."'";
											$res_who=$conn->query($sql_who);
											$whoArray=$res_who->fetchall();
											//			print_r($whoArray);
											$nombre=$whoArray[0]['nombre'];
											$apellidos=$whoArray[0]['apellidos'];
											$centro=$whoArray[0]['centro'];
									 ?><li><?php echo $nombre?>, <?php echo $apellidos?> (<b><?php echo $centro?></b>)</li><?php
								}
						 ?>
			 </ul></div>
			 <?php
			 }
		?></ul>
</form>
<?php
}//fin session
}//fin sesion
else{ //si no estas identificado
?>
<div class="centrado">
	 <div class="login">
			<h2>Login</h2>
			<form method="post" action="index.php" name="loginform" onsubmit="return validar()">
				 <table>
						<tr><td>usuario:</td><td><input  type="tex" name="user" id="user" onblur="checkuser()"/></td</tr>
							 <tr><td>contraseña</td><td><input type="password" name="password" id="password"> </td></tr>
						</table>
						<input type="submit" name="login" value="Entrar"/>
						<input type="submit" name="alta" id="alta" value="Nuevo Usuario"/>
				 </form>
			</div>
	 </div>
	 <?php
	 }
?>
</body>
</html>
