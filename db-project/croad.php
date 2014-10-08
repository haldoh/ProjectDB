<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - createroad<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
					<caption><p align="center"><h4>db-project - Crea Strada</h4></p><caption>
					<tr>
						<td width=25%><p align="center"><a href=index.php>Home</a></p></td>
						<td width=25%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
						<td width=25%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
						<td width=25%><p align="center"><a href=logout.php>Logout</a></p></td>
					</tr>
				</table>
				');
			if($_GET['send'] == 0){
				//Inizializzazione tabella
				echo('<table width=50% align=center border=1>
					<caption><p><h5>Seleziona la città da cui far partire la nuova strada. Il costo è proporzionale alla popolazione delle due città.</h5></p></caption>
				');
				//controllo errori
				if($_GET['err'] == 1){echo('<tr><td colspan=3><p align=center>Errore - Strada già esistente</p></td></tr>');}
				if($_GET['err'] == 2){echo('<tr><td colspan=3><p align=center>Errore - Fondi Insufficienti</p></td></tr>');}
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select nomec, ab, nazione from citta;";
				$resource=pg_query($db, $sql);
				$numcities=pg_num_rows($resource);
				echo('</thead>
					<tr>
						<th width="34%"><p>Nome Città</p></th>
						<th width="33%"><p>Ab</p></th>
						<th width="33%"><p>Nazione</p></th>
					</tr>
					</thead>
				');
				//Mostro alternative
				$i=0;
				while($i < $numcities){
					$city=pg_fetch_array($resource,$i,PGSQL_ASSOC);
					$nomecurl=str_replace(' ','%20',$city['nomec']);
					echo('<tr>
							<td><a href=croad.php?city='.$nomecurl.'&send=1><p>'.$city['nomec'].'</p></a></td>
							<td><p>'.$city['ab'].'</p></td>
							<td><p>'.$city['nazione'].'</p></td>
						</tr>
					');
					$i=$i+1;
				}
				echo('</table>');
			}
			if($_GET['send'] == 1){
				$nomec=$_GET['city'];
				$nomecurl=str_replace(' ','%20', $nomec);
				//Inizializzazione tabella
				echo('<table width=50% align=center border=1>
					<caption><p><h5>Seleziona la destinazione per creare una strada che parta da '.$nomec.'. Il costo è proporzionale alla popolazione delle due città.</h5></p></caption>
				');
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select nomec, ab, nazione from citta where nomec<>'$nomec';";
				$resource=pg_query($db, $sql);
				$numcities=pg_num_rows($resource);
				echo('</thead>
					<tr>
						<th width="34%"><p>Nome Città</p></th>
						<th width="33%"><p>Ab</p></th>
						<th width="33%"><p>Nazione</p></th>
					</tr>
					</thead>
				');
				//Mostro alternative
				$i=0;
				while($i < $numcities){
					$city=pg_fetch_array($resource,$i,PGSQL_ASSOC);
					$nomecurl2=str_replace(' ','%20',$city['nomec']);
					echo('<tr>
							<td><a href=croad.php?city='.$nomecurl.'&city2='.$nomecurl2.'&send=2><p>'.$city['nomec'].'</p></a></td>
							<td><p>'.$city['ab'].'</p></td>
							<td><p>'.$city['nazione'].'</p></td>
						</tr>
					');
					$i=$i+1;
				}
				echo('</table>');
			}
			if ($_GET['send'] == 2){
				//checkroad- Controllo dati nuova strada
				$city1=$_GET['city'];
				$nomecurl=str_replace(' ', '%20', $city1);
				$city2=$_GET['city2'];
				$nomecurl2=str_replace(' ', '%20', $city2);
				$nomes=$_SESSION['nome'];
				//Controllo esistenza strada uguale
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select * from strada where (citta1='$city1' and citta2='$city2') or (citta1='$city2' and citta2='$city1');";
				$resource=pg_query($db, $sql);
				$row=pg_fetch_array($resource,NULL,PGSQL_NUM);
				if($row[0] != NULL){
					echo ('<script>
					<!--
					location.replace("http://localhost/db-project/croad.php?err=1&city='.$nomecurl.'");
					-->
					</script>');
				}
				//Calcolo costo
				$sql="select * from citta where nomec='$city1' or nomec='$city2';";
				$resource=pg_query($db, $sql);
				$row=pg_fetch_array($resource,0,PGSQL_ASSOC);
				$pi=0+$row['ab'];
				$row=pg_fetch_array($resource,1,PGSQL_ASSOC);
				$pi = round(($pi+$row['ab'])/3);
				//Verifico disponibilità fondi
				$sql="select * from nazione where giocatore='$nomes';";
				$resource=pg_query($db,$sql);
				$row=pg_fetch_array($resource,NULL,PGSQL_ASSOC);
				$nomen=$row['nomen'];
				if($row['pi'] < $pi){
					echo ('<script>
					<!--
					location.replace("http://localhost/db-project/croad.php?err=2");
					-->
					</script>');
				}
				echo('<table width=40% align=center border=1>
					<caption><p><h5>Stai per creare una strada fra '.$city1.' e '.$city2.':</h5></p></caption>
					<tr>
						<td width=50%><p>Costo:</p></td>
						<td><p>'.$pi.'</p></td>
					</tr>
					<tr>
						<td><p>PI rimasti alla nazione:</p></td>
						<td><p>'.($row['pi']-$pi).'</p></td>
					</tr>
					<tr>
						<td><p align=center><a href=croad.php?city='.$nomecurl.'&send=0>Annulla<a></p></td>
						<td><p align=center><a href=croad.php?city='.$nomecurl.'&city2='.$nomecurl2.'&naz='.$nomen.'&send=3>Invia<a></p></td>
					</tr>
				</table>
				');
			}
			if($_GET['send'] == 3){
				$nomec=$_GET['city'];
				$nomecurl=str_replace(' ','%20',$nomec);
				$nomec2=$_GET['city2'];
				$naz=$_GET['naz'];
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$sql="select newroad('$nomec','$nomec2','$naz');";
				$resource=pg_query($db, $sql);
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/index.php");
				-->
				</script>');
			}
		?>
	</body>
</html>