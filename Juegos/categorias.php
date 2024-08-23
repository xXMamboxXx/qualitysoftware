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

// Obtener categorías
$stmt = $pdo->query('SELECT * FROM CategoriaProductos');
echo json_encode($stmt->fetchAll());
?>
