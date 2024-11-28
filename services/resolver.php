<?php
// Inicializa la sesión. Es necesario para verificar el estado de inicio de sesión.
session_start();

// Verifica si el usuario ha iniciado sesión, si no, redirige a la página de inicio de sesión.
if (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Recupera las variables de sesión de forma segura
$google_loggedin = $_SESSION['google_loggedin'];
$google_email = $_SESSION['google_email'];
$google_name = $_SESSION['google_name'];
$google_picture = $_SESSION['google_picture'];

$resultado = ""; // Variable para almacenar el resultado

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a1 = $_POST['a1'];
    $b1 = $_POST['b1'];
    $c1 = $_POST['c1'];
    $a2 = $_POST['a2'];
    $b2 = $_POST['b2'];
    $c2 = $_POST['c2'];
    $metodo = $_POST['metodo'];

    // Función para resolver mediante determinantes
    function determinantes($a1, $b1, $c1, $a2, $b2, $c2) {
        $D = $a1 * $b2 - $a2 * $b1;
        if ($D == 0) {
            return "El sistema no tiene solución única.";
        } else {
            $Dx = $c1 * $b2 - $c2 * $b1;
            $Dy = $a1 * $c2 - $a2 * $c1;
            $x = $Dx / $D;
            $y = $Dy / $D;
            return "Solución: x = " . round($x, 2) . ", y = " . round($y, 2);
        }
    }

    // Método de suma y resta
    function sumaYresta($a1, $b1, $c1, $a2, $b2, $c2) {
        if ($b1 != 0) {
            $k = $b2 / $b1; // Factor para igualar coeficientes de y
            $new_c1 = $c1 * $k; // Modificamos c1
            $new_a2 = $a2 * $k; // Modificamos a2
            
            if ($new_a2 == $a1) {
                return "El sistema no tiene solución única.";
            }

            $x = ($new_c1 - $c2) / ($a1 - $new_a2);
            $y = ($c1 - $a1 * $x) / $b1;

            return "Solución: x = " . round($x, 2) . ", y = " . round($y, 2);
        }
        return "No se puede resolver, coeficiente en y es cero.";
    }

    // Método de igualación
    function igualacion($a1, $b1, $c1, $a2, $b2, $c2) {
        if ($b1 != 0 && $b2 != 0) {
            $x = ($c1 - $b1 * ($c1 / $b1)) / $a1; 
            $y1 = ($c1 - $a1 * $x) / $b1;
            $y2 = ($c2 - $a2 * $x) / $b2;
            
            if (round($y1, 2) == round($y2, 2)) {
                return "Solución: x = " . round($x, 2) . ", y = " . round($y1, 2);
            } else {
                return "El sistema no tiene solución única.";
            }
        }
        return "No se puede resolver, coeficientes en y son cero.";
    }

    // Método de sustitución
    function sustitucion($a1, $b1, $c1, $a2, $b2, $c2) {
        if ($b1 != 0) {
            $y = ($c1 - $a1 * ($c2 - $b2 * ($c1 / $b1))) / $b1; 
            $x = ($c2 - $b2 * $y) / $a2;

            return "Solución: x = " . round($x, 2) . ", y = " . round($y, 2);
        }
        return "No se puede resolver, coeficiente en y es cero.";
    }

    // Selección del método
    switch ($metodo) {
        case 'determinantes':
            $resultado = determinantes($a1, $b1, $c1, $a2, $b2, $c2);
            break;
        case 'suma_resta':
            $resultado = sumaYresta($a1, $b1, $c1, $a2, $b2, $c2);
            break;
        case 'igualacion':
            $resultado = igualacion($a1, $b1, $c1, $a2, $b2, $c2);
            break;
        case 'sustitucion':
            $resultado = sustitucion($a1, $b1, $c1, $a2, $b2, $c2);
            break;
        default:
            $resultado = "Método no implementado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Página Segura - Resolver Sistema de Ecuaciones</title>
    <link rel="stylesheet" href="assets/css/resolver.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            font-family: Arial, sans-serif;
            height: 100%;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        /* Barra superior fija */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000; /* Asegura que la barra esté por encima de otros elementos */
            width: 100%;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .buttons-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        .resultado {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Barra superior fija -->
    <div class="top-bar">
        <img src="<?php echo htmlspecialchars($google_picture); ?>" alt="Foto de perfil" class="profile-pic">
        <div class="buttons-container">
            <button class="back-button" onclick="window.history.back();">Regresar</button>
        </div>
    </div>

    <div class="container">
        <!-- Formulario para resolver el sistema de ecuaciones 2x2 -->
        <h1>Resolver Sistema de Ecuaciones 2x2</h1>
        <form action="resolver.php" method="post">
            <div>
                <label for="a1">Ecuación 1: a1*x + b1*y = c1</label>
                <input type="number" name="a1" required> *
                <input type="number" name="b1" required> *
                <input type="number" name="c1" required>
            </div>
            <div>
                <label for="a2">Ecuación 2: a2*x + b2*y = c2</label>
                <input type="number" name="a2" required> *
                <input type="number" name="b2" required> *
                <input type="number" name="c2" required>
            </div>
            <div>
                <label for="metodo">Método de resolución:</label>
                <select name="metodo" required>
                    <option value="suma_resta">Suma y Resta</option>
                    <option value="igualacion">Igualación</option>
                    <option value="sustitucion">Sustitución</option>
                    <option value="determinantes">Determinantes</option>
                </select>
            </div>
            <button type="submit">Resolver</button>
        </form>

        <!-- Mostrar el resultado debajo del formulario -->
        <?php if (!empty($resultado)): ?>
            <div class="resultado">
                <h2>Resultado:</h2>
                <p><?php echo $resultado; ?></p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>
