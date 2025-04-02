<?php
session_start();
require 'conexao.php';

// Verifica se o utilizador está autenticado e tem permissões
if (!isset($_SESSION['user']) || $_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Verifica se o ID da sala foi passado pela URL
if (!isset($_GET['id'])) {
    header("Location: gestão_salas.php"); // Caso o ID não seja encontrado, redireciona para a página de lista de salas
    exit;
}

$id_sala = $_GET['id'];

// Verifica se a sala existe antes de remover
$stmt_check = $pdo->prepare("SELECT * FROM salas WHERE cod_sala = ?");
$stmt_check->execute([$id_sala]);
$sala = $stmt_check->fetch();

// Se a sala não existe, redireciona para a página de lista de salas
if (!$sala) {
    header("Location: gestão_salas.php");
    exit;
}

// Exclui a sala do banco de dados
$stmt_delete = $pdo->prepare("DELETE FROM salas WHERE cod_sala = ?");
$stmt_delete->execute([$id_sala]);

// Redireciona de volta para a lista de salas após a remoção
header("Location: gestão_salas.php");
exit;
?>
