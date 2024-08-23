<?php
$host = 'localhost';
$db = 'mydb';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Obtener un usuario por ID
            $stmt = $pdo->prepare('SELECT * FROM Usuarios WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch());
        } else {
            // Obtener todos los usuarios
            $stmt = $pdo->query('SELECT * FROM Usuarios');
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        // Crear un nuevo usuario
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO Usuarios (nombreC, Roles_id, email, password) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data['nombreC'], $data['Roles_id'], $data['email'], $data['password']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        // Actualizar un usuario existente
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE Usuarios SET nombreC = ?, Roles_id = ?, email = ?, password = ? WHERE id = ?');
        $stmt->execute([$data['nombreC'], $data['Roles_id'], $data['email'], $data['password'], $_GET['id']]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        // Eliminar un usuario
        $stmt = $pdo->prepare('DELETE FROM Usuarios WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true]);
        break;
}
?>
