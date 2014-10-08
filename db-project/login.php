<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	
	<head>
		<title>db-project - login<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<table width="40%" align="center" border="0">
			<caption><p align="center"><p align="center"><h3>db-project - Login</h3></p><caption>
				<tr>
					<td width="50%"><p align="center"><a href=index.php>Torna all'Inizio</a></p></td>
					<td><p align="center"><a href=register.php>Registra Nuovo Utente</a></p></td>
				</tr>
		</table>
		<?php
			if($_GET['send'] == 0){
				echo('<table width="40%" align="center" border="1">
					<caption><p align="center"><h5>Inserire Username e password</h5></p></caption>
				');
				if($_GET['err'] == 1){ echo ('<tr><td colspan="2"><p align="center">Nome utente e/o password errati.</p></td></tr>');}
				if($_GET['new'] == 1){ echo ('<tr><td colspan="2"><p align="center">Registrazione effettuata con successo. Ora puoi accedere con i tuoi dati.</p></td></tr>');}
				echo('<form action="login.php?send=1" method="post">
						<tr>
							<td width="50%"><p>Username:</p></td>
							<td width="50%"><input type="text" name="user" size="20" maxlength="30"></td>
						</tr>
						<tr>
							<td width="50%"><p>Password:</p></td>
							<td width="50%"><input type="password" name="passwd" size="20" maxlength="30"></td>
						</tr>
						<tr>
							<td colspan="2"><p align="center"><input type="submit" value="Login"></p></td>
						</tr>
					</table>
				');
			}
			if($_GET['send'] == 1){
				$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
				$nome=$_POST['user'];
				$passwd=$_POST['passwd'];
				$sql="select * from giocatore where nomeg='$nome' and passwd='$passwd';";
				$resource=pg_query($db,$sql);
				$row=pg_fetch_array($resource, NULL, PGSQL_NUM);
				if($row[0] != NULL){
					$_SESSION['nome'] = $row[0];
					echo ('<script>
					<!--
					location.replace("http://localhost/db-project/index.php");
					-->
					</script>');
				} else {
					echo ('<script>
					<!--
					location.replace("http://localhost/db-project/login.php?err=1&send=0");
					-->
					</script>');
				}
			}
		?>
	</body>
</html>