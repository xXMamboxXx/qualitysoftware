<?php
// Conexión a la base de datos
$host = 'localhost'; // Cambia esto si tu base de datos está en otro servidor
$db = 'mydb';
$user = 'root'; // Cambia esto si tienes otro usuario de MySQL
$pass = ''; // Cambia esto si tienes una contraseña de MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Preparar la consulta para buscar al usuario
$query = 'SELECT * FROM Usuarios WHERE email = ? AND password = ?';
$stmt = $pdo->prepare($query);
$stmt->execute([$email, $password]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Verificar el rol del usuario
    $role_id = $user['Roles_id'];
    
    if ($role_id == 1) {
        // Si el rol es 1 (Admin), redirigir a la página principal
        header('Location: index.html');
        exit();
    } elseif ($role_id == 2) {
        // Si el rol es 2 (Vendedor), mostrar un mensaje de acceso denegado
        echo '<p>Acceso denegado. No tienes permisos para acceder a esta página.</p>';
        echo '<a href="login.html">Volver al login</a>';
        exit();
    }
} else {
    // Si las credenciales son incorrectas, mostrar un mensaje de error
    echo '<p>Credenciales incorrectas. Por favor, intente nuevamente.</p>';
    echo '<a href="login.html">Volver al login</a>';
}
?>
