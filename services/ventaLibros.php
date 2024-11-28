<?php
// Inicializa la sesión
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Recupera las variables de sesión de forma segura
$google_loggedin = $_SESSION['google_loggedin'];
$google_email = $_SESSION['google_email'];
$google_name = $_SESSION['google_name'];
$google_picture = $_SESSION['google_picture'];

// Conectar a la base de datos
$servername = "localhost"; // o el nombre de tu servidor de base de datos
$username = "root"; // tu usuario de la base de datos
$password = ""; // tu contraseña de la base de datos
$dbname = "fig"; // tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los libros
$sql = "SELECT * FROM libros"; // Asegúrate de que el nombre de la tabla y los campos sean correctos
$result = $conn->query($sql);

// Verifica si hay resultados
if ($result->num_rows > 0) {
    // Guarda los libros en un array
    $libros = [];
    while($row = $result->fetch_assoc()) {
        $libros[] = $row;
    }
} else {
    $libros = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
    <title>Perfil</title>
    <link rel="stylesheet" href="assets/css/style-login.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
        }

        .sidebar {
            background-color: #2c3e50;
            color: #fff;
            width: 300px;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-picture img {
            border-radius: 50%;
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            border: 3px solid #ecf0f1;
        }

        .profile-details {
            margin-top: 20px;
        }

        .profile-details .name,
        .profile-details .email {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .profile-details .icon {
            background-color: #3498db;
            border-radius: 50%;
            padding: 10px;
            margin-right: 15px;
        }

        .profile-details .wrap {
            color: #ecf0f1;
        }

        .profile-details .wrap strong {
            display: block;
            font-weight: bold;
        }

        .profile-details .wrap span {
            font-size: 14px;
            color: #bdc3c7;
        }

        .logout-btn {
            display: block;
            text-align: center;
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 30px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .back-btn {
            display: block;
            text-align: center;
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        .content {
            margin-left: 320px; /* Evita que el contenido quede detrás de la barra lateral */
            width: 100%;
        }

        .catalogo {
            display: flex;
            flex-direction: column; /* Cambiar de grid a columna */
            gap: 20px;
            margin-top: 50px;
        }

        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .book-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .book-card p {
            color: #7f8c8d;
        }

        .book-card .price {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
        }

        .book-card .buy-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
            font-size: 14px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .book-card .buy-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<!-- Barra lateral -->
<div class="sidebar">
    <div class="profile-picture">
        <img src="<?= htmlspecialchars($google_picture) ?>" alt="<?= htmlspecialchars($google_name) ?>" width="120" height="120">
    </div>
    <div class="profile-details">
        <div class="name">
            <div class="icon">
                <!-- Icono de nombre -->
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                    <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                </svg>
            </div>
            <div class="wrap">
                <strong>Nombre</strong>
                <span><?= htmlspecialchars($google_name) ?></span>
            </div>
        </div>
        <div class="email">
            <div class="icon">
                <!-- Icono de correo -->
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/>
                </svg>
            </div>
            <div class="wrap">
                <strong>Correo electrónico</strong>
                <span><?= htmlspecialchars($google_email) ?></span>
            </div>
        </div>
    </div>

    <!-- Botón de regresar -->
    <a href="javascript:history.back()" class="back-btn">Regresar</a>

    <!-- Botón de cerrar sesión -->
    <a href="logout.php" class="logout-btn">
        <span class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 16.8 11.3 26.7s-4.1 19.5-11.3 26.7L377.9 406.1c-7.2 7.2-16.8 11.3-26.7 11.3s-19.5-4.1-26.7-11.3l-67.9-67.9c-7.2-7.2-7.2-18.9 0-26.1L285.4 235c7.2-7.2 7.2-18.9 0-26.1L285.4 141.9c7.2-7.2 7.2-18.9 0-26.1l-67.9-67.9c-7.2-7.2-7.2-18.9 0-26.1c7.2-7.2 18.9-7.2 26.1 0l67.9 67.9c7.2 7.2 7.2 18.9 0 26.1L377.9 105.9z"/>
            </svg>
        </span>
        Cerrar sesión
    </a>
</div>

<!-- Contenido principal -->
<div class="content">
    <h1>Catálogo de Libros</h1>
    <div class="catalogo">
        <?php foreach ($libros as $libro): ?>
        <div class="book-card">
            <h3><?= htmlspecialchars($libro['titulo']) ?></h3>
            <p><?= htmlspecialchars($libro['autor']) ?></p>
            <p class="price">$<?= number_format($libro['precio'], 2) ?></p>
            <a href="paypal.php?book=<?= urlencode($libro['titulo']) ?>" class="buy-btn">Comprar</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
