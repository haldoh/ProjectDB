<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - endturn<title>
	</head>
	<body>
		<?php
			$nome=$_SESSION['nome'];
			$db=pg_connect("host=localhost user=project password=password dbname=dbproject");
			$sql="update giocatore set turno='f' where nomeg='$nome';";
			$resource=pg_query($db, $sql);
			echo ('<script>
			<!--
			location.replace("http://localhost/db-project/index.php");
			-->
			</script>');
		?>
	</body>
</html>