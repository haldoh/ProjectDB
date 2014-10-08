<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - index<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
		<meta http-equiv="refresh" content="60">
	</head>
	<body>
		<?php
			//Controllo l'esistenza di una sessione già attiva
			if($_SESSION['nome'] == NULL){
				//Se nessuna sessione attiva
				echo('<table width="40%" align="center" border="1">
					<caption><p align="center"><h1>Benvenuto in db-project!</h1></p><caption>
					<tr>
						<td width="50%"><p align="center">Nuovo Giocatore?</p></td>
						<td width="50%"><p align= "center">Hai già un account?</p></td>
					</tr>
					<tr>
						<td width="50%"><p align="center"><a href = register.php>Registrati</a></p></td>
						<td width="50%"><p align="center"><a href = login.php>Accedi</a></p></td>
					</tr>
					</table>
					');
			} else {
				$nome=$_SESSION['nome'];
				//Controllo l'esistenza di una nazione per il giocatore dopo il login
				$db=pg_connect("host=localhost user=postgres password=password dbname=dbproject");
				$sql="select * from statnazioni, nazione where nazione=nomen and giocatore='$nome';";
				$resource=pg_query($db, $sql);
				$row=pg_fetch_array($resource, NULL, PGSQL_ASSOC);
				if($row['nazione'] == NULL){
					//Se non esiste nazione, propongo la creazione
					echo('<table width="40%" align="center" border="1">
						<caption><p align="center"><p align="center"><h3>Benvenuto in db-project '.$nome.'!</h3></p><caption>
						<tr>
							<td width="50%"><p align="center">Non hai una Nazione</p></td>
							<td width="50%"><p align= "center">Esci dal gioco</p></td>
						</tr>
						<tr>
							<td width="50%"><p align="center"><a href=cnation.php>Crea Nazione</a></p></td>
							<td width="50%"><p align="center"><a href=logout.php>Logout</a></p></td>
						</tr>
						</table>
					');
				} else {
					//Se esiste una nazione, presento statistiche sulla nazione
					//Comandi di gestione
					//A seconda se il giocatore è di turno o meno, presento diversi comandi
					$sqlturn="select turno from giocatore where nomeg='$nome';";
					$resturn=pg_query($db,$sqlturn);
					$turn=pg_fetch_array($resturn,NULL,PGSQL_ASSOC);
					if($turn['turno'] == 't'){
						echo('<table width="70%" align="center" border="1">
							<caption><p align="center"><h3>db-project - Pannello di Comando di '.$nome.'</h3></p><caption>
							<tr>
							<thead><th colspan=8><p align= center>E'."'".' il tuo turno!</p></th></thead>
							<tr>
							<tr>
								<td width=12%><p align="center"><a href=ccity.php>Nuova Città</a></p></td>
								<td width=12%><p align="center"><a href=modcity.php>Gestisci Città</a></p></td>
								<td width=13%><p align="center"><a href=carmy.php>Nuovo Esercito</a></p></td>
								<td width=13%><p align="center"><a href=modarmy.php>Gestisci Eserciti</a></p></td>
								<td width=13%><p align="center"><a href=croad.php>Crea Strada</a></p></td>
								<td width=13%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
								<td width=12%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
								<td width=12%><p align="center"><a href=logout.php>Logout</a></p></td>
							</tr>
							</table>
							');
					} else if($row['pi'] < -2000){
						echo('<table width="70%" align="center" border="0">
							<caption><p align="center"><h3>db-project - Pannello di Comando di '.$nome.'</h3></p><caption>
							<tr>
							<thead><th colspan=7><p align= center>Sei stato sospeso dal gioco per essere sceso sotto la soglia dei -2000 PI.</p></th></thead>
							<tr>
							<tr>
								<td width=50%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
								<td width=50%><p align="center"><a href=logout.php>Logout</a></p></td>
							</tr>
							</table>
							');
					}
					else {
						echo('<table width="70%" align="center" border="0">
							<caption><p align="center"><h3>db-project - Pannello di Comando di '.$nome.'</h3></p><caption>
							<tr>
							<thead><th colspan=7><p align= center>Attendi il tuo turno!</p></th></thead>
							<tr>
							<tr>
								<td width=50%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
								<td width=50%><p align="center"><a href=logout.php>Logout</a></p></td>
							</tr>
							</table>
							');
					}
					//recupero dati su citta e eserciti
					$sql2="select * from citta where nazione='$row[nazione]';";
					$resourcec=pg_query($db,$sql2);
					$cities=pg_num_rows($resourcec);
					$sql3="select * from esercito where nazione='$row[nazione]'";
					$resourcea=pg_query($db,$sql3);
					$armies=pg_num_rows($resourcea);
					//recupero la bandiera della nazione
					$temp='/home/postgres2/tmp.jpg';
					$sqlb="select lo_export(band, '$temp') from nazione where giocatore='$nome';";
					$resource=pg_query($db, $sqlb);
					//stampa stats nazione
					echo('<div height=30><p></p></div>
						<table width="70%" align="center" border="1">
						<tr>
							<td width="15%"><p align="center"><b>Nazione:</b></p></td>
							<td width="15%"><p align="center">'.$row['nazione'].'</p></td>
							<td rowspan="4"><p align="center"><img src=show.php width="320"></p></td>
							<td width="15%"><p align="center"><b>PI Nazione:</b></p></td>
							<td width="15%"><p align="center">'.$row['pi'].'</p></td>
						</tr>
						<tr>
							<td width="15%"><p align="center"><b>Abitanti:</b></p></td>
							<td width="15%"><p align="center">'.$row['ab'].'</p></td>
							<td width="15%"><p align="center"><b>Vi nelle Città:</b></p></td>
							<td width="15%"><p align="center">'.$row['picitta'].'</p></td>
						</tr>
						<tr>
							<td width="15%"><p align="center"><b>Numero Città:</b></p></td>
							<td width="15%"><p align="center">'.$row['citta'].'</p></td>
							<td width="15%"><p align="center"><b>PI negli Eserciti:</b></p></td>
							<td width="15%"><p align="center">'.$row['pieserciti'].'</p></td>
						</tr>
						<tr>
							<td width="15%"><p align="center"><b>Numero Eserciti:</b></p></td>
							<td width="15%"><p align="center">'.$row['eserciti'].'</p></td>
							<td width="15%"><p align="center"><b>Ricchezza Totale:</b></p></td>
							<td width="15%"><p align="center">'.($row['pi']+$row['picitta']+$row['pieserciti']).'</p></td>
						</tr>
						</table>
					');
					//stampa stats città
					echo('<table width="70%" align="center" border="1">
						<caption><p align="center"><h5>Città</h5></p></caption>
						<thead>
						<tr>
							<th width="20%"><p>Nome Città</p></th>
							<th width="10%"><p>Vi</p></th>
							<th width="10%"><p>Ab</p></th>
							<th width="10%"><p>Rn</p></th>
							<th width="10%"><p>Ra</p></th>
							<th width="10%"><p>Rl</p></th>
							<th width="15%"></th>
						</tr>
						</thead>
					');
					$i=0;
					while($i < $cities){
						$city=pg_fetch_array($resourcec, $i, PGSQL_NUM);
						//converto spazi nel nome della nazione
						$nomec=str_replace(' ','%20',$city[0]);
						echo ('<tr>
								<td><p>'.$city[0].'</p></td>
								<td><p>'.$city[1].'</p></td>
								<td><p>'.$city[2].'</p></td>
								<td><p>'.$city[3].'</p></td>
								<td><p>'.$city[4].'</p></td>
								<td><p>'.$city[5].'</p></td>
								<td><p><a href=viewroad.php?city='.$nomec.'>Visual. Collegamenti</a></p></td>
								</tr>');
						$i = $i+1;
					}echo('</table>');
					//stampa stats eserciti
					echo('<table width="70%" align="center" border="1">
						<caption><p align="center"><h5>Eserciti</h5></p></caption>
						<thead>
						<tr>
							<th width="17%"><p>Nome Esercito</p></th>
							<th width="17%"><p>Attuale Posizione</p></th>
							<th width="16%"><p>Numerosità</p></th>
							<th width="7%"><p>PI</p></th>
							<th width="7%"><p>PM</p></th>
							<th width="7%"><p>VA</p></th>
							<th width="7%"><p>VD</p></th>
							<th width="9%"><p>Vittorie</p></th>
							<th width="13%"><p></p></th>
						</tr>
						</thead>
					');
					$i=0;
					while($i < $armies){
						$army=pg_fetch_array($resourcea, $i, PGSQL_ASSOC);
						$nomecurl=str_replace(' ','%20',$army['citta']);
						$nomeeurl=str_replace(' ','%20',$army['nomeesercito']);
						echo('<tr>
								<td><p>'.$army['nomeesercito'].'</p></td>
								<td><p>'.$army['citta'].'</p></td>
								<td><p>'.$army['numeros'].'</p></td>
								<td><p>'.$army['pi'].'</p></td>
								<td><p>'.$army['pm'].'</p></td>
								<td><p>'.$army['va'].'</p></td>
								<td><p>'.$army['vd'].'</p></td>
								<td><p>'.$army['vittorie'].'</p></td>');
						if($turn['turno'] == 't' && $army['mosso'] == 'f'){echo('<td><p><a href=movearmy.php?city='.$nomecurl.'&army='.$nomeeurl.'>Muovi Esercito</a></p></td></tr>');}
						else{echo('<td><p> </p></td></tr>');}
						$i = $i+1;
					}echo('</table>');
				}
			}
		?>
	</body>
</html>
