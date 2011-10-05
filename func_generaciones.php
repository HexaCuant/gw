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
				Tamaño de población: <input type = "text" name="pop" ></input><br /><br />
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
												$tipo="aleatoria";
												makepoc($pop,$gen,$tipo);
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


function indivbutton($id){
				?><td><input type="submit" name="addindiv" value="<?=$id?>"></input>
				<?
}


function processline($line){
				$data = explode("=",$line);
				if ($data[0] > 0){
								echo "\n";
								?><tr><?
								$idindiv = $data[0];
								?><td><?=$idindiv?><?
								fwrite($_SESSION['out'],$data[0]);
								$fenotipos = explode(":",$data[1]);
								$i=0;
								$j=0;
								while($fenotipos[$i] !== "$"){
												$i++;
												?><td><?=$fenotipos[$i]?><?
												fwrite($_SESSION['out']," ".$fenotipos[$i]);
												$i++;
												$j++;
								}
												indivbutton($idindiv);
								?></tr><?
								fwrite($_SESSION['out'],"\n");
								echo "\n";
				}
}

function checkline($line){
				$data = explode("=",$line);
				if ($data[0] > 0){
								return true;
				}else{
								return false;
				}
}

function testcarac($line){
				$conn=conecta();
				$data = explode("=",$line);
				$fenotipos = explode(":",$data[1]);
				$i=0;
				$j=0;
				 while($fenotipos[$i] !== "$"){
								 $sql = "select name from caracteres where id = ".$fenotipos[$i];
								 $res=pg_query($conn,$sql);
								 if(!$res) echo "ERROR 52-func_generaciones";
								 else{
												 $name = pg_fetch_result($res,0);
												 $_SESSION['listcarac'][$j]=$name;
												 ?><th><?=$name?></th><?
												 fwrite($_SESSION['out']," ".$name);
								 }
								 $j++;
								 $i++;
								 $i++;
				 }
				$_SESSION['missingnames']=false;
				pg_close($conn);
				?><th>Selec.</th><tr><?
				fwrite($_SESSION['out'],"\n");
}


function abrirgeneracion($id){
				?><form action = "index.php?option=3#cruce" method="post">
				<input type="submit" name="cerrargeneracion" value="Cerrar"></input><?
				$_SESSION['missingnames']=true;
				?><h2>Generacion <?=$id?></h2><?
				$filename = "../proyectos/".$_SESSION['proactivo']."/".$_SESSION['proactivo'].".dat".$id;
				$outfilename = "../proyectos/".$_SESSION['proactivo']."/".$_SESSION['proactivo']."_".$id."_datos.csv";
				$fh = fopen($filename,"r");
				$_SESSION['out'] = fopen($outfilename,"w");
				?><table>
				<tr><th>Id</th><?
				fwrite($_SESSION['out'],"Id");
				if($fh){
								while (($line = fgets($fh)) !== false){
												if (checkline($line)){
																if($_SESSION['missingnames']) testcarac($line);
																processline($line);
												}
								}
				}
				?></table>
				</form><?
				fclose($fh);
				fclose($_SESSION['out']);
				?><a href="<?=$outfilename?>">Descargar datos</a><?
}

function generacionbutton($id){
?>
<td><input type="submit" name="borrargeneracion" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
<td><input type="submit" name="abrirgeneracion" value="<?=$id?>"></input></td>
<?
}

function testlistgeneraciones(){
				if($_SESSION['vergeneraciones']){
								$conn = conecta();
								$sql="select distinct(generacion_id) from generaciones_proy where proy_id = ".$_SESSION['proactivo']." order by generacion_id";
								$res = pg_query($conn,$sql);
								$filas = pg_num_rows($res);
								?><br /><br /><table><?
								?><tr><th>N.</th><th>Borrar</th><th>Abrir</th></tr><?
								for($i=0;$i<$filas;$i++){
												$id=pg_fetch_result($res,$i,0);
												?><tr><td><?=$id?></td><?
												generacionbutton($id);
												?></tr><?
												echo "\n";
								}
								?></table><?
								pg_close($conn);
				}
				if(isset($_POST['borrargeneracion']) && isset($_POST['confirmado'])){
								$id=$_POST['borrargeneracion'];
								$conn=conecta();
								$sql = "delete from generaciones_proy where proy_id = ".$_SESSION['proactivo']." and generacion_id = ".$id;
								$res = pg_query($conn,$sql);
								$file = "../proyectos/".$_SESSION['proactivo']."/".$_SESSION['proactivo'].".dat".$id;
								$command = "rm ".$file;
								system($command);
								refresh();
				}
}

function crearcruce(){
				//generacion max
				$sql="select max(generacion_id) from generaciones_proy where proy_id = ".$_SESSION['proactivo'];
				$conn = conecta();
				$res = pg_query($conn,$sql);
				$generacion_id = pg_fetch_result($res,0);
				$generacion_id++;
				pg_close($conn);
				//////
?><a name="cruce">
<form action="index.php?option=3" method="post">
				Generación num. <input type="text" name="generacion_id" value="<?=$generacion_id?>"></input /><br /><br />
				Tamaño población <input type="text" name="poblacion" value=""></input /><br /><br />
<?
				if($_SESSION['creandocruce']){
								?><input type="submit" name="ocultarparentales" value="Ocultar parentales"></input><?
				}
				else{
								?><input type="submit" name="verparentales" value="Ver parentales"></input><?
				}
								?><input type="submit" name="cruzar" value="Generar nueva generación"></input><?

				if(isset($_POST['verparentales'])){
								$_SESSION['cruce_gen_id']=$_POST['generacion_id'];
								$_SESSION['creandocruce']=TRUE;
								refresh();
				}
				if(isset($_POST['ocultarparentales'])){
								$_SESSION['cruce_gen_id']=$_POST['generacion_id'];
								$_SESSION['creandocruce']=FALSE;
								refresh();
				}
				if(isset($_POST['cruzar'])){
								$pop = $_POST['poblacion'];
								if ($pop > 0){
												$gen=$_POST['generacion_id'];
												$tipo="cruce";
												makepoc($pop,$gen,$tipo);
								}
				}

				if($_SESSION['creandocruce']){
								?><h3>Parentales</h3><?
								cruce();
				}
?>
</form>
<?
}

function parentalbutton($id){
?>
<td><input type="submit" name="borrarparental" value="<?=$id?>"></input><input type="checkbox" name="confirmado"></input></td>
<?
}


function cruce(){
				//ejemplo cruce		1,6:5,3:=,10:
				$conn=conecta();
				if(isset($_POST['borrarparental'])){
								$id = $_POST['borrarparental'];
								$sql = "delete from parentales where id=".$id;
								$res=pg_query($conn,$sql);
				}
				if(isset($_POST['addindiv'])){
								$sql="insert into parentales (generacion_id, indiv_id, gener_indiv_id,proy_id) values (".$_SESSION['cruce_gen_id'].",".$_POST['addindiv'].",".$_SESSION['generacionactiva'].",".$_SESSION['proactivo'].")";
								$res=pg_query($conn,$sql);
				}
				?><table><?
				?><tr><th>N.</th><th>Indiv. Id</th><th>Generación</th><th>Borrar</th></tr><?
				$sql="select id, indiv_id, gener_indiv_id from parentales where generacion_id = ".$_SESSION['cruce_gen_id']." and proy_id =". $_SESSION['proactivo']." order by gener_indiv_id, indiv_id";
				$res = pg_query($conn,$sql);
				$filas = pg_num_rows($res);
				for($i=0;$i<$filas;$i++){
								$id = pg_fetch_result($res,$i,0);
								$indiv_id = pg_fetch_result($res,$i,1);
								$gener = pg_fetch_result($res,$i,2);
								?><tr><td><?=$id?></td><td><?=$indiv_id?></td><td><?=$gener?></td><?parentalbutton($id)?></tr><?
				}
				?></table><?
				pg_close($conn);
}


function makepoc($pop,$gen,$tipo){
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
				if($tipo=="aleatoria"){
				$line="*create\n";
				fwrite($fh,$line);
				}
				if($tipo=="cruce"){
								//Qué generaciones hay que leer
								$sqlread = "select distinct gener_indiv_id from parentales where generacion_id = ".$_POST['generacion_id']." order by gener_indiv_id";
								$resread = pg_query($conn,$sqlread);
								$filas = pg_num_rows($resread);
								for($i=0;$i<$filas;$i++){
												$line="*read\n";
												fwrite($fh,$line);
												$line=pg_fetch_result($resread,$i,0);
												$line = $line."\n";
												fwrite($fh,$line);
								}

								//ejemplo cruce		1,6:5,3:=,10:
								$line = "*cross\n";
								fwrite($fh,$line);
								$sqlcruce="select indiv_id, gener_indiv_id from parentales where generacion_id = ".$_POST['generacion_id']." and proy_id = ".$_SESSION['proactivo']."order by gener_indiv_id, indiv_id";
								$rescruce=pg_query($conn,$sqlcruce);
								$filas = pg_num_rows($rescruce);
								$line="";
								for($i=0;$i<$filas;$i++){
												$indiv = pg_fetch_result($rescruce,$i,0);
												$gener = pg_fetch_result($rescruce,$i,1);
												$line = $line.$indiv.",".$gener.":";
								}
								fwrite($fh,$line);
								$poblacion=$_POST['poblacion'];
								$line="=,".$poblacion.":\n";
								fwrite($fh,$line);
				}
				$line = "*end\n";
				fwrite($fh,$line);
				//ejecutar
				$command = "gen2web ".$_SESSION['proactivo']." > /dev/null";
				echo $command;
				system($command,$ret);
				if($ret==0){
								echo "creada generación";
								$sqlnewgen ="insert into generaciones_proy (proy_id,generacion_id) values (".$_SESSION['proactivo'].", ".$gen.")";
								$res=pg_query($conn,$sqlnewgen);
								$_SESSION['cruce_gen_id']=$_POST['generacion_id'];
								$_SESSION['creandocruce']=FALSE;
								refresh();
				}else{
								echo "ERROR: ".$ret.": no ha podido crearse la nueva generación.";
				}
				pg_close($conn);
}
