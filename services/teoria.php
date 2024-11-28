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

        /* Botón de descarga PDF */
        .download-button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .download-button:hover {
            background-color: #218838;
        }

        /* Botón de comprar libros */
        .buy-button {
            padding: 10px 20px;
            background-color: #FF5733; /* Color para el botón de comprar */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .buy-button:hover {
            background-color: #c13d21; /* Color al pasar el mouse */
        }

        /* Contenedor principal de la barra lateral (con imágenes) */
        .main-content-container {
            display: flex;
            flex-grow: 1;
            padding: 20px;
        }

        /* Lado izquierdo (rectángulo con imágenes y el cuadrado) */
        .left-bar {
            width: 500px;
            height: 100%;
            background-color: #333;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            position: relative;
            flex-direction: column;
            overflow: hidden;
        }

        /* Contenedor de imágenes */
        .image-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        /* Animaciones para las imágenes */
        #image1 { opacity: 1; animation: fadeIn 3s forwards; }
        #image2 { animation: fadeIn 3s 3s forwards; }
        #image3 { animation: fadeIn 3s 6s forwards; }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .text-box {
            margin-left: 50px;
            margin-top: 20px;
            width: 850px;
            height: auto;
            background-color: #ffffff;
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            line-height: 1.8;
            overflow-y: auto;
            max-height: 600px;
            white-space: normal;
            word-wrap: break-word;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .text-box h2 {
            color: #007BFF;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .text-box h3 {
            color: #444;
            font-size: 20px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .text-box p {
            margin-bottom: 15px;
            text-align: justify;
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-bar {
                width: 100%;
                height: auto;
                flex-direction: row;
            }

            .image {
                width: 100%;
            }

            .back-button, .download-button, .buy-button {
                position: static;
                margin-top: 20px;
            }

            .text-box {
                width: 90%;
                font-size: 14px;
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

            <!-- Contenedor de botones (Regresar, Descargar PDF y Comprar Libros) -->
            <div class="buttons-container">
                <!-- Botón de "Regresar" -->
                <button class="back-button" onclick="window.history.back();">Regresar</button>

                <!-- Botón de descarga PDF -->
                <a href="docs/figuras.pdf" download>
                    <button class="download-button">Descargar PDF</button>
                </a>

                <!-- Botón de "Comprar Libros" -->
                <a href="ventaLibros.php">
                    <button class="buy-button">Comprar Libros</button>
                </a>
            </div>
        </div>

        <!-- Contenedor principal de la barra lateral con imágenes -->
        <div class="main-content-container">
            <!-- Lado izquierdo (rectángulo con imágenes) -->
            <div class="left-bar">
                <div class="image-container">
                    <img src="imagenes/teoria1.jpg" alt="Imagen 1" class="image" id="image1">
                    <img src="imagenes/teoria2.jpg" alt="Imagen 2" class="image" id="image2">
                    <img src="imagenes/teoria3.jpg" alt="Imagen 3" class="image" id="image3">
                </div>
            </div>

            <!-- Cuadrado con texto mejorado -->
            <div class="text-box">
                <h2>Figuras Geométricas:</h2>
                Las figuras geométricas son fundamentales en el estudio de las matemáticas y se encuentran presentes en múltiples áreas del conocimiento, desde la física hasta el arte. Su estudio ha permitido el desarrollo de teorías y herramientas matemáticas esenciales para comprender nuestro entorno. A continuación, te proporcionamos una descripción detallada de tres de las figuras más relevantes: el triángulo, el cuadrado y el círculo.

                <h3>Triángulo:</h3>
                El triángulo es una de las figuras más antiguas y estudiadas en la historia de la geometría. En la civilización egipcia, los arquitectos usaban triángulos para la construcción de monumentos y pirámides, basándose en el teorema de Pitágoras para asegurar la estabilidad de sus estructuras. Este principio matemático, que relaciona los lados de un triángulo rectángulo, se ha utilizado durante siglos en ingeniería, arquitectura y más recientemente en la informática.

                <h3>Cuadrado:</h3>
                El cuadrado ha sido esencial en el desarrollo de la geometría desde la antigüedad. Los matemáticos griegos lo estudiaron profundamente, y más tarde, en la Edad Media, los matemáticos árabes desarrollaron técnicas para calcular áreas de cuadrados, lo que permitió un avance significativo en la geometría. En la actualidad, el cuadrado es utilizado en áreas tan diversas como la informática, donde las matrices cuadradas son fundamentales, y en la teoría de números, en la cual se estudian los números cuadrados.

                <h3>Círculo:</h3>
                El círculo es una de las figuras más estudiadas debido a su perfección geométrica. En la antigua Grecia, Pitágoras ya había explorado sus propiedades, y más tarde, Arquímedes desarrolló el cálculo de su área y circunferencia. Los avances en la geometría y el cálculo moderno han permitido que el círculo sea utilizado para modelar fenómenos cíclicos en física y astronomía, como el movimiento de los planetas y las ondas sonoras.

                Hoy en día, el círculo sigue siendo fundamental en áreas como la trigonometría, la ingeniería electrónica (en el diseño de circuitos) y la navegación, donde se utiliza para calcular distancias y direcciones mediante coordenadas polares. También en la tecnología de pantallas y en la creación de gráficos en dos dimensiones, el círculo mantiene su relevancia.

                Estos documentos te proporcionarán una comprensión más profunda de las propiedades, historia y aplicaciones de estas figuras geométricas, fundamentales no solo en las matemáticas, sino también en múltiples disciplinas científicas y tecnológicas.
            </div>
        </div>
    </div>

</body>
</html>
