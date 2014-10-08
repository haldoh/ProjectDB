<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - modcity<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			$nome=$_SESSION['nome'];
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
					<caption><p align="center"><h4>db-project - Modifica città</h4></p><caption>
					<tr>
						<td width=25%><p align="center"><a href=index.php>Home</a></p></td>
						<td width=25%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
						<td width=25%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
						<td width=25%><p align="center"><a href=logout.php>Logout</a></p></td>
					</tr>
				</table>
				');
			if($_GET['menu'] == 0){
				//Inizializzazione tabella
				echo('<table width=50% align=center border=1>');
				if($_GET['err'] == 1){echo('<tr><td colspan=6><p align=center>Errore - riprova</p></td></tr>');}
				echo('<caption><p><h5>Scegli la città da gestire. Ricorda che puoi investire denaro in una città una sola volta per turno. Click sul nome per cambiare nome, click sui vi per cambiare investimento in pi.</h5></p></caption>');
				//controllo errori
				//Recupero dati sulle città del giocatore
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select nomec as Citta, vi, ab, rn, ra, rl, mod from citta join nazione on nazione=nomen where giocatore='$nome'";
				$resource=pg_query($db, $sql);
				//Numero città da visualizzare
				$numcity=pg_num_rows($resource);
				//Intestazione Tabella
				echo('<thead>
						<tr>
							<th width="20%"><p>Nome Città</p></th>
							<th width="16%"><p>Vi</p></th>
							<th width="16%"><p>Ab</p></th>
							<th width="16%"><p>Rn</p></th>
							<th width="16%"><p>Ra</p></th>
							<th width="16%"><p>Rl</p></th>
						</tr>
					</thead>
				');
				//Genero le righe
				$i=0;
				while($i < $numcity){
					$city=pg_fetch_array($resource, $i, PGSQL_NUM);
					$nomecurl=str_replace(' ','%20',$city[0]);
					echo('<tr>
							<td><p><a href=modcity.php?city='.$nomecurl.'&menu=1>'.$city[0].'</a></p></td>');
					if($city[6] == 'f'){echo('<td><p><a href=modcity.php?city='.$nomecurl.'&menu=2&oldvi='.$city[1].'>'.$city[1].'</a></p></td>');}
					else{echo('<td><p>'.$city[1].'</p></td>');}
					echo('<td><p>'.$city[2].'</p></td>
							<td><p>'.$city[3].'</p></td>
							<td><p>'.$city[4].'</p></td>
							<td><p>'.$city[5].'</p></td>
						</tr>
					');
					$i = $i+1;
				}
				echo('</table>');
			}
			//Schermata cambio nome
			if($_GET['menu'] == 1){
				$nomec=$_GET['city'];
				$nomecurl=str_replace(' ','%20',$nomec);
				echo('<form action=modcity.php?menu=99&send=1&city='.$nomecurl.' method=post>
					<table width="50%" align="center" border="0">
						<caption><p align="center"><h5>Scegli come rinominare '.$nomec.'</h5></p><caption>
						<tr>
							<td width=50%><p>Nuovo Nome</p></td>
							<td><p><input type=text name=newnomec size=30 maxlength=40></p></td>
						</tr>
						<tr>
							<td colspan=2><p align=center><input type=submit value=Invia></p></td>
						</tr>
					</table>
				');
			}
			//Schermata cambio Investimento
			if($_GET['menu'] == 2){
				$oldvi=$_GET['oldvi'];
				$nomec=$_GET['city'];
				$nomecurl=str_replace(' ','%20',$nomec);
				echo('<form action=modcity.php?menu=99&send=1&city='.$nomecurl.' method=post>
					<table width="50%" align="center" border="0">
						<caption><p align="center"><h5>Scegli come variare l'."'".'investimento in '.$nomec.'</h5></p><caption>
						<tr><td colspan=2><p align="center"><h5>Investimento attuale: '.$oldvi.'</h5></p></td></tr>
						<tr>
							<td width=50%><p>Nuova Somma Investita</p></td>
							<td><p><input type=text name=newvi size=30 maxlength=40></p></td>
						</tr>
						<tr>
							<td colspan=2><p align=center><input type=submit value=Invia></p></td>
						</tr>
					</table>
				');
			}
			if($_GET['send'] == 1){
				//Recupero dati inviati tramite GET e POST
				$nomec=$_GET['city'];
				$newnomec=$_POST['newnomec'];
				$newvi=$_POST['newvi'];
				//if - controllo se sto modificando il nome o l'investimento e genero la query di conseguenza
				if($newnomec != NULL){$sql="update citta set nomec='$newnomec' where nomec='$nomec';";}
				else if($newvi != NULL){$sql="update citta set vi='$newvi' where nomec='$nomec';";}
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$resource=pg_query($db, $sql);
				if(!$resource){
					echo ('<script>
						<!--
						location.replace("http://localhost/db-project/modcity.php?err=1");
						-->
						</script>');
				}
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/index.php");
				-->
				</script>');
			}
		?>
	</body>
</html>
