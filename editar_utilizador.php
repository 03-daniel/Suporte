<?php
session_start();
require 'conexao.php';

// Verifica se o utilizador está autenticado e tem permissões
if (!isset($_SESSION['user']) || $_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Obtém o ID do utilizador a ser editado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido!");
}

$id = (int) $_GET['id'];

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $nivel = filter_input(INPUT_POST, 'nivel', FILTER_SANITIZE_STRING);

    if (!empty($_POST['pass'])) {
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, login = ?, pass = ?, nivel = ? WHERE id = ?");
        $stmt->execute([$nome, $login, $pass, $nivel, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE utilizadores SET nome = ?, login = ?, nivel = ? WHERE id = ?");
        $stmt->execute([$nome, $login, $nivel, $id]);
    }

    header("Location: listar_utilizadores.php");
    exit;
}

// Obtém os dados do utilizador para exibição no formulário
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilizador não encontrado!");
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Utilizador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-weight: bold;
        }
        .btn-outline-primary {
            border-color: #007bff;
            color: #007bff;
        }
        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .form-label {
            font-weight: 600;
            color: #333;
        }
        .form-control {
            border-radius: 5px;
        }
        .form-select {
            border-radius: 5px;
        }
        .mb-3 {
            margin-bottom: 1.5rem;
        }
        .mt-3 a {
            margin-right: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #555;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1>Editar Utilizador</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="login" class="form-label">Login:</label>
            <input type="text" class="form-control" id="login" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="nivel" class="form-label">Nível de Acesso:</label>
            <select id="nivel" name="nivel" class="form-select" required>
                <option value="administrador" <?= $user['nivel'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                <option value="tecnico" <?= $user['nivel'] === 'tecnico' ? 'selected' : '' ?>>Técnico</option>
                <option value="utilizador" <?= $user['nivel'] === 'utilizador' ? 'selected' : '' ?>>Utilizador</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="pass" class="form-label">Nova Palavra-passe (opcional):</label>
            <input type="password" class="form-control" id="pass" name="pass">
        </div>

        <button type="submit" class="btn btn-outline-primary">Guardar Alterações</button>
    </form>

    <div class="mt-3">
        <a href="dashboard_admin.php" class="btn btn-outline-primary">Voltar</a>
        <a href="gestao_utilizadores.php" class="btn btn-outline-primary">Gestão Utilizadores</a>
    </div>
</div>

<div class="footer">
    <p>&copy; <?= date("Y"); ?> Sistema de Gestão</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
