<?php
session_start();
require 'conexao.php';
require 'valida_session.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $login = htmlspecialchars($_POST['login']);
    $password = md5($_POST['password']); // Em projetos reais, utilize password_hash

    $stmt = $pdo->prepare("INSERT INTO utilizadores (nome, login, pass, status, nivel) VALUES (?, ?, ?, 'ativo', 'tecnico')");
    $stmt->execute([$nome, $login, $password]);
    $success = "Técnico registado com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Registar Técnico</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="login" class="form-label">Login:</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Palavra-passe:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Submeter</button>
    </form>
    <?php if (isset($success)) echo "<div class='alert alert-success mt-3'>$success</div>"; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
