<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - createcity<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
					<caption><p align="center"><h4>db-project - Crea Esercito</h4></p><caption>
					<tr>
						<td width=25%><p align="center"><a href=index.php>Home</a></p></td>
						<td width=25%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
						<td width=25%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
						<td width=25%><p align="center"><a href=logout.php>Logout</a></p></td>
					</tr>
				</table>
				');
			$nome=$_SESSION['nome'];
			if($_GET['send'] == 0){
				//Inizializzazione Tabella
				echo('<table width=40% align=center border=1>
					<caption><p><h5>Per creare un nuovo esercito, scegliere la città in cui stanziarlo. Parte della popolazione della città entrerà a far parte dell'."'".'esercito.</h5></p></caption>
				');
				//Controllo errori
				if($_GET['err'] == 1) {echo('<tr><td colspan=6><p align=center>Nome Esercito già utilizzato.</p></td></tr>');}
				if($_GET['err'] == 2)  {echo('<tr><td colspan=6><p align=center>Fondi Nazione insufficienti.</p></td></tr>');}
				if($_GET['err'] == 3) {echo('<tr><td colspan=6><p align=center>Popolazione Città Insufficiente.</p></td></tr>');}
				//Selezione città - recupero dati
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select * from citta where nazione=(select nomen from nazione where giocatore='$nome');";
				$resource=pg_query($db,$sql);
				//Selezione città - presentazione dati
				$numcities=pg_num_rows($resource);
				echo('</thead>
					<tr>
						<th width=25%><p>Nome Città</p></th>
						<th width=15%><p>Ab</p></th>
						<th width=15%><p>vi</p></th>
						<th width=15%><p>rn</p></th>
						<th width=15%><p>ra</p></th>
						<th width=15%><p>rl</p></th>
					</tr>
					</thead>
				');
				//Mostro alternative
				$i=0;
				while($i < $numcities){
					$city=pg_fetch_array($resource,$i,PGSQL_ASSOC);
					$nomecurl=str_replace(' ','%20',$city['nomec']);
					echo('<tr>
							<td><a href=carmy.php?city='.$nomecurl.'&ab='.$city['ab'].'&send=1><p>'.$city['nomec'].'</p></a></td>
							<td><p>'.$city['ab'].'</p></td>
							<td><p>'.$city['vi'].'</p></td>
							<td><p>'.$city['rn'].'</p></td>
							<td><p>'.$city['ra'].'</p></td>
							<td><p>'.$city['rl'].'</p></td>
						</tr>
					');
					$i=$i+1;
				}
				echo('</table>');
			}
			if($_GET['send'] == 1){	
				//Inserimento dati nuovo esercito
				$nomec=$_GET['city'];
				$ab=$_GET['ab'];
				$nomecurl=str_replace(' ','%20',$nomec);
				//Inizializzazione Tabella
				echo('<table width=40% align=center border=1>
					<caption><p><h5>Inserire i dati del nuovo esercito. La numerosità sarà sottratta dalla popolazione della città. La somma di PM, VA e VD sarà sottratta dai PI della nazione e formerà i PI dell'."'".'esercito.</h5></p></caption>
					<tr><td colspan=2><p align=center>'.$nomec.': '.$ab.' abitanti.</p></td></tr>
					');
				//form
				echo('<form action="carmy.php?send=2&city='.$nomecurl.'" method="post">
					<tr>
						<td width=50%><p>Nome Esercito:</p></td>
						<td><input type="text" name="name" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>Numerosità:</p></td>
						<td><input type="text" name="num" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>PM:</p></td>
						<td><input type="text" name="pm" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>VA:</p></td>
						<td><input type="text" name="va" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>VD:</p></td>
						<td><input type="text" name="vd" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p align=center><input type="reset" value="Azzera Campi"></p></td>
						<td><p align=center><input type="submit" value="Invia"></p></td>
					</tr>
				</table>
			</form>');
			}
		if ($_GET['send'] == 2){
			//checkarmy- Controllo dati nuovo esercito
			$nomee=$_POST['name'];
			$pm=$_POST['pm'];
			$num=$_POST['num'];
			$va=$_POST['va'];
			$vd=$_POST['vd'];
			$nome=$_SESSION['nome'];
			$nomec=$_GET['city'];
			$nomecurl=str_replace(' ','%20',$nomec);
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="select nomeesercito from esercito where nomeesercito='$nomee';";
			$resource=pg_query($db, $sql);
			$eser=pg_fetch_array($resource,NULL,PGSQL_ASSOC);
			if($reser['nomeesercito'] != NULL){
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/carmy.php?err=1&send=0");
				-->
				</script>');
			}
			$pi=$pm+$va+$vd;
			$sql="select nomen, pi from nazione where giocatore='$nome';";
			$resource=pg_query($db,$sql);
			$naz=pg_fetch_array($resource,NULL,PGSQL_ASSOC);
			$nomen=$naz['nomen'];
			$nomenurl=str_replace(' ','%20',$nomen);
			if($naz['pi'] < $pi){
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/carmy.php?err=2&send=0");
				-->
				</script>');
			}
			$sql="select ab from citta where nomec='$nomec';";
			$resource=pg_query($db,$sql);
			$city=pg_fetch_array($resource,NULL,PGSQL_ASSOC);
			if($city['ab'] < $num){
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/carmy.php?err=3&send=0");
				-->
				</script>');
			}
			echo('<table width=40% align=center border=1>
			<caption><p><h5>Stai per creare un esercito con i seguenti parametri:</h5></p></caption>
			<tr>
				<td width=50%><p>Nome Esercito:</p></td>
				<td><p>'.$nome.'</p></td>
			</tr>
			<tr>
				<td><p>PI:</p></td>
				<td><p>'.$pi.'</p></td>
			</tr>
			<tr>
				<td><p>PM:</p></td>
				<td><p>'.$pm.'</p></td>
			</tr>
			<tr>
				<td><p>Numerosità:</p></td>
				<td><p>'.$num.'</p></td>
			</tr>
			<tr>
				<td><p>VA:</p></td>
				<td><p>'.$va.'</p></td>
			</tr>
			<tr>
				<td><p>VD:</p></td>
				<td><p>'.$vd.'</p></td>
			</tr>
			<tr>
				<td><p>Abitanti rimasti a '.$nomec.':</p></td>
				<td><p>'.($city['ab']-$num).'</p></td>
			</tr>
			<tr>
				<td><p>PI rimasti alla nazione:</p></td>
				<td><p>'.($naz['pi']-$pi).'</p></td>
			</tr>
			<tr>
				<td><p align=center><a href="carmy.php?send=0">Indietro<a></p></td>
				<td><p align=center><a href="carmy.php?send=3&nome='.$nomee.'&pi='.$pi.'&pm='.$pm.'&num='.$num.'&va='.$va.'&vd='.$vd.'&nomen='.$nomenurl.'&nomec='.$nomecurl.'">Invia<a></p></td>
			</tr>
			</table>
		');
		}
		if ($_GET['send'] == 3){
			//sendccity - Invio dati nuova città al dbms
			$nomee=$_GET['nome'];
			$nomen=$_GET['nomen'];
			$nomec=$_GET['nomec'];
			$pm=$_GET['pm'];
			$pi=$_GET['pi'];
			$num=$_GET['num'];
			$va=$_GET['va'];
			$vd=$_GET['vd'];
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="insert into esercito values('$nomee', '$num', '$pi', '$pm', '$va', '$vd', '0', '$nomen', '$nomec');";
			$resource=pg_query($db,$sql);
			echo ('<script>
				<!--
				location.replace("http://localhost/db-project/index.php");
				-->
				</script>');
		}
		?>
	</body>
</html>