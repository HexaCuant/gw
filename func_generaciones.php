<?

function formnewrandom(){
?><form action="index.php?option=3#fin" method="post">
Tamaño de población: <input type = "text" name="pop"></input>
<input type="submit" name="newrandom" value="Crear Generación"></input><?
}

function testnewrandom(){
				if(isset($_POST['newrandom'])){
								$pop=$_POST['pop'];
								echo "Población=".$pop;
								makepoc($pop);
				}
}


function makepoc($pop){
				$path="/var/www/proyectos/".$_SESSION['proactivo']."/".$_SESSION['proactivo'].".poc";
				$fh = fopen($path,"w");
        $line = "#file created by GenWeb\n";
				fwrite($fh,$line);
				$line = "n".$pop."\n";
				fwrite($fh,$line);
				//generacion max
				$sql="select max(generacion_id) from generaciones_proy where proy_id = ".$_SESSION['proactivo'];
				$conn = conecta();
				$res = pg_query($conn,$sql);
				$generacion_id = pg_fetch_result($res,0);
				$generacion_id++;
				//////
				$line = "i".$generacion_id."\n";
				fwrite($fh,$line);
				$line="*characters\n";
				fwrite($fh,$line);
				//bucle caracteres
				$sql = "select caracter_id,ambiente from caracteres_proy where proyecto_id = ".$_SESSION['proactivo'];
				$res=pg_query($conn,$sql);
				$filas = pg_num_rows($res);
				for ($i=0;$i<$filas;$i++){
								//bucle de caracteres
								$id=pg_fetch_result($res,$i,0);
								$ambiente=pg_fetch_result($res,$i,1);
								$sqlamb = "select sexo from caracteres where id = ".$id;
								$resamb=pg_query($conn,$sqlamb);
								$sexo=pg_fetch_result($resamb,0);
								$line=$id.":".$ambiente.":";
								if ($sexo=="t") $line=$line."0:";
								fwrite($fh,$line);
								//genes dentro de cada caracter

				}
}
