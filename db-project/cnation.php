<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - createnation<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<table width="40%" align="center" border="0">
			<caption><p align="center"><p align="center"><h4>db-project - Create Nation</h4></p><caption>
				<tr>
					<td width="50%"><p align="center"><a href=index.php>Torna all'Inizio</a></p></td>
					<td><p align="center"><a href=logout.php>Logout</a></p></td>
				</tr>
		</table>
		<?php
			if($_GET['send'] == 0){
				echo('<table width="40%" align="center" border="1">
					<caption><p align="center"><h5>Scegliere un nome per la nazione e una bandiera (max 300kb).</h5></p></caption>
				');
				if($_GET['err'] == 1){echo('<tr><td colspan="2"><p align="center">Errore - Riprova</p></td></tr>');}
				echo('<form enctype="multipart/form-data" action="cnation.php?send=1" method="post">
						<tr>
							<td width="50%"><p>Nome Nazione:</p></td>
							<td><input type="text" name="name" size="20" maxlength="30"></td>
						</tr>
						<tr>
							<td width="50%"><p>Bandiera:</p></td>
							<td> <input type="hidden" name="MAX_FILE_SIZE" value="300000" /><input name="flag" type="file" size="25"/></td>
						</tr>
						<tr>
							<td><p align="center"><input type="reset" value="Azzera Campi"></p></td>
							<td><p align="center"><input type="submit" value="Invia"></p></td>
						</tr>
					</table>
				</form>');
			}
			if($_GET['send'] == 1){
				$nome=$_POST['name'];
				$nomes=$_SESSION['nome'];
				$db = pg_connect("host=localhost user=postgres password=password dbname=dbproject");
				$sql="select * from nazione where nomen='$nome' or giocatore='$nomes';";
				$resource=pg_query($db, $sql);
				$row=pg_fetch_array($resource, NULL, PGSQL_NUM);
				if($row[0] != NULL) {
				echo ('<script>
					<!--
					location.replace("http://localhost/db-project/cnation.php?err=1");
					-->
					</script>');
				} else {
					$uploaddir = '/home/postgres/';
					$uploadfile = $uploaddir . basename($_FILES['flag']['name']);
					$nome = $_POST['name'];
					if (move_uploaded_file($_FILES['flag']['tmp_name'], $uploadfile)){echo "<p>File caricato con successo.</p>";
					}else{
						echo ('<script>
						<!--
						location.replace("http://localhost/db-project/cnation.php?err=1");
						-->
						</script>');
					}	
				}
				$sql = "insert into nazione values ('$nome', '$nomes', lo_import('$uploadfile'));";
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

