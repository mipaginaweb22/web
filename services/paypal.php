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
        /* Aquí van tus estilos CSS previos */
    </style>
    <!-- Script de PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=ARi4XC6b6_w6I4L2GfLZggAQd4dChPOSjAiaOqzw8EPjcbFgo6FGJfIQ4000Ts176AABe_iZ_MiG36os&currency=MXN"></script>
</head>
<body>

<!-- Barra lateral (sin cambios) -->

<div class="content">
    <h1>Catálogo de Libros</h1>
    <div class="catalogo">
        <?php foreach ($libros as $libro): ?>
        <div class="book-card">
            <h3><?= htmlspecialchars($libro['titulo']) ?></h3>
            <p><?= htmlspecialchars($libro['autor']) ?></p>
            <p class="price">$<?= number_format($libro['precio'], 2) ?></p>
            
            <!-- Botón de PayPal -->
            <div id="paypal-button-container-<?= $libro['id'] ?>"></div>

            <script>
                paypal.Buttons({
                    style: {
                        shape: 'pill',
                        label: 'pay',
                    },
                    createOrder: function(data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: '<?= $libro['precio'] ?>'
                                }
                            }]
                        });
                    },
                    onApprove: function(data, actions) {
                        actions.order.capture().then(function(details) {
                            window.location.href = "completado.html";
                        });
                    }
                }).render('#paypal-button-container-<?= $libro['id'] ?>');
            </script>

        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
