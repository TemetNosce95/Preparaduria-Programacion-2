<?php session_start();

define("tamano_matrix", 12);
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="index.css">
<meta charset="ANSI">
<head>
	<script type="text/javascript">
		function seleccionarCasilla(i,j){
			//alert("Seleccionada "+i+"   "+j);
			document.formulario3.destpI.value = i;
			document.formulario3.destpJ.value = j;
			document.formulario3.submit();
		}
	</script>
		<title>MEMORIA</title>
</head>
	
<body>
	<form action="" method="get" name="formulario1">
		<?php
			if(isset($_GET["button_juego_nuevo"])){
				$_SESSION["name"] = null;
				$_SESSION["matrix"] = null;
			}

			if(isset($_GET["destpJ"]) && isset($_GET["destpI"]) && isset($_SESSION["matrix"]) && $_SESSION["matrix"] != null){
				jugada($_GET["destpI"],$_GET["destpJ"]);
			}

			if(isset($_GET["button_next"]) &&  isset($_SESSION["matrix"]) && $_SESSION["matrix"] != null){
				for ($i=0; $i < tamano_matrix; $i++) 
					for ($j=0; $j < tamano_matrix; $j++)
						$_SESSION["matrix_visibility"][$i][$j] = 0;
				$_SESSION["jugada_valida"] = 0;
				$_SESSION["jugada_invalida"] = 0;
			}
			if(isset($_GET["button_enviar_name"])){
				if(isset($_GET["name_player"])){
					if($_GET["name_player"] == null)
						echo 'El nombre del jugador no puede estar vacío.';
					else
						$_SESSION["name"] = $_GET["name_player"];
				}
			}

			if(!isset($_SESSION["name"]) || $_SESSION["name"] == null){
				?>
				Ingrese su nombre para empezar la partida
				<input name="name_player" type="text" placeholder="Ingrese su nombre"></input>
				<input name="button_enviar_name" type="submit" value="Enviar">
				<?php
			}else{
				echo "Bienvenido ".$_SESSION["name"];
				if(!isset($_SESSION["matrix"]) || $_SESSION["matrix"] == null){
					creartablero();
					echo "ola k ase?";
				}
				?>
				<br>
				<br>
					<form name="formulario2" method="GET" action="">
						<input name="button_juego_nuevo" type="submit" value="Juego nuevo"></input>
						<table>
							<?php
								echo '<br>';echo '<br>';echo '<br>';
								echo 'Pares encontrados: '.$_SESSION["pares"].'     Intentos: '.$_SESSION["intentos"];
								for ($i=0; $i < tamano_matrix; $i++) { 
									echo '<tr>';
									for ($j=0; $j < tamano_matrix; $j++) { 
										echo '<td class="matrix_item_'.selectColor($_SESSION["matrix"][$i][$j],$i,$j).'" onclick="seleccionarCasilla('.$i.','.$j.')" >'.'</td>';
										//.$_SESSION["matrix"][$i][$j].
									}
									echo '</tr>';
								}
							?>
						</table>
					</form>
					<form name="formulario3" method="get" action="">
						<input type="hidden" name="destpI"></input>
						<input type="hidden" name="destpJ"></input>
					</form>

					<form name="formulario4" method="get" action="">
						<<?php 
							if(isset($_SESSION["jugada_valida"]) && $_SESSION["jugada_valida"] != 0 || isset($_SESSION["jugada_invalida"]) && $_SESSION["jugada_invalida"] != 0)
								echo '<input type="submit" name="button_next" value="PRESIONAME PARA ENVIAR PODER REALIZAR TU SIGUIENTE JUGADA"></input>';
						 ?>

					</form>
				<?php

					if(isset($_SESSION["jugada_valida"]) && $_SESSION["jugada_valida"] != 0)
						echo 'La última jugada fue válida, ahora tienes un par más en tus manos';

					if(isset($_SESSION["jugada_invalida"]) && $_SESSION["jugada_invalida"] != 0)
						echo 'La última jugada fue inválida, sigue buscando parejas';

					echo "<br><br><br>Para jugar oprime los rectángulos y busca los colores idénticos para formar pares, acumula todos los pares :D (atrápalos ya! POKEMÓN!).";
			}

		?>
	</form>
</body>
</html>

<?php

	function creartablero(){
		for ($i=0; $i < tamano_matrix; $i++) { 
			for ($j=0; $j < tamano_matrix; $j++) { 
				$_SESSION["matrix"][$i][$j] = rand(1,8);
				$_SESSION["matrix_visibility"][$i][$j] = 0;
			}
		}

		$_SESSION["pares"] = 0;
		$_SESSION["intentos"] = 0;
		$_SESSION["cont_destapadas_jugada"] = 0;
		$_SESSION["jugada"] = null;
		$_SESSION["jugada_valida"] = 0;
		$_SESSION["jugada_invalida"] = 0;
	}

	function getVisibility($i,$j){
		if(isset($_SESSION["matrix_visibility"]))
			if($_SESSION["matrix_visibility"][$i][$j] == 0)
				return 'false';
			else
				return 'true';
	}

	function selectColor($c,$i,$j){
		if(getVisibility($i,$j) == 'true')
			switch ($c) {
				case 1:
					return 'yellow';
					break;
				
				case 2:
					return 'blue';
					break;
				case 3:
					return 'red';
					break;
				case 4:
					return 'orange';
					break;
				case 5:
					return 'green';
					break;
				case 6:
					return 'purple';
					break;
				case 7:
					return 'cyan';
					break;
				case 8:
					return 'brown';
					break;
			}
		else
			if($c == 0)
				return 'white_true';
			else
				return 'white';
	}

	function destapar($i,$j){
		if($i < tamano_matrix && $j < tamano_matrix && $i>=0 && $j>=0){
			$_SESSION["matrix_visibility"][$i][$j] = 1;
		}
	}

	function jugada($i,$j){
		if($i < tamano_matrix && $j < tamano_matrix && $i>=0 && $j>=0){
			if(!(isset($_SESSION["jugada_valida"]) && $_SESSION["jugada_valida"] != 0 || isset($_SESSION["jugada_invalida"]) && $_SESSION["jugada_invalida"] != 0)){
				
				destapar($i,$j);
			

				if(!isset($_SESSION["cont_destapadas_jugada"]))
						$_SESSION["cont_destapadas_jugada"] = 0;

				if(isset($_SESSION["cont_destapadas_jugada"])){
					$_SESSION["jugada"][$_SESSION["cont_destapadas_jugada"]][0] = $i;
					$_SESSION["jugada"][$_SESSION["cont_destapadas_jugada"]][1] = $j;

					$_SESSION["cont_destapadas_jugada"]++;
					if($_SESSION["cont_destapadas_jugada"] > 1){
						$_SESSION["cont_destapadas_jugada"] = 0;
						$_SESSION["jugada_valida"] = 0;
						$_SESSION["jugada_invalida"] = 0;
						comprobar();
					}
				}
			}
		}
	}

	function comprobar(){
		if(($_SESSION["matrix"][$_SESSION["jugada"][0][0]][$_SESSION["jugada"][0][1]] == $_SESSION["matrix"][$_SESSION["jugada"][1][0]][$_SESSION["jugada"][1][1]]) && ($_SESSION["jugada"][0][0] != $_SESSION["jugada"][1][0] || $_SESSION["jugada"][0][1] != $_SESSION["jugada"][1][1])){
			$_SESSION["matrix"][$_SESSION["jugada"][0][0]][$_SESSION["jugada"][0][1]] = 0;
			$_SESSION["matrix"][$_SESSION["jugada"][1][0]][$_SESSION["jugada"][1][1]] = 0;
			$_SESSION["pares"]++;
			$_SESSION["jugada_valida"] = 1;
		}
		else{
			$_SESSION["jugada_invalida"] = 1;
		}

		$_SESSION["intentos"]++;
	}

?>