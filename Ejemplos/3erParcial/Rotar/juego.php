<?php session_start(); ?>
<!DOCTYPE html>
<meta charset="ANSI">
<link rel="stylesheet" type="text/css" href="juego.css">
<html>
<head>
	<title>ROTAR - JUEGO</title>

	<script type="text/javascript">
		function seleccionarCuadro(i,j,n,t){
			if((i + t - 1) < n && (j + t - 1) < n){
				document.form2.casillaI.value = i;
				document.form2.casillaJ.value = j;
				document.form2.submit();
			}else{
				alert("La posicion seleccionada se encuentra muy cerca de el(los) borde(s) inferior/derecho. Una submatriz de TxT no cabe allí. Movimiento inválido.");
			}
		}
	</script>
</head>
<body>
	<h1>Rotar</h1>
	<h2>Juego</h2>

	<?php
		if(isset($_GET["button_reingresarNyT"]))
			header("Location: index.php");

		if(isset($_GET["casillaJ"]) && isset($_GET["casillaI"])){

			if(!isset($_SESSION["algoSeleccionado"]) || $_SESSION["algoSeleccionado"] == 0)
				generarSubMatrix($_GET["casillaI"],$_GET["casillaJ"]);

		}
		if(!isset($_SESSION["matrix"]) || $_SESSION["matrix"] == null || isset($_GET["button_recargarTablero"]))
			cargarMatrix();

		if(isset($_GET["button_deseleccionar"])){
			$_SESSION["rotacionSatisfactoria"] = 0;
			anularSeleccion();
		}

		if(isset($_GET["radio_direccion"])){
			if(!(isset($_SESSION["rotacionSatisfactoria"]) && $_SESSION["rotacionSatisfactoria"] != 0))
				if($_GET["radio_direccion"] == 'derecha'){
					rotarDerecha();
				}
				else{
					rotarIzquierda();
				}
		}
		evaluarGanador();
	?>
	<div class="formularios">
		<form name="form1" method="get" action="">
			<input type="submit" name="button_recargarTablero" value= "Reiniciar tablero (conservando N y T anteriores)"></input>
			<input type="submit" name="button_reingresarNyT" value= "Reingresar N y T"></input>
			<input type="submit" name="button_deseleccionar" value="Anular seleccion/Siguiente movimiento(Luego de rotar)"></input>
		</form>
		<form name="form3">
			<?php
				if(isset($_SESSION["algoSeleccionado"]) && $_SESSION["algoSeleccionado"] != 0){
					?>
					Seleccione el sentido de rotacion, por defecto aunque ud no seleccione nada es hacia derecha. <br>
					<input type="radio" name="radio_direccion" value="derecha" checked="checked">Derecha</input>
					<input type="radio" name="radio_direccion" value="izquierda">Izquierda</input>
					<input type="submit" name="botonRotarSentido" value="Rotar"></input>
					<?php
				}
			?>
		</form>
		<form name="form2">
			<input type="hidden" name="casillaI"></input>
			<input type="hidden" name="casillaJ"></input>
		</form>
	</div>
	<div class="div_matrix">
		
		<table>
			<?php
				if($_SESSION["gano"] == 0){
					echo '<h3>Rotaciones realizadas hacia la derecha:'.$_SESSION["rotacionesDerecha"].'      Rotaciones realizadas hacia la izquierda:'.$_SESSION["rotacionesIzquierda"].'</h3><br><br>';
					for ($i=0; $i < $_SESSION["N"]; $i++) { 
						echo '<tr>';
						for ($j=0; $j < $_SESSION["N"]; $j++) { 
							echo '<td class="'.getSelected($i,$j).'" onclick="seleccionarCuadro('.$i.','.$j.','.$_SESSION["N"].','.$_SESSION["T"].')">'.$_SESSION["matrix"][$i][$j].'</td>';
						}
						echo '</tr>';
					}

					if((isset($_SESSION["rotacionSatisfactoria"]) && $_SESSION["rotacionSatisfactoria"] != 0))
						echo '<br><br> <h3>Presione Siguiente Movimiento para continuar la partida </h3>';

				/*if(isset($_SESSION["submatrix"]) && $_SESSION["submatrix"] != null)
					for ($i=0; $i < $_SESSION["T"]; $i++) { 
						echo '<tr>';
						for ($j=0; $j < $_SESSION["T"]; $j++) { 
							echo '<td >'.$_SESSION["submatrix"][$i][$j].'</td>';
						}
						echo '</tr>';
					}

				if(isset($_SESSION["nuevaSubmatrix"]))
					for ($i=0; $i < $_SESSION["T"]; $i++) { 
						echo '<tr>';
						for ($j=0; $j < $_SESSION["T"]; $j++) { 
							echo '<td >'.$_SESSION["nuevaSubmatrix"][$i][$j].'</td>';
						}
						echo '</tr>';
					}*/

				
				//print_r($_SESSION["submatrix"]);
				}else{
					echo '<h1>GANOOOOOOOOOOOOOOOOOOOOOOOOOOO LA PARTIDA DE ROTAR</h1>';
				}
			?>
		</table>
	</div>
</body>
</html>

<?php
	function cargarMatrix(){
		for ($i=0; $i < $_SESSION["N"]; $i++) { 
			for ($j=0; $j < $_SESSION["N"]; $j++) { 
				$_SESSION["matrix"][$i][$j] = 0;
				$_SESSION["matrixSelected"][$i][$j] = 0;
			}
		}

		for ($p=1; $p <= $_SESSION["N"]*$_SESSION["N"]; $p++) { 
			do{
				$y = rand(0,$_SESSION["N"]-1);
				$x =rand(0,$_SESSION["N"]-1);
			}while($_SESSION["matrix"][$y][$x] != 0);

			$_SESSION["matrix"][$y][$x] = $p;
		}

		$_SESSION["rotacionesIzquierda"] = 0;
		$_SESSION["rotacionesDerecha"] = 0;
		$_SESSION["gano"] = 0;
	}
	function getSelected($i,$j){
		if(isset($_SESSION["matrixSelected"]))
			if($_SESSION["matrixSelected"][$i][$j] == 0)
				return 'normal';
			else
				if($_SESSION["matrixSelected"][$i][$j] == 1)
					return 'selected';
				else
					return 'rotated';
	}

	function evaluarGanador(){
		$cont = 1;
		for ($i=0; $i < $_SESSION["N"]; $i++) { 
			for ($j=0; $j < $_SESSION["N"]; $j++) { 
				if($_SESSION["matrix"][$i][$j] == $cont){
					$cont++;
					continue;
				}
				else{
					return 0;
				}
			}
		}
		$_SESSION["gano"] = 1;
	}

	function generarSubMatrix($i1,$j1){
		$_SESSION["pi"] = $i1;
		$_SESSION["pj"] = $j1;

		for ($i=$_SESSION["pi"]; $i < $_SESSION["pi"]+$_SESSION["T"]; $i++) { 
			for ($j=$_SESSION["pj"]; $j < $_SESSION["pj"]+$_SESSION["T"]; $j++) { 
				$_SESSION["matrixSelected"][$i][$j] = 1;
				$_SESSION["submatrix"][$i - $_SESSION["pi"]][$j - $_SESSION["pj"]] = $_SESSION["matrix"][$i][$j];
			}
		}

		$_SESSION["algoSeleccionado"] = 1;
	}

	function anularSeleccion(){
		if(isset($_SESSION["algoSeleccionado"]) || $_SESSION["algoSeleccionado"] != 0){
			for ($i=0; $i < $_SESSION["N"]; $i++) { 
				for ($j=0; $j < $_SESSION["N"]; $j++) { 
					$_SESSION["matrixSelected"][$i][$j] = 0;
				}
			}
			$_SESSION["algoSeleccionado"] = 0;
			$_SESSION["submatrix"] = null;
		}
	}

	function rotarDerecha(){
		for ($i=0; $i < $_SESSION["T"]; $i++) { 
			for ($j=0; $j < $_SESSION["T"]; $j++) { 
				$_SESSION["nuevaSubmatrix"][$j][$_SESSION["T"] - $i - 1] = $_SESSION["submatrix"][$i][$j];
			}
		}


		for ($i=$_SESSION["pi"]; $i < $_SESSION["pi"]+$_SESSION["T"]; $i++) { 
			for ($j=$_SESSION["pj"]; $j < $_SESSION["pj"]+$_SESSION["T"]; $j++) { 
				$_SESSION["matrix"][$i][$j] = $_SESSION["nuevaSubmatrix"][$i - $_SESSION["pi"]][$j - $_SESSION["pj"]];
				$_SESSION["matrixSelected"][$i][$j] = 2;
			}
		}
		$_SESSION["rotacionSatisfactoria"] = 1;
		$_SESSION["rotacionesDerecha"]++;
	}

	function rotarIzquierda(){
		for ($i=0; $i < $_SESSION["T"]; $i++) { 
			for ($j=0; $j < $_SESSION["T"]; $j++) { 
				$_SESSION["nuevaSubmatrix"][$_SESSION["T"] - $j - 1][$i] = $_SESSION["submatrix"][$i][$j];
			}
		}

		for ($i=$_SESSION["pi"]; $i < $_SESSION["pi"]+$_SESSION["T"]; $i++) { 
			for ($j=$_SESSION["pj"]; $j < $_SESSION["pj"]+$_SESSION["T"]; $j++) { 
				$_SESSION["matrix"][$i][$j] = $_SESSION["nuevaSubmatrix"][$i - $_SESSION["pi"]][$j - $_SESSION["pj"]];
				$_SESSION["matrixSelected"][$i][$j] = 2;
			}
		}
		$_SESSION["rotacionSatisfactoria"] = 1;
		$_SESSION["rotacionesIzquierda"]++;
	}
?>