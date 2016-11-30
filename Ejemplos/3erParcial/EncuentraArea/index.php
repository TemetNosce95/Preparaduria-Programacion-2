<?php session_start(); ?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="index.css">
<meta charset="ANSI">
<head>
	<title>Encuentra el área</title>

	<script type="text/javascript" language="javascript">
		function presionado(i,j){
			
			document.form_puntos.posI.value = i;
			document.form_puntos.posJ.value = j;
			document.form_puntos.submit();
		}
	</script>
</head>
<body>
<h1>
	Encuentra el área
</h1>
	<?php
		if(isset($_GET["button_reinciar_juego"]))
			juegoNuevo();

		if(isset($_GET["button_comenzar_juego"]))
			comenzarJuego();
		if(isset($_GET["input_text_filas"]) && isset($_GET["input_text_columnas"])){
			$n = (int)$_GET["input_text_filas"];
			$m = (int)$_GET["input_text_columnas"];

			if($n >= 5 && $n <= 15 && $m>=5 && $m<=15){
				$_SESSION["tamanoMatrixN"] = $n;
				$_SESSION["tamanoMatrixM"] = $m;
				$_SESSION["numero_puntos"] = $_GET["numero_puntos"];
				crearMatriz();
			}
			else
				echo '<h2>Los datos ingresados son inválidos</h2>';
		}
		if(!isset($_SESSION["matrizcreada"]) || $_SESSION["matrizcreada"] == null){
			?>
			<br>
			Para poder jugar debe crear la matriz de juego con N filas y M columnas, N y M no pueden ser menores a 5 ni mayores a 15.
			<br>
			<form name="formulario1" method="get" action="">
				<input type="text" name="input_text_filas" placeholder="Numero de filas"></input>
				<input type="text" name="input_text_columnas" placeholder="Numero de columnas"></input>
				<select name="numero_puntos">
					<option value="3">3</option>
					<option value="4">4</option>
				</select>
				<input type="submit" name="button_enviar_formulario1" value="Enviar"></input>
			</form>
			<?php
		}else{
			if(!isset($_SESSION["seleccion_area_realizada"]) || $_SESSION["seleccion_area_realizada"] == null){
				if(isset($_GET["posI"]) && isset($_GET["posJ"])){
					if(isset($_SESSION["cont_puntos_A"])){

						$_SESSION["puntos"][$_SESSION["cont_puntos_A"]][0] = (int)$_GET["posI"];
						$_SESSION["puntos"][$_SESSION["cont_puntos_A"]][1] = (int)$_GET["posJ"];
						$_SESSION["matrix"][(int)$_GET["posI"]][(int)$_GET["posJ"]] = 1;
						$_SESSION["cont_puntos_A"]++;
						print_r($_SESSION["puntos"]);
					}
					if(!isset($_SESSION["cont_puntos_A"]) || $_SESSION["cont_puntos_A"] >= $_SESSION["numero_puntos"]){
						$_SESSION["cont_puntos_A"] = 0;
						if(comprobarPuntos() == true){
							$_SESSION["seleccion_area_realizada"] = true;
						}else{
							$_SESSION["puntos"] = null;
							crearMatriz();
						}
					}
				}
				?>
					<br>
					<form name="formulario2" method="get" action="">

						<?php
							echo "Seleccione en la matriz los ".$_SESSION["numero_puntos"]." puntos para formar el area. (DEBE SER RECTANGULAR)";

							if($_SESSION["numero_puntos"] == 3 && !isset($_SESSION["seleccion_area_realizada"] ) || $_SESSION["seleccion_area_realizada"] != null){
									echo "<h3>Debe seleccionar los puntos en el siguiente orden. <br> 1) Esquina superior izquiera  del rectangulo.<br> 2) Esquina inferior izquierda del rectangulo. <br> 3) Esquina superior derecha  del rectangulo.</h3>";
							}else{
								if(!isset($_SESSION["seleccion_area_realizada"] ) || $_SESSION["seleccion_area_realizada"] != null)
									echo "<h3>Debe seleccionar los puntos en el siguiente orden. <br> 1) Esquina superior izquiera  del rectangulo.<br> 2) Esquina inferior izquierda del rectangulo. <br> 3) Esquina superior derecha  del rectangulo. <br> 3) Esquina inferior derecha  del rectangulo. </h3>";
							}
						?>
					</form>


				<?php
			}
			if(isset($_GET["posI"]) && isset($_GET["posJ"]) && isset($_SESSION["seleccion_area_realizada"]) && $_SESSION["seleccion_area_realizada"] != null){

				$i = $_GET["posI"];
				$j = $_GET["posJ"];

				if(!isset($_SESSION["puntosDescubiertos"]))
					$_SESSION["puntosDescubiertos"] = 0;

				if($_SESSION["matrix"][$i][$j] == 7){
					$_SESSION["matrix"][$i][$j] = 2;
					$_SESSION["puntosDescubiertos"] += 1;
				}
				if($_SESSION["matrix"][$i][$j] == 8){
					$_SESSION["matrix"][$i][$j] = 3;
					$_SESSION["puntosDescubiertos"] += 1;
				}
				if($_SESSION["matrix"][$i][$j] == 0)
					$_SESSION["matrix"][$i][$j] = 4;
				
				if($_SESSION["cantidadDePuntos"] * 0.6 <= $_SESSION["puntosDescubiertos"])
					echo "<br><h1>Ha ganado!!!!!! :D</h1>";
				if($_SESSION["oportunidades"] <= 0.0)
					echo "<br><h1>Ha perdido!!!!!! :(</h1>";

				$_SESSION["oportunidades"]--;
				echo "<br><h3>Ha descubierto ".$_SESSION["puntosDescubiertos"]."Le restan ".$_SESSION["oportunidades"]." para descubrir al menos el 60% del area </h3>";
			}
			?>
			<br><br>
						<table>
							<?php
							
								for ($i=0; $i < $_SESSION["tamanoMatrixN"]; $i++) { 
									echo "<tr>";
									for ($j=0; $j < $_SESSION["tamanoMatrixM"]; $j++) { 
										echo '<td class="click_'.clicked($i,$j).'" onclick="presionado('.$i.','.$j.')" >'.$_SESSION["matrix"][$i][$j].'</td>';
									}
									echo "</tr>";
								}
							?>
						</table>
			?>
			<form name="form_puntos" method="get" action="">
						<input type="hidden" name="posI"></input>
						<input type="hidden" name="posJ"></input>
						<input type="submit" name="button_reinciar_juego" value="Reiniciar juego"></input>
						<?php
						echo 'SArea= '.$_SESSION["seleccion_area_realizada"];
						if(isset($_SESSION["seleccion_area_realizada"] ) && $_SESSION["seleccion_area_realizada"] != null)
						 echo '<input type="submit" name="button_comenzar_juego" value="Comenzar juego"></input>';
						?>
					</form>
					<?php
		}
	?>
</body>
</html>

<?php
	function crearMatriz(){
		for ($i=0; $i < $_SESSION["tamanoMatrixN"]; $i++) { 
			for ($j=0; $j < $_SESSION["tamanoMatrixM"]; $j++) { 
				$_SESSION["matrix"][$i][$j]	= 0;
			}
		}

		$_SESSION["matrizcreada"] = 2387467836458;
		$_SESSION["oportunidades"] = (int)$_SESSION["tamanoMatrixN"]*$_SESSION["tamanoMatrixM"]*$_SESSION["numero_puntos"]/10;//puntos
	}

	function juegoNuevo(){
		$_SESSION["seleccion_area_realizada"] = null;
		$_SESSION["matrizcreada"] = null;
		$_SESSION["puntos"] = null;
		$_SESSION["comenzar_juego"] = null;
		$_SESSION["puntosDescubiertos"] = 0;
	}

	function clicked($i,$j){
		return $_SESSION["matrix"][$i][$j].'';
	}
	//
	function comprobarPuntos(){
		if($_SESSION["numero_puntos"] == 3){
			if(isset($_SESSION["puntos"]) || $_SESSION["puntos"]!= null)
			if($_SESSION["puntos"][0][0] == $_SESSION["puntos"][2][0] && $_SESSION["puntos"][0][1] == $_SESSION["puntos"][1][1]){
				echo "<br><h2>Seleccion valida. Presione el boton de Comenzar juego</h2>";

				$i1 = $_SESSION["puntos"][0][0]; $j1= $_SESSION["puntos"][0][1];
				$_SESSION["matrix"][$i1][$j1] = 2;

				$i2 = $_SESSION["puntos"][1][0]; $j2= $_SESSION["puntos"][1][1];
				$_SESSION["matrix"][$i2][$j2] = 2;

				$i3 = $_SESSION["puntos"][2][0]; $j3= $_SESSION["puntos"][2][1];
				$_SESSION["matrix"][$i3][$j3] = 2;

				$_SESSION["cantidadDePuntos"] = 3;
				for ($i=$i1; $i <= $i2; $i++) { 
					for ($j=$j1; $j <= $j3; $j++) { 
						if($_SESSION["matrix"][$i][$j] != 0)
							continue;
						else{
							$_SESSION["matrix"][$i][$j] = 3;
							$_SESSION["cantidadDePuntos"]++;
						}
					}
				}

				return true;
			}
			else
				echo "<br><h2>Seleccion invalida Presione el boton de Reiniciar juego</h2>";
		}else{
			if(isset($_SESSION["puntos"]) || $_SESSION["puntos"]!= null)
			if($_SESSION["puntos"][0][0] == $_SESSION["puntos"][2][0] && $_SESSION["puntos"][0][1] == $_SESSION["puntos"][1][1] && $_SESSION["puntos"][1][0] == $_SESSION["puntos"][3][0] && $_SESSION["puntos"][2][1] == $_SESSION["puntos"][3][1]){
				echo "<br><h2>Seleccion valida. Presione el boton de Comenzar juego</h2>";

				$i1 = $_SESSION["puntos"][0][0]; $j1= $_SESSION["puntos"][0][1];
				$_SESSION["matrix"][$i1][$j1] = 2;

				$i2 = $_SESSION["puntos"][1][0]; $j2= $_SESSION["puntos"][1][1];
				$_SESSION["matrix"][$i2][$j2] = 2;

				$i3 = $_SESSION["puntos"][2][0]; $j3= $_SESSION["puntos"][2][1];
				$_SESSION["matrix"][$i3][$j3] = 2;

				$i4 = $_SESSION["puntos"][3][0]; $j4= $_SESSION["puntos"][3][1];
				$_SESSION["matrix"][$i4][$j4] = 2;

				$_SESSION["cantidadDePuntos"] = 4;

				for ($i=$i1; $i <= $i2; $i++) { 
					for ($j=$j1; $j <= $j3; $j++) { 
						if($_SESSION["matrix"][$i][$j] != 0)
							continue;
						else{
							$_SESSION["matrix"][$i][$j] = 3;
							$_SESSION["cantidadDePuntos"]++;
						}
					}
				}

				return true;
			}
			else
				echo "<br><h2>Seleccion invalida Presione el boton de Reiniciar juego</h2>";
		}

		return false;
	}

	/*function mostrar($i,$j){
		if($_SESSION["matrix"][$i][$j] == 7 || $_SESSION["matrix"][$i][$j] == 8)
			return 'hidden';
		else{
			return '';
		}
	}*/

	function comenzarJuego(){
		for ($i=0; $i < $_SESSION["tamanoMatrixN"]; $i++) { 
					for ($j=0; $j < $_SESSION["tamanoMatrixM"]; $j++) { 
						switch ($_SESSION["matrix"][$i][$j]) {
							case 2:
								$_SESSION["matrix"][$i][$j] = 7;
								break;
							case 3:
								$_SESSION["matrix"][$i][$j] = 8;
								break;
							
						}
					}
				}
	}
?>