<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>ROTAR - INICIO</title>
</head>
<body>
	<h1>Rotar</h1>
	<form name="form1" action="" method="get">
		Ingrese el numero de filas/columnas (N) que contendrá la matriz (entre 3 y 20)<br>
		<input type="text" placeholder="Numero de filas/columnas (N)" name="numeroFCN"></input>
		<br>Ingrese el numero de filas/columnas (T) de la submatriz de rotacion (T <= N/2+1)<br>
		<input type="text" placeholder="Numero de filas/columnas (T)" name="numeroFCT"></input>
		<br>

		<input type="submit" name="buttonEnviarNyT" value="Enviar N y T"></input>

		<?php
			if(isset($_GET["buttonEnviarNyT"])){
				if(isset($_GET["numeroFCN"]) && $_GET["numeroFCN"] >= 3 && $_GET["numeroFCN"] <= 20 && isset($_GET["numeroFCT"]) && $_GET["numeroFCT"] >= 2 && $_GET["numeroFCT"] <= ($_GET["numeroFCN"] / 2 + 1) ){
					$_SESSION["N"] = $_GET["numeroFCN"];
					$_SESSION["T"] = $_GET["numeroFCT"];

					$_SESSION["matrix"] = null;
					$_SESSION["matrixSelected"] = null;
					echo "<h3> Los valores ingresados son correctos </h3>";
					header("Location: juego.php");
				}
				else{
					echo "<h3> Los valores ingresados son invalidos </h3>";
				}
			}
		?>
	</form>
</body>
</html>