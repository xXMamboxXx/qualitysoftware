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
            // Obtener una venta por ID
            $stmt = $pdo->prepare('SELECT * FROM Ventas WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            echo json_encode($stmt->fetch());
        } else {
            // Obtener todas las ventas
            $stmt = $pdo->query('SELECT * FROM Ventas');
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        // Crear una nueva venta
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO Ventas (Productos_id, cantidad, precio) VALUES (?, ?, ?)');
        $stmt->execute([$data['Productos_id'], $data['cantidad'], $data['precio']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        // Actualizar una venta existente
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE Ventas SET Productos_id = ?, cantidad = ?, precio = ? WHERE id = ?');
        $stmt->execute([$data['Productos_id'], $data['cantidad'], $data['precio'], $_GET['id']]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        // Eliminar una venta
        $stmt = $pdo->prepare('DELETE FROM Ventas WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true]);
        break;
}
?>
