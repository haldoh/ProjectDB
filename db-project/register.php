<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - register<title>
		<link rel="stylesheet" type="text/css" href="stile.css">
	</head>
	<body>
		<table width="40%" align="center" border="0">
			<caption><p align="center"><p align="center"><h3>db-project - Register</h3></p><caption>
				<tr>
					<td width="50%"><p align="center"><a href=index.php>Torna all'Inizio</a></p></td>
					<td><p align="center"><a href=login.php>Accedi</a></p></td>
				</tr>
		</table>
		<?php
			if($_GET['send'] == 0){
				echo('<table width="40%" align="center" border="1">
					<caption><p align="center"><h5>Scegliere Username e Password</h5></p></caption>
				
				');
				if($_GET['err'] == 1){ echo ('<tr><td colspan="2"><p align="center">Le due password non coincidono</p></td></tr>');}
				if($_GET['err'] == 2){ echo ('<tr><td colspan="2"><p align="center">Username già assegnato</p></td></tr>');}
				echo('<form action="register.php?send=1" method="post">
					<tr>
						<td width="50%"><p>Username:</p></td>
						<td><input type="text" name="name" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td width="50%"><p>Password:</p></td>
						<td><input type="password" name="passwd" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td width="50%"><p>Ripeti Password:</p></td>
						<td><input type="password" name="passwd2" size="20" maxlength="30"></td>
					</tr>
					<tr>
						<td colspan="2"><p align="center"><input type="submit" value="Invia"></p></td>
					</tr>
					</table>
				</form>');
			}
			if($_GET['send'] == 1){
				$nome=$_POST['name'];
				$passwd=$_POST['passwd'];
				$passwd2=$_POST['passwd2'];
				if($passwd != $passwd2) {
					echo ('<script>
						<!--
						location.replace("http://localhost/db-project/register.php?err=1&send=0");
						-->
						</script>');
				} else {
					$time = time();			
					$db = pg_connect("host=localhost user=project password=password dbname=dbproject");
					$sql = "select * from giocatore where nomeg='$nome';";
					$resource = pg_query($db,$sql);
					$row=pg_fetch_array($resource,NULL,PGSQL_NUM);
					if($row[0] != NULL){
						echo ('<script>
						<!--
						location.replace("http://localhost/db-project/register.php?err=2");
						-->
						</script>');
					} else {
						$sql2= "insert into giocatore values ('$nome', '$passwd', '$time');";
						$resource2=pg_query($db,$sql2);
						echo ('<script>
							<!--
							location.replace("http://localhost/db-project/login.php?new=1");
							-->
							</script>');
					}
				}
			} 
		?>
	</body>
</html>