<?php
// Obtener el n vertices
$vertices = $_POST['vertices'];
$figure = '<svg width="300" height="300">';


for ($v = 3; $v <= $vertices; $v++) {
    $angle = 360 / $v;
    $radius = 140; 

    $figure .= '<polygon points="';

    // Calcular las coordenadas de los vertices
    for ($i = 0; $i < $v; $i++) {
        $x = 150 + $radius * cos(deg2rad($i * $angle));
        $y = 150 + $radius * sin(deg2rad($i * $angle));
        $figure .= $x . ',' . $y . ' ';
    }

    $figure .= '" style="fill:none;stroke:black;stroke-width:2" />'; 
}

$figure .= '</svg>';


echo $figure;
?>
