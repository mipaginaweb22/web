<?php
// Inicializa la sesión
session_start();

// Verifica si el usuario ha iniciado sesión como administrador
if (!isset($_SESSION['google_loggedin']) || $_SESSION['google_loggedin'] !== true || $_SESSION['google_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

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

// Consulta para obtener todas las suscripciones
$sql = "SELECT * FROM suscripciones";
$result = $conn->query($sql);

// Verifica si hay resultados
if ($result->num_rows > 0) {
    $suscripciones = [];
    while($row = $result->fetch_assoc()) {
        $suscripciones[] = $row;
    }
} else {
    $suscripciones = [];
}

// Función para eliminar una suscripción
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteSql = "DELETE FROM suscripciones WHERE id = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: admin_suscripciones.php');
        exit;
    } else {
        echo "Error al eliminar la suscripción.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1">
    <title>Administrar Suscripciones</title>
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

        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 18px;
            margin-bottom: 20px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #3498db;
            padding: 10px;
            border-radius: 5px;
        }

        .content {
            margin-left: 320px;
            width: 100%;
            padding: 20px;
        }

        .button {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #2980b9;
        }

        .suscripcion-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .suscripcion-card h3 {
            font-size: 18px;
            margin: 10px 0;
        }

        .suscripcion-card p {
            color: #7f8c8d;
        }

        .suscripcion-card .price {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
        }

        .suscripcion-card .edit-btn,
        .suscripcion-card .delete-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .suscripcion-card .edit-btn:hover,
        .suscripcion-card .delete-btn:hover {
            background-color: #2980b9;
        }

        .suscripcion-card .delete-btn {
            background-color: #e74c3c;
        }

        .suscripcion-card .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<!-- Barra lateral -->
<div class="sidebar">
    <a href="planes.php">Dashboard</a>
    <a href="admin_suscripciones.php">Suscripciones</a>
    <a href="logout.php">Cerrar sesión</a>
</div>

<!-- Contenido principal -->
<div class="content">
    <h1>Administrar Suscripciones</h1>
    <a href="agregar_suscripcion.php" class="button">Agregar Nueva Suscripción</a>

    <div class="suscripcion-list">
        <?php foreach ($suscripciones as $suscripcion): ?>
        <div class="suscripcion-card">
            <h3><?= htmlspecialchars($suscripcion['paquete']) ?></h3>
            <p>Desde: <?= htmlspecialchars($suscripcion['fecha_inicio']) ?></p>
            <p>Hasta: <?= htmlspecialchars($suscripcion['fecha_fin']) ?></p>
            <p class="price">$<?= number_format($suscripcion['costo'], 2) ?></p>
            <a href="editar_suscripcion.php?id=<?= $suscripcion['id'] ?>" class="edit-btn">Editar</a>
            <a href="admin_suscripciones.php?delete=<?= $suscripcion['id'] ?>" class="delete-btn" onclick="return confirm('¿Estás seguro de eliminar esta suscripción?');">Eliminar</a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
