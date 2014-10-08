<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - viewroad<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<?php
			$city=$_GET['city'];
			//comandi gestione
			echo('<table width="70%" align="center" border="0">
				<caption><p align="center"><h3>db-project - Città raggiungibili da '.$city.'</h3></p><caption>
				<tr>
					<td width=25%><p align="center"><a href=index.php>Home</a></p></td>
					<td width=25%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
					<td width=25%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
					<td width=25%><p align="center"><a href=logout.php>Logout</a></p></td>
				</tr>
				</table>
				');
			//carico dal database le informazioni di routing
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="select * from cityroute where partenza='$city';";
			$resource=pg_query($db, $sql);
			$num=pg_num_rows($resource);
			echo('<table width=50% align=center border=1>
				<caption><p><h5>Città</h5></p></caption>
				</thead>
				<tr>
					<th width=20%><p>Destinazione</p></td>
					<th width=10%><p>Movimenti</p></td>
					<th width=70%><p>Passa Da</p></td>
				</tr>
				</thead>
			');
			$i=0;
			//ciclo while che genera le righe della tabella
			while($i < $num){
				$row=pg_fetch_array($resource,$i,PGSQL_ASSOC);
				echo('<tr>
						<td><p>'.$row['arrivo'].'</p></td>
						<td><p>'.$row['hop'].'</p></td>
						<td><p>');
				$hop=$row['hop'];
				$partenza=$row['passada'];
				$arrivo=$row['arrivo'];
				$passada=$row['passada'];
				while($hop > 2){
					$sql2="select * from cityroute where partenza='$partenza' and arrivo='$arrivo';";
					$res2=pg_query($db, $sql2);
					$route=pg_fetch_array($res2, NULL, PGSQL_ASSOC);
					$passada=$passada.', '.$route['passada'];
					$partenza=$route['passada'];
					$hop = $route['hop'];
				}
				echo($passada.'.</p></td></tr>');
				$i = $i+1;
			}
			echo('</table>');
		?>