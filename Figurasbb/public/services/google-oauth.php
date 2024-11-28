<?php
// Iniciar la sesión
session_start();

// Variables de configuración con tus credenciales de Google OAuth
$google_oauth_client_id = '1040871422594-5moao09dvruo9ld34f18pmmoh0arb1f0.apps.googleusercontent.com';
$google_oauth_client_secret = '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';
$google_oauth_redirect_uri = 'http://localhost:3000/public/services/google-oauth.php'; // Asegúrate de que coincida exactamente
$google_oauth_version = 'v3'; // Versión de la API que estás utilizando (ajustar si necesario)

// Correo del administrador
$admin_email = 'cercassavilaasergioo@gmail.com'; // Correo del administrador

// Conexión a la base de datos
$host = 'localhost';  // Cambia esto si es necesario
$dbname = 'fig'; // El nombre de tu base de datos
$username = 'root'; // El nombre de tu usuario de la base de datos
$password = ''; // La contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Error de conexión: ' . $e->getMessage());
}

// Si el parámetro 'code' existe y no está vacío
if (isset($_GET['code']) && !empty($_GET['code'])) {

    // Realizar la petición cURL para obtener el token de acceso
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Convertir la respuesta JSON a un array
    $response = json_decode($response, true);

    // Verificar si el token de acceso es válido
    if (isset($response['access_token']) && !empty($response['access_token'])) {

        // Realizar una segunda petición cURL para obtener los datos del perfil de usuario
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);

        // Convertir la respuesta del perfil a un array
        $profile = json_decode($response, true);

        // Verificar si la información del perfil existe
        if (isset($profile['email'])) {

            // Limpiar y organizar los nombres
            $google_name_parts = [];
            $google_name_parts[] = isset($profile['given_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['given_name']) : '';
            $google_name_parts[] = isset($profile['family_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['family_name']) : '';

            // Autenticar al usuario y crear una nueva sesión
            session_regenerate_id();
            $_SESSION['google_loggedin'] = TRUE;
            $_SESSION['google_email'] = $profile['email'];
            $_SESSION['google_name'] = implode(' ', $google_name_parts);
            $_SESSION['google_picture'] = isset($profile['picture']) ? $profile['picture'] : '';
            $_SESSION['google_id'] = isset($profile['id']) ? $profile['id'] : ''; // Google ID

            // Verificar si el usuario ya existe en la base de datos
            $sql = "SELECT * FROM usuarios WHERE google_id = :google_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['google_id' => $_SESSION['google_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                // El usuario no existe, insertar un nuevo registro
                $sql = "INSERT INTO usuarios (google_id, email, nombre, foto, role) VALUES (:google_id, :email, :nombre, :foto, :role)";
                $stmt = $pdo->prepare($sql);
                $role = ($_SESSION['google_email'] == $admin_email) ? 'admin' : 'user'; // Asignar rol 'admin' o 'user'
                $stmt->execute([ 
                    'google_id' => $_SESSION['google_id'],
                    'email' => $_SESSION['google_email'],
                    'nombre' => $_SESSION['google_name'],
                    'foto' => $_SESSION['google_picture'],
                    'role' => $role
                ]);
            }

            // Verificar el rol del usuario
            if ($_SESSION['google_email'] == $admin_email || $user['role'] == 'admin') {
                // Redirigir al perfil de administrador
                header('Location: profileAdmi.php');
            } else {
                // Redirigir al perfil normal
                header('Location: profile.php');
            }
            exit;

        } else {
            // Error al recuperar la información del perfil
            exit('No se pudo recuperar la información del perfil. Intenta nuevamente más tarde.');
        }
    } else {
        // Error con el token de acceso
        exit('Token de acceso no válido. Intenta nuevamente más tarde.');
    }

} else {
    // Redirigir a Google para la autenticación
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];

    // Redirigir a la página de autenticación de Google
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
