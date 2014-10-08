<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - modarmy<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			$nome=$_SESSION['nome'];
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
					<caption><p align="center"><h4>db-project - Modifica Esercito</h4></p><caption>
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
				echo('<caption><p><h5>Scegli l'."'".'esercito da gestire. Ricorda che puoi investire denaro in unesercito una sola volta per turno. Click sul nome per cambiare nome, click sui vi per cambiare investimento in pi.</h5></p></caption>');
				//controllo errori
				//Recupero dati sugli eserciti del giocatore
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select nomeesercito, esercito.pi, numeros, pm, va, vd, vittorie, mod from esercito join nazione on nazione=nomen where giocatore='$nome'";
				$resource=pg_query($db, $sql);
				//Numero eserciti da visualizzare
				$numarmy=pg_num_rows($resource);
				//Intestazione Tabella
				echo('<thead>
						<tr>
							<th width="20%"><p>Esercito</p></th>
							<th width="16%"><p>Numerosità</p></th>
							<th width="12%"><p>PI</p></th>
							<th width="12%"><p>PM</p></th>
							<th width="12%"><p>VA</p></th>
							<th width="12%"><p>VD</p></th>
							<th width="16%"><p>Vittorie</p></th>
						</tr>
					</thead>
				');
				//Genero le righe
				$i=0;
				while($i < $numarmy){
					$army=pg_fetch_array($resource, $i, PGSQL_ASSOC);
					$nomeeurl=str_replace(' ','%20',$army['nomeesercito']);
					echo('<tr>
							<td><p><a href=modarmy.php?army='.$nomeeurl.'&menu=1>'.$army['nomeesercito'].'</a></p></td>');
					if($army['mod'] == 'f'){echo('<td><p><a href=modarmy.php?army='.$nomeeurl.'&menu=2&oldnum='.$army['numeros'].'>'.$army['numeros'].'</a></p></td>
										<td><p><a href=modarmy.php?army='.$nomeeurl.'&menu=3&oldpi='.$army['pi'].'>'.$army['pi'].'</a></p></td>
									');}
					else{echo('<td><p>'.$army['numeros'].'</p></td><td><p>'.$army['pi'].'</p></td>');}
					echo('	<td><p>'.$army['pm'].'</p></td>
							<td><p>'.$army['va'].'</p></td>
							<td><p>'.$army['vd'].'</p></td>
							<td><p>'.$army['vittorie'].'</p></td>
						</tr>
					');
					$i = $i+1;
				}
				echo('</table>');
			}
			//Schermata cambia nome
			if($_GET['menu'] == 1){
				$nomee=$_GET['army'];
				$nomeeurl=str_replace(' ','%20',$nomee);
				echo('<form action=modarmy.php?menu=99&send=1&army='.$nomeeurl.' method=post>
					<table width="50%" align="center" border="0">
						<caption><p align="center"><h5>Scegli come rinominare '.$nomee.'</h5></p><caption>
						<tr>
							<td width=50%><p>Nuovo Nome</p></td>
							<td><p><input type=text name=newnomee size=30 maxlength=40></p></td>
						</tr>
						<tr>
							<td colspan=2><p align=center><input type=submit value=Invia></p></td>
						</tr>
					</table>
				');
			}
			//Schermata cambia Numerosità
			if($_GET['menu'] == 2){
				$oldnum=$_GET['oldnum'];
				$nomee=$_GET['army'];
				$nomeeurl=str_replace(' ','%20',$nomee);
				echo('<form action=modarmy.php?menu=99&send=1&army='.$nomeeurl.' method=post>
					<table width="50%" align="center" border="0">
						<caption><p align="center"><h5>Scegli come variare la numerosità in '.$nomee.'. La numerosità può solo aumentare e la differenza viene sottratta alla città dove è stanziato l'."'".'esercito.</h5></p><caption>
						<tr><td colspan=2><p align="center"><h5>Numerosità attuale: '.$oldnum.'</h5></p></td></tr>
						<tr>
							<td width=50%><p>Nuova Numerosità</p></td>
							<td><p><input type=text name=newnum size=30 maxlength=40></p></td>
						</tr>
						<tr>
							<td colspan=2><p align=center><input type=submit value=Invia></p></td>
						</tr>
					</table>
				');
			}
			//Schermata cambia Investimento
			if($_GET['menu'] == 3){
				$oldpi=$_GET['oldpi'];
				$nomee=$_GET['army'];
				$nomeeurl=str_replace(' ','%20',$nomee);
				echo('<form action=modarmy.php?menu=99&send=1&army='.$nomeeurl.' method=post>
					<table width="50%" align="center" border="0">
						<caption><p align="center"><h5>Scegli come variare l'."'".'investimento in '.$nomee.'</h5></p><caption>
						<tr><td colspan=2><p align="center"><h5>Investimento attuale: '.$oldpi.'</h5></p></td></tr>
						<tr>
							<td width=50%><p>Nuova Somma Investita</p></td>
							<td><p><input type=text name=newpi size=30 maxlength=40></p></td>
						</tr>
						<tr>
							<td colspan=2><p align=center><input type=submit value=Invia></p></td>
						</tr>
					</table>
				');
			}
			if($_GET['send'] == 1){
				//Recupero dati inviati tramite GET e POST
				$nomee=$_GET['army'];
				$newnomee=$_POST['newnomee'];
				$newpi=$_POST['newpi'];
				$newnum=$_POST['newnum'];
				//if - controllo se sto cambiando nome o investimento o numerosità e genero query
				if($newnomee != NULL){$sql="update esercito set nomeesercito='$newnomee' where nomeesercito='$nomee';";}
				else if($newpi != NULL){$sql="update esercito set pi='$newpi' where nomeesercito='$nomee';";}
				else if($newnum != NULL){$sql="update esercito set numeros='$newnum' where nomeesercito='$nomee';";}
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$resource=pg_query($db, $sql);
				if(!$resource){
					echo ('<script>
						<!--
						location.replace("http://localhost/db-project/modarmy.php?err=1");
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