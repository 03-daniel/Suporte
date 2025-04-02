<?php
session_start();
require 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$idutil = $_SESSION['user']['id'];
$andar = $_POST['andar'];
$setor = $_POST['setor'];
$contato = $_POST['contato'];
$escritorio = $_POST['escritorio'];
$prob_utilizador = $_POST['prob_utilizador'];
$estado = 'ABERTO';
$equipamento = $_POST['equipamento'];
$stmt = $pdo->prepare("INSERT INTO ocorrencias (idutil, andar, setor, contato, escritorio, prob_utilizador, estado, equipamento) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$idutil, $andar, $setor, $contato, $escritorio, $prob_utilizador, $estado, $equipamento]);
header("Location: dashboard.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar Ocorrência</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Registar Ocorrência</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="andar" class="form-label">Andar:</label>
            <input type="text" class="form-control" id="andar" name="andar" required>
        </div>
        <div class="mb-3">
            <label for="setor" class="form-label">Setor:</label>
            <input type="text" class="form-control" id="setor" name="setor" required>
        </div>
        <div class="mb-3">
            <label for="contato" class="form-label">Contato:</label>
            <input type="text" class="form-control" id="contato" name="contato" required>
        </div>
        <div class="mb-3">
            <label for="escritorio" class="form-label">Escritório:</label>
            <input type="text" class="form-control" id="escritorio" name="escritorio" required>
        </div>
        <div class="mb-3">
            <label for="prob_utilizador" class="form-label">Problema:</label>
            <textarea class="form-control" id="prob_utilizador" name="prob_utilizador" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="equipamento" class="form-label">Equipamento:</label>
            <input type="text" class="form-control" id="equipamento" name="equipamento">
        </div>
        <button type="submit" class="btn btn-primary">Submeter</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
