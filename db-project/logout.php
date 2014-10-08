<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>db-project - logout<title>
	</head>
	<body>
		<?php
			unset($_SESSION['nome']);
			echo ('<script>
			<!--
			location.replace("http://localhost/db-project/index.php");
			-->
			</script>');
		?>
	</body>
</html>