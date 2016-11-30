<?php 
session_start(); 
define("TAMANO_TABLERO", 4);

$_SESSION["colores_digitos_matrix"] = generarColores();
?>
<!DOCTYPE html>
<html>
<head>
	<title>2048, el juego del siglo</title>
</head>
<body>
	<div class="titulo_2048">2048</div>
	<div class="cuerpo_principal">
		<h1>¡Bienvenido a 2048!</h1>
		<p>Presiona las teclas direccionales o los botones con las direcciones</p>

		<form type="submit" method="get">
			<input type="submit" name="boton" value="derecha"></input>
			<input type="submit" name="boton" value="izquierda"></input>
			<input type="submit" name="boton" value="arriba"></input>
			<input type="submit" name="boton" value="abajo"></input>

			<?php
				if(isset($_GET["boton"])){
					if($_GET["boton"] == 'arriba'){
						moverMatrix_generarNuevo(1);
					}

					if($_GET["boton"] == 'abajo'){
						moverMatrix_generarNuevo(2);
					}

					if($_GET["boton"] == 'derecha'){
						moverMatrix_generarNuevo(3);
					}

					if($_GET["boton"] == 'izquierda'){
						moverMatrix_generarNuevo(4);
					}
				}
			?>

			<table>
				<?php

					loadMatrix();

					//print_r($_SESSION["matrix"]);
					echo "hola";
					for ($i=0; $i < TAMANO_TABLERO; $i++) { 
						echo '<tr>';
						for ($j=0; $j < TAMANO_TABLERO; $j++) { 
							echo '<td class="casilla_tablero" style = "padding: 20px; background-color: '.getcolor($_SESSION["matrix"][$i][$j]).';">'.$_SESSION["matrix"][$i][$j].'</td>';
						}
						echo '</tr>';
					}

				?>	
			</table>
			
		</form>
	</div>
</body>
</html>

<?php 
function loadMatrix(){
	if(!isset($_SESSION["matrix"])){
		for ($i=0; $i < TAMANO_TABLERO; $i++) { 
			for ($j=0; $j < TAMANO_TABLERO; $j++) { 
				$matrix[$i][$j] = 0;
			}
		}

		for ($i=0; $i < 2; $i++) { 
			do{
				$x = rand(0,TAMANO_TABLERO);
				$y = rand(0,TAMANO_TABLERO);
			}while($matrix[$x][$y] != 0);

			$matrix[$x][$y] = 2;

		}

		$_SESSION["matrix"] = $matrix;

	}
}

function calcularColor($i,$j){

}

function generarColores(){
	$colors[0] ='#bdc3c7';
	$colors[2]='#1abc9c';
	$colors[4]='#2ecc71';
	$colors[8]='#3498db';
	$colors[16]='#9b59b6';
	$colors[32]='#e74c3c';
	$colors[64]='#c0392b';
	$colors[128]='#e67e22';
	$colors[256]='#f1c40f';
	$colors[512]='#f39c12';
	$colors[1024]='#2c3e50';
	$colors[2048]='#7f8c8d';

	return $colors;
}

function getcolor($value){
	if($value >=0 && $value % 2 == 0 && $value <= 2048 && log($value,2) % 1 == 0){
		return $_SESSION["colores_digitos_matrix"][$value];
	}
}

function moverMatrix_generarNuevo($direccion){
	for ($i=0; $i < TAMANO_TABLERO; $i++) { 
		for ($j=0; $j < TAMANO_TABLERO; $j++) { 
			if($_SESSION["matrix"][$i][$j] != 0){
				switch ($direccion) {
					case 1://arriba
						for ($k=$i; $k > 0 ; $k--) { 
							if($_SESSION["matrix"][$k-1][$j] != 0)// && $_SESSION["matrix"][$k-1][$j] != $_SESSION["matrix"][$k][$j])
								break;
							else{
								$aux = $_SESSION["matrix"][$k-1][$j];
								$_SESSION["matrix"][$k-1][$j] = $_SESSION["matrix"][$k][$j];
								$_SESSION["matrix"][$k][$j] = $aux;
							}
						}
						break;
					
					case 2://abajo
						for ($k=$i; $k < TAMANO_TABLERO ; $k++) { 
							if($_SESSION["matrix"][$k+1][$j] != 0)// && $_SESSION["matrix"][$k+1][$j] != $_SESSION["matrix"][$k][$j])
								break;
							else{
								$aux = $_SESSION["matrix"][$k+1][$j];
								$_SESSION["matrix"][$k+1][$j] = $_SESSION["matrix"][$k][$j];
								$_SESSION["matrix"][$k][$j] = $aux;
							}
						}
						break;
					case 3://derecha
						for ($k=$j; $k > 0 ; $k--) { 
							if($_SESSION["matrix"][$i][$k-1] != 0)// && $_SESSION["matrix"][$i][$k-1] != $_SESSION["matrix"][$k][$j])
								break;
							else{
								$aux = $_SESSION["matrix"][$i][$k-1];
								$_SESSION["matrix"][$i][$k-1] = $_SESSION["matrix"][$i][$k];
								$_SESSION["matrix"][$i][$k] = $aux;
							}
						}
						break;
					case 4://izquierda
						for ($k=$j; $k < TAMANO_TABLERO ; $k++) { 
							if($_SESSION["matrix"][$i][$k+1] != 0)// && $_SESSION["matrix"][$i][$k+1] != $_SESSION["matrix"][$k][$j])
								break;
							else{
								$aux = $_SESSION["matrix"][$i][$k+1];
								$_SESSION["matrix"][$i][$k+1] = $_SESSION["matrix"][$i][$k];
								$_SESSION["matrix"][$i][$k] = $aux;
							}
						}
						break;
				}
			}
		}
	}
}
?>