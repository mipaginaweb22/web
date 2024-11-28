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

// Maneja las acciones de agregar, editar y eliminar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agregar'])) {
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $precio = $_POST['precio'];

        $sql = "INSERT INTO libros (titulo, autor, precio) VALUES ('$titulo', '$autor', '$precio')";

        if ($conn->query($sql) === TRUE) {
            // Redirige a la misma página para actualizar la lista de libros
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $titulo = $_POST['titulo'];
        $autor = $_POST['autor'];
        $precio = $_POST['precio'];

        $sql = "UPDATE libros SET titulo='$titulo', autor='$autor', precio='$precio' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            // Redirige a la misma página para actualizar la lista de libros
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Maneja la acción de eliminar
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];

    $sql = "DELETE FROM libros WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        // Redirige a la misma página para actualizar la lista de libros
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Maneja la acción de editar
if (isset($_GET['editar'])) {
    $id = $_GET['editar'];

    $sql = "SELECT * FROM libros WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $editar_libro = $result->fetch_assoc();
    } else {
        $editar_libro = [];
    }
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
            align-items: flex-start;
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
            text-align: center; /* Centra el contenido */
            margin-top: 60px; /* Espacio para la barra de menú */
        }

        h1 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .catalogo {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 50px;
            justify-content: center;
            align-items: center;
        }

        .book-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 250px;
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

        .action-btns a {
            background-color: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }

        .action-btns a.edit {
            background-color: #f39c12;
        }

        .action-btns a:hover {
            background-color: #c0392b;
        }

        form input, form button {
            margin: 10px;
            padding: 10px;
            width: 80%;
            max-width: 300px;
        }

        form button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2980b9;
        }

        /* Estilos para la barra de menú */
        .navbar {
            background-color: transparent; /* Elimina el fondo azul */
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: center; /* Centra los botones horizontalmente */
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10; /* Asegúrate de que la barra de menú esté por encima de la barra lateral */
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px; /* Agrega margen a los lados de cada botón */
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Estilos para el contenedor de los botones */
        .navbar-buttons {
            background-color: #3498db; /* Fondo azul para los botones */
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 5px; /* Redondea las esquinas */
        }
    </style>
</head>
<body>

<!-- Barra de menú -->
<div class="navbar">
    <!-- Contenedor para los botones -->
    <div class="navbar-buttons">
        <a href="#">Inicio</a>
        <a href="#">Libros</a>
        <a href="suscripciones.php">Suscripciones</a>
        <a href="ventas.php">Ventas</a>
    </div>
</div>

<!-- Barra lateral -->
<div class="sidebar">
    <div class="profile-picture">
        <img src="<?= htmlspecialchars($google_picture) ?>" alt="<?= htmlspecialchars($google_name) ?>" width="120" height="120">
    </div>
    <div class="profile-details">
        <div class="name">
            <div class="icon">
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
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                    <path d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4C512 85.5 490.5 64 464 64H48z"/>
                </svg>
            </div>
            <div class="wrap">
                <strong>Email</strong>
                <span><?= htmlspecialchars($google_email) ?></span>
            </div>
        </div>
    </div>
    <a href="login.html" class="logout-btn">Cerrar sesión</a>
</div>

<!-- Contenido -->
<div class="content">
    <h1>Catálogo de Libros</h1>

    <!-- Formulario para agregar o editar libro -->
    <form method="POST" action="libros.php">
        <input type="hidden" name="id" value="<?= isset($editar_libro) ? $editar_libro['id'] : '' ?>" />
        <input type="text" name="titulo" placeholder="Título" value="<?= isset($editar_libro) ? $editar_libro['titulo'] : '' ?>" required />
        <input type="text" name="autor" placeholder="Autor" value="<?= isset($editar_libro) ? $editar_libro['autor'] : '' ?>" required />
        <input type="number" name="precio" placeholder="Precio" value="<?= isset($editar_libro) ? $editar_libro['precio'] : '' ?>" required />
        <button type="submit" name="<?= isset($editar_libro) ? 'editar' : 'agregar' ?>"><?= isset($editar_libro) ? 'Editar' : 'Agregar' ?> Libro</button>
    </form>

    <div class="catalogo">
        <!-- Mostrar la lista de libros -->
        <?php foreach ($libros as $libro): ?>
            <div class="book-card">
                <h3><?= htmlspecialchars($libro['titulo']) ?></h3>
                <p><?= htmlspecialchars($libro['autor']) ?></p>
                <p class="price">$<?= number_format($libro['precio'], 2) ?></p>
                <div class="action-btns">
                    <!-- Botón de editar -->
                    <a href="libros.php?editar=<?= $libro['id'] ?>" class="edit">Editar</a>
                    <!-- Botón de eliminar -->
                    <a href="libros.php?eliminar=<?= $libro['id'] ?>" class="delete" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');">Eliminar</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>