<?php 

$host = 'localhost';
$db   = 'sistema_orcamentos';
$user = 'root'; // Ajuste conforme seu ambiente
$pass = '';     // Ajuste conforme seu ambiente

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>