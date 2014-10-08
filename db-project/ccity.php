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
					<caption><p align="center"><h4>db-project - Crea città</h4></p><caption>
					<tr>
						<td width=25%><p align="center"><a href=index.php>Home</a></p></td>
						<td width=25%><p align="center"><a href=stats.php>Stat. Nazioni</a></p></td>
						<td width=25%><p align="center"><a href=endturn.php>Fine Turno</a></p></td>
						<td width=25%><p align="center"><a href=logout.php>Logout</a></p></td>
					</tr>
				</table>
				');
			if($_GET['send'] == 0){
				//Inizializzazione Tabella
				echo('<table width=40% align=center border=1>
					<caption><p><h5>Per fondare una nuova città, scegli un nome che non sia già stato usato, poi scegli quanti punti assegnare a Ra, Rn e Rl. La somma di questi valori sarà il Vi della città e verrà sottratto ai PI della Nazione (min 200PI)</h5></p></caption>
				');
				//Controllo errori
				if($_GET['err'] == 1) {echo('<p>Nome Città già utilizzato.</p>');}
				if($_GET['err'] == 2)  {echo('<p>Fondi Nazione insufficienti. '.$_GET['pi'].' '.$_GET['npi'].'</p>');}
				if($_GET['err'] == 3) {echo('<p>Devi stanziare almeno 200PI</p>');}
			//ccity - Immissione dati nuova città
			echo('<form action="ccity.php?send=1" method="post">
					<tr>
						<td width=50%><p>Nome Città:</p></td>
						<td><input type="text" name="name" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>Ra:</p></td>
						<td><input type="text" name="ra" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>Rn:</p></td>
						<td><input type="text" name="rn" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p>Rl:</p></td>
						<td><input type="text" name="rl" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td><p align=center><input type="reset" value="Azzera Campi"></p></td>
						<td><p align=center><input type="submit" value="Invia"></p></td>
					</tr>
				</table>
			</form>');
		}
		if ($_GET['send'] == 1){
			//checkccity - Controllo dati nuova città
			$nome=$_POST['name'];
			$ra=$_POST['ra'];
			$rn=$_POST['rn'];
			$rl=$_POST['rl'];
			$nomes=$_SESSION['nome'];
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="select * from citta where nomec='$nome';";
			$resource=pg_query($db, $sql);
			$row=pg_fetch_array($resource,NULL,PGSQL_ASSOC);
			if($row[nomec] != NULL){
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/ccity.php?err=1");
				-->
				</script>');
			}
			$pi=$ra+$rn+$rl;
			if($pi < 200){
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/ccity.php?err=3");
				-->
				</script>');
			}
			$sql="select * from nazione where giocatore='$nomes';";
			$resource=pg_query($db,$sql);
			$row=pg_fetch_array($resource,NULL,PGSQL_ASSOC);
			$nomen=$row['nomen'];
			if($row['pi'] < $pi){
				echo ('<script>
				<!--
				location.replace("http://localhost/db-project/ccity.php?err=2");
				-->
				</script>');
			}
		echo('<table width=40% align=center border=1>
			<caption><p><h5>Stai per creare una città con i seguenti parametri:</h5></p></caption>
			<tr>
				<td width=50%><p>Nome Città:</p></td>
				<td><p>'.$nome.'</p></td>
			</tr>
			<tr>
				<td><p>Vi:</p></td>
				<td><p>'.$pi.'</p></td>
			</tr>
			<tr>
				<td><p>Ra:</p></td>
				<td><p>'.$ra.'</p></td>
			</tr>
			<tr>
				<td><p>Rn:</p></td>
				<td><p>'.$rn.'</p></td>
			</tr>
			<tr>
				<td><p>Rl:</p></td>
				<td><p>'.$rl.'</p></td>
			</tr>
			<tr>
				<td><p>PI rimasti alla nazione:</p></td>
				<td><p>'.($row['pi']-$pi).'</p></td>
			</tr>
			<tr>
				<td><p align=center><a href="ccity.php?send=0">Indietro<a></p></td>
				<td><p align=center><a href="ccity.php?send=2&nome='.$nome.'&vi='.$pi.'&ra='.$ra.'&rn='.$rn.'&rl='.$rl.'&nomen='.$nomen.'">Invia<a></p></td>
			</tr>
			</table>
		');
		}
		if ($_GET['send'] == 2){
			//sendccity - Invio dati nuova città al dbms
			$nome=$_GET['nome'];
			$vi=$_GET['vi'];
			$rn=$_GET['rn'];
			$ra=$_GET['ra'];
			$rl=$_GET['rl'];
			$nomen=$_GET['nomen'];
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="insert into citta values('$nome', '$vi', 0, '$rn', '$ra', '$rl', '$nomen');";
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