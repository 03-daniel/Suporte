<?php
// Configuração da conexão
$host = 'localhost';
$dbname = 'suporte';
$username = 'root';
$password = '';
try {
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username,
$password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Erro na conexão: " . $e->getMessage());
}
?>