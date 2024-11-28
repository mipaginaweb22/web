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
    <title>Mi Página Segura</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        /* Contenedor principal */
        .container {
            display: flex;
            height: 100%;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Barra superior */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
            color: white;
        }

        /* Foto de perfil */
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Estilo para los botones en la barra superior */
        .buttons-container {
            display: flex;
            align-items: center;
            gap: 10px; /* Espacio entre los botones */
        }

        /* Botón de regresar */
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

        /* Responsividad */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .back-button {
                position: static;
                margin-top: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Contenedor principal -->
    <div class="container">
        <!-- Barra superior -->
        <div class="top-bar">
            <!-- Foto de perfil -->
            <img src="<?php echo htmlspecialchars($google_picture); ?>" alt="Foto de perfil" class="profile-pic">

            <!-- Contenedor de botones (Regresar) -->
            <div class="buttons-container">
                <!-- Botón de "Regresar" -->
                <button class="back-button" onclick="window.history.back();">Regresar</button>
            </div>
        </div>

        <!-- Contenido principal -->
        <h1>Bienvenido a tu página segura, <?php echo htmlspecialchars($google_name); ?>!</h1>
        <p>Tu correo es: <?php echo htmlspecialchars($google_email); ?></p>

        <!-- Sistema de Cálculo de Área y Perímetro -->
        <h2>Calcular Área y Perímetro de Figuras</h2>
        <button onclick="calculate()">Calcular Área y Perímetro</button>

        <script>
            // Ejemplo de objetos que simulan las figuras en el lienzo (puedes adaptarlo según tus necesidades)
            const figures = [
                { type: 'rect', width: 150, height: 100 },
                { type: 'circle', radius: 50 },
                { type: 'triangle', width: 100, height: 100 }
            ];

            // Función para calcular área y perímetro
            async function calculate() {
                const results = figures.map(fig => {
                    let area = 0;
                    let perimeter = 0;
                    switch (fig.type) {
                        case 'rect':
                            area = fig.width * fig.height;
                            perimeter = 2 * (fig.width + fig.height);
                            break;
                        case 'circle':
                            area = Math.PI * Math.pow(fig.radius, 2);
                            perimeter = 2 * Math.PI * fig.radius;
                            break;
                        case 'triangle':
                            // Suponiendo un triángulo rectángulo para simplificar
                            area = (fig.width * fig.height) / 2;
                            perimeter = fig.width + fig.height + Math.sqrt(Math.pow(fig.width, 2) + Math.pow(fig.height, 2));
                            break;
                    }
                    return { type: fig.type, area, perimeter };
                });

                alert(`Resultados:\n${results.map(r => `${r.type}: Área=${r.area.toFixed(2)}, Perímetro=${r.perimeter.toFixed(2)}`).join('\n')}`);
            }
        </script>
    </div>

</body>
</html>
