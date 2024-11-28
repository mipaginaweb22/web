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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Página Segura - Resolver Sistema de Ecuaciones</title>
    <link rel="stylesheet" href="assets/css/resolver.css">
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }

        .form-container {
            width: 48%;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
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

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
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

        /* Estilo para el contenedor de figuras dentro de un círculo azul */
        .circle-container {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background-color: #007bff; /* Azul */
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto;
            overflow: hidden; /* Asegura que cualquier contenido fuera del círculo se oculte */
        }

        #figure {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            overflow: hidden;
        }

        /* Asegura que las figuras generadas tengan un tamaño adecuado */
        svg, canvas {
            max-width: 100%;
            max-height: 100%;
        }

        /* Estilo para el tercer cuadro con barra de opciones */
        .options-container {
            width: 48%;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        select {
            width: 100%;
        }

        /* Estilos adicionales para el texto y el botón de suscripción */
        .subscription-container {
            background-color: #f9f9f9;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }

        .subscription-container p {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .subscribe-button {
            padding: 10px 20px;
            background-color: #ff8c00;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .subscribe-button:hover {
            background-color: #e07b00;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <img src="<?php echo htmlspecialchars($google_picture); ?>" alt="Foto de perfil" class="profile-pic">
    <div class="buttons-container">
        <button class="back-button" onclick="window.location.href='profile.php';">Regresar</button>
    </div>
</div>


    <!-- Contenedor de suscripción debajo de la barra de menú -->
    <div class="subscription-container">
        <p>Estos son ejercicios sencillos que puedes utilizar para ayudarte en tus tareas. Si deseas adquirir más librerías con ejercicios, fórmulas y soluciones más complejas, suscríbete.</p>
        <button class="subscribe-button" onclick="window.location.href='planes.php';">Suscribirse</button>
    </div>

    <div class="container">
        <!-- Formulario para resolver el sistema de ecuaciones 2x2 -->
        <div class="form-container">
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
        </div>

        <!-- Generador de Figuras -->
        <div class="form-container">
            <h1>Generador de Figuras</h1>
            <form id="figureForm">
                <label for="vertices">Número de vértices:</label>
                <select name="vertices" id="vertices">
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <input type="submit" value="Generar">
            </form>
            <!-- Contenedor circular azul para mostrar las figuras -->
            <div class="circle-container">
                <div id="figure"></div>
            </div>
        </div>

        <!-- Tercer cuadro con la barra de selección de figuras geométricas -->
        <div class="options-container">
            <h1>Seleccionar Figura Geométrica</h1>
            <form id="shapeForm" method="POST" action="calcular.php">
                <label for="shape">Elija una figura:</label>
                <select id="shape" name="shape">
                    <option value="circle">Círculo</option>
                    <option value="square">Cuadrado</option>
                    <option value="triangle">Triángulo</option>
                    <option value="rectangle">Rectángulo</option>
                    <option value="pentagon">Pentágono</option>
                </select>
                <!-- Botón Calcular -->
                <button type="submit" id="calculateButton">Calcular</button>
            </form>
            <!-- Contenedor para mostrar el resultado debajo del botón -->
            <div id="result"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#figureForm').on('submit', function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de forma tradicional
                $.post('generate.php', $(this).serialize(), function(data) {
                    $('#figure').html(data); // Muestra la figura generada en el contenedor circular
                });
            });
        });
    </script>
</body>
</html>
