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
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id) {
            $stmt = $pdo->prepare('SELECT * FROM Productos WHERE id = ?');
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch());
        } else {
            $stmt = $pdo->query('SELECT * FROM Productos');
            echo json_encode($stmt->fetchAll());
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('INSERT INTO Productos (nombreP, precio, CategoriaProductos_id) VALUES (?, ?, ?)');
        $stmt->execute([$data['nombreP'], $data['precio'], $data['CategoriaProductos_id']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $stmt = $pdo->prepare('UPDATE Productos SET nombreP = ?, precio = ?, CategoriaProductos_id = ? WHERE id = ?');
        $stmt->execute([$data['nombreP'], $data['precio'], $data['CategoriaProductos_id'], $_GET['id']]);
        echo json_encode(['success' => true]);
        break;

    case 'DELETE':
        $stmt = $pdo->prepare('DELETE FROM Productos WHERE id = ?');
        $stmt->execute([$_GET['id']]);
        echo json_encode(['success' => true]);
        break;
}
?>
