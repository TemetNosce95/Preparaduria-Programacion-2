<?php session_start(); const TAMANO_TABLERO = 4;?>
<<!DOCTYPE html>
<html>
<head>
	<title>2048, el juego del siglo</title>
</head>
<body>
	<div class="titulo_2048">2048</div>
	<div class="cuerpo_principal">
		<h1>¡Bienvenido a 2048!</h1>
		<p>Presiona las teclas direccionales o los botones con las direcciones</p>
	</div>
</body>
</html>

<?php 
function loadMatrix(){
	if(!isset($_SESION["matrix"])){
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
	}
}

?>