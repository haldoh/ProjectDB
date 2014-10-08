<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - stats<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
				<caption><p align="center"><h3>db-project - Statistiche Nazioni</h3></p><caption>
				<tr>
					<td width=50%><p align="center"><a href=index.php>Home</a></p></td>
					<td width=50%><p align="center"><a href=logout.php>Logout</a></p></td>
				</tr>
				</table>
				');
			//carico dal database la vista con le statistiche sulle nazioni
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="select * from statnazioni, nazione where nazione=nomen;";
			$resource=pg_query($db, $sql);
			$num=pg_num_rows($resource);
			echo('<table width=70% align=center border=1>
				<caption><p><h5>Nazioni</h5></p></caption>
				</thead>
				<tr>
					<th width=23%><p>Nazione</p></td>
					<th width=11%><p>Abitanti</p></td>
					<th width=11%><p>PI</p></td>
					<th width=11%><p>Num Città</p></td>
					<th width=11%><p>Vi Città</p></td>
					<th width=11%><p>Num Eserciti</p></td>
					<th width=11%><p>PI eserciti</p></td>
					<th width=11%><p>Ricchezza tot</p></td>
				</tr>
				</thead>
			');
			$i=0;
			//ciclo while che genera le righe della tabella
			while($i < $num){
				$row=pg_fetch_array($resource,$i,PGSQL_ASSOC);
				echo('<tr>
						<td><p>'.$row['nazione'].'</p></td>
						<td><p>'.$row['ab'].'</p></td>
						<td><p>'.$row['pi'].'</p></td>
						<td><p>'.$row['citta'].'</p></td>
						<td><p>'.$row['picitta'].'</p></td>
						<td><p>'.$row['eserciti'].'</p></td>
						<td><p>'.$row['pieserciti'].'</p></td>
						<td><p>'.($row['pi']+$row['picitta']+$row['pieserciti']).'</p></td>
					</tr>
				');
				$i = $i+1;
			}
			echo('</table>');
		?>