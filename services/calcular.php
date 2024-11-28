<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $shape = $_POST['shape'];  // Figura seleccionada
    $result = '';
    
    // Variables para cálculos
    $area = 0;
    $perimeter = 0;

    // Dependiendo de la figura, realizar los cálculos
    switch ($shape) {
        case 'circle':
            $radius = $_POST['radius'] ?? 0;  // Radio del círculo
            $area = pi() * pow($radius, 2);  // Área del círculo
            $perimeter = 2 * pi() * $radius;  // Perímetro del círculo
            $result = "Área: " . number_format($area, 2) . " unidades²<br>Perímetro: " . number_format($perimeter, 2) . " unidades";
            break;
        
        case 'square':
            $side = $_POST['side'] ?? 0;  // Lado del cuadrado
            $area = pow($side, 2);  // Área del cuadrado
            $perimeter = 4 * $side;  // Perímetro del cuadrado
            $result = "Área: " . number_format($area, 2) . " unidades²<br>Perímetro: " . number_format($perimeter, 2) . " unidades";
            break;
        
        case 'triangle':
            $base = $_POST['base'] ?? 0;  // Base del triángulo
            $height = $_POST['height'] ?? 0;  // Altura del triángulo
            $side1 = $_POST['side1'] ?? 0;  // Lado 1 del triángulo
            $side2 = $_POST['side2'] ?? 0;  // Lado 2 del triángulo
            $area = 0.5 * $base * $height;  // Área del triángulo
            $perimeter = $side1 + $side2 + $base;  // Perímetro del triángulo
            $result = "Área: " . number_format($area, 2) . " unidades²<br>Perímetro: " . number_format($perimeter, 2) . " unidades";
            break;
        
        case 'rectangle':
            $length = $_POST['length'] ?? 0;  // Longitud del rectángulo
            $width = $_POST['width'] ?? 0;  // Ancho del rectángulo
            $area = $length * $width;  // Área del rectángulo
            $perimeter = 2 * ($length + $width);  // Perímetro del rectángulo
            $result = "Área: " . number_format($area, 2) . " unidades²<br>Perímetro: " . number_format($perimeter, 2) . " unidades";
            break;
        
        case 'pentagon':
            $side = $_POST['side'] ?? 0;  // Lado del pentágono regular
            $area = (1 / 4) * sqrt(5 * (5 + 2 * sqrt(5))) * pow($side, 2);  // Fórmula del área de un pentágono regular
            $perimeter = 5 * $side;  // Perímetro del pentágono regular
            $result = "Área: " . number_format($area, 2) . " unidades²<br>Perímetro: " . number_format($perimeter, 2) . " unidades";
            break;

        default:
            $result = "Por favor, seleccione una figura.";
            break;
    }

    echo $result;
}
?>
