<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - movearmy<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			//Memorizzazione di vari dati su giocatore, nazione, esercito
			$nomeg=$_SESSION['nome'];
			$city=$_GET['city'];
			$army=$_GET['army'];
			$armyurl=str_replace(' ','%20', $army);
			$pm=$_GET['pm'];
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="select * from nazione where giocatore = '$nomeg';";
			$res=pg_query($db, $sql);
			$naz=pg_fetch_array($res, NULL, PGSQL_ASSOC);
			$nomen=$naz['nomen'];
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
				<caption><p align="center"><h3>db-project - Muovi Esercito '.$army.'</h3></p><caption>
				<tr>
					<td width=25%><p align="center"><a href=index.php>Home</a></p></td>
					<td width=25%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
					<td width=25%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
					<td width=25%><p align="center"><a href=logout.php>Logout</a></p></td>
				</tr>
				</table>
				');
			if($_GET['send'] == 0){
				//carico dal database le informazioni di routing
				$sql="select * from cityroute where partenza='$city';";
				$resource=pg_query($db, $sql);
				$num=pg_num_rows($resource);
				echo('<table width=40% align=center border=1>
					<caption><p><h5>Scegli dove muovere l'."'".'esercito. Sono elencate solo le città raggiungibili con i pm dell'."'".'esercito, e che non hanno eserciti nemici lungo il percorso.</h5></p></caption>
					</thead>
					<tr>
						<th width=500%><p>Destinazione</p></td>
						<th width=25%><p>Movimenti</p></td>
						<th width=25%><p>Presidiata</p></td>
					</tr>
					</thead>
				');
				$i=0;
				//ciclo while che genera le righe della tabella
				while($i < $num){
					//recupero la prossima città dal risultato della query
					$row=pg_fetch_array($resource,$i,PGSQL_ASSOC);
					$hop=$row['hop'];
					$dist=$hop;
					$partenza=$row['passada'];
					$arrivo=$row['arrivo'];
					$dest=str_replace(' ','%20', $arrivo);
					//controllo se c'è un esercito nemico nella città da cui devo passare
					$sqlbloc="select * from esercito where citta='$partenza' and nazione <> '$nomen';";
					$resbloc=pg_query($db, $sqlbloc);
					$rowbloc=pg_fetch_array($resbloc, NULL, PGSQL_NUM);
					if($rowbloc[0] != NULL) $bloc= 'Sì'; else $bloc='No';
					//controllo se la mia destinazione è presidiata
					$sqlbloc="select * from esercito where citta='$arrivo' and nazione <> '$nomen';";
					$resbloc=pg_query($db, $sqlbloc);
					$rowbloc=pg_fetch_array($resbloc, NULL, PGSQL_NUM);
					if($rowbloc[0] != NULL) $pres= 'Sì'; else $pres='No';
					//Scorro il percorso fra partenza e arrivo
					while($hop > 2 && $bloc != 'Sì'){
						$sql2="select * from cityroute where partenza='$partenza' and arrivo='$arrivo';";
						$res2=pg_query($db, $sql2);
						$route=pg_fetch_array($res2, NULL, PGSQL_ASSOC);
						$partenza=$route['passada'];
						$hop = $route['hop'];
						//controllo se c'è un esercito nemico nella città da cui devo passare
						$sqlbloc="select * from esercito where citta='$partenza' and nazione <> '$nomen';";
						$resbloc=pg_query($db, $sqlbloc);
						$rowbloc=pg_fetch_array($resbloc, NULL, PGSQL_NUM);
						if($rowbloc[0] != NULL) $bloc= 'Sì'; else $bloc='No';
					}
					if($bloc == 'No' && $pm <= $dist){
						echo('<tr>
								<td><p><a href=movearmy.php?dest='.$dest.'&army='.$armyurl.'&send=1>'.$row['arrivo'].'</a></p></td>
								<td><p>'.$row['hop'].'</p></td>
								<td><p>'.$pres.'</p></td>
							</tr>
						');
					}
					$i = $i+1;
				}
				echo('</table>');
			}
			if($_GET['send'] == 1){
				$army=$_GET['army'];
				$dest=$_GET['dest'];
				//Richiamo funzione movearmy definita nel db
				$sqlmv="select movearmy('$army','$dest');";
				$resmv=pg_query($db, $sqlmv);
				$result=pg_fetch_array($resmv, NULL, PGSQL_NUM);
				echo('<table width=40% align=center border=1>');
				if ($result[0] == NULL){echo('<caption><p><h5>L'."'".'esercito ha già mosso.</h5></p></caption>');}
				else{
					if($result[0] > 0){echo('<caption><p><h5>L'."'".'esercito attaccante ha vinto!</h5></p></caption>');
					}else{echo('<caption><p><h5>L'."'".'esercito difensore ha vinto!</h5></p></caption>');}
				}
				echo('<tr>
							<td><p align="center"><a href=index.php>Torna alla Home Page</a></p></td>
						</tr>
						</table>
				');
			}
		?>
	</body>
</html>