<?

function formnewrandom(){
				//generacion max
				$sql="select max(generacion_id) from generaciones_proy where proy_id = ".$_SESSION['proactivo'];
				$conn = conecta();
				$res = pg_query($conn,$sql);
				$generacion_id = pg_fetch_result($res,0);
				$generacion_id++;
				pg_close($conn);
				//////
				?><form action="index.php?option=3#fin" method="post">
				Tamaño de población: <input type = "text" name="pop"></input><br /><br />
				Generación num. <input type="text" name="generacion_id" value="<?=$generacion_id?>"></input /><br /><br />
				<input type="submit" name="newrandom" value="Crear Generación"></input><br /><br />
<?
				if($_SESSION['vergeneraciones']){
								?><input type="submit" name="ocultargeneraciones" value="Ocultar Generaciones"></input><?
				}
				else{
								?><input type="submit" name="vergeneraciones" value="Ver Generaciones"></input><?
				}
}

function testnewrandom(){
				if(isset($_POST['newrandom'])){
								$pop=$_POST['pop'];
								if($pop>0){
												$gen=$_POST['generacion_id'];
												echo "Generacion=".$gen;
												makepoc($pop,$gen);
								}
				}
				if(isset($_POST['vergeneraciones'])){
								$_SESSION['vergeneraciones'] = TRUE;
								refresh();
				}
				if(isset($_POST['ocultargeneraciones'])){
								$_SESSION['vergeneraciones'] = FALSE;
								refresh();
				}
}

function testlistgeneraciones(){
				if($_SESSION['vergeneraciones']){
								echo "listar las generaciones";
								$conn = conecta();
								$sql="select distinct(generacion_id) from generaciones_proy where proy_id = ".$_SESSION['proactivo']." order by generacion_id";
								$res = pg_query($conn,$sql);
								$filas = pg_num_rows($res);
								?><br /><br /><table><?
								for($i=0;$i<$filas;$i++){
												$id=pg_fetch_result($res,$i,0);
												?><tr><th><a href="../proyectos/<?=$_SESSION['proactivo']?>/<?=$_SESSION['proactivo']?>.dat<?=$id?>">Generación <?=$id?></a></th></tr><?
												echo "\n";
								}
								?></table><?
								pg_close($conn);
				}
}


function makepoc($pop,$gen){
				$path="/var/www/proyectos/".$_SESSION['proactivo']."/".$_SESSION['proactivo'].".poc";
				$fh = fopen($path,"w");
        $line = "#file created by GenWeb\n";
				fwrite($fh,$line);
				$line = "n".$pop."\n";
				fwrite($fh,$line);
				$line = "i".$gen."\n";
				fwrite($fh,$line);
				$line="*characters\n";
				fwrite($fh,$line);
				//bucle caracteres
				$conn = conecta();
				$sql = "select caracter_id,ambiente from caracteres_proy where proyecto_id = ".$_SESSION['proactivo']." order by caracter_id";
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
								$sqlgen = "select gen_id from genes_car where car_id=".$id." order by gen_id";
								$resgen = pg_query($conn,$sqlgen);
								$filasgen = pg_num_rows($resgen);
								for($j=0;$j<$filasgen;$j++){
												//bucle de genes
												$idgen = pg_fetch_result($resgen,$j,0);
												$sqldatosgen = "select chr,pos,cod from genes where idglobal = ".$idgen;
												$resdatosgen = pg_query($conn,$sqldatosgen);
												$chr=pg_fetch_result($resdatosgen,0);
												$pos=pg_fetch_result($resdatosgen,1);
												$cod=pg_fetch_result($resdatosgen,2);
												$line="\n".$idgen."=".$chr.":".$pos.":".$cod.":";
												fwrite($fh,$line);
												//bucle alelos de cada gen
												$sqlalelos = "select id_alelo from alelos_gen where id_gen=".$idgen." order by id_alelo";
												$resalelos = pg_query($conn,$sqlalelos);
												$filasalelos = pg_num_rows($resalelos);
												for($k=0;$k<$filasalelos;$k++){
																$idalelo = pg_fetch_result($resalelos,$k,0);
																$sqldatosalelo = "select valor,dominancia from alelos where id=".$idalelo;
																$resdatosalelo = pg_query($conn,$sqldatosalelo);
																$valor = pg_fetch_result($resdatosalelo,0);
																$dominancia = pg_fetch_result($resdatosalelo,1);
																$line = $idalelo.":".$valor.":".$dominancia.":";
																fwrite($fh,$line);
												}
												$line = "&:";
												fwrite($fh,$line);
								}
								$line="\n$=\n";
								fwrite($fh,$line);
								$line="states";
								fwrite($fh,$line);
								//Estados=sustratos
								$sqlestados = "select sustratos from caracteres where id = ".$id." order by sustratos";
								$resestados = pg_query($conn,$sqlestados);
								$numestados = pg_fetch_result($resestados,0);
								$line="\n0=1";
								fwrite($fh,$line);
								for($l=1;$l<$numestados;$l++){
												$line="\n".$l."=0";
												fwrite($fh,$line);
								}
								$line="\n$=\nconnections";
								fwrite($fh,$line);
								//conexiones
								$sqlcon = "select estadoa,transicion,estadob from conexiones where car_id=".$id;
								$rescon = pg_query($conn,$sqlcon);
								$filascon = pg_num_rows($rescon);
								for($m=0;$m<$filascon;$m++){
												$estadoa=pg_fetch_result($rescon,$m,0);
												$transicion=pg_fetch_result($rescon,$m,1);
												$estadob=pg_fetch_result($rescon,$m,2);
												$line = "\n".$estadoa."=".$transicion."=".$estadob;
												fwrite($fh,$line);
								}
								$line="\n$=\n";
								fwrite($fh,$line);
				}
				$line="@:\n";
				fwrite($fh,$line);
				//comprobar el tipo de cruce
				//si es una generacon aleatoria:
				$line="*create\n*end\n";
				fwrite($fh,$line);
				//ejecutar
				$command = "gen2web ".$_SESSION['proactivo']." > /dev/null";
				echo $command;
				system($command,$ret);
				if($ret==0){
								echo "creada generación";
								$sqlnewgen ="insert into generaciones_proy (proy_id,generacion_id) values (".$_SESSION['proactivo'].", ".$gen.")";
								$res=pg_query($conn,$sqlnewgen);
				}else{
								echo "ERROR: ".$ret.": no ha podido crearse la nueva generación.";
				}
				pg_close($conn);
}
