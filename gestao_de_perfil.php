<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é administrador opu técnico
if (!($_SESSION['user']['nivel'] === 'administrador' || $_SESSION['user']['nivel'] === 'tecnico' || $_SESSION['user']['nivel'] === 'utilizador')) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background-color: #fff;
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #ddd;
        }
        .header img {
            height: 40px;
            margin-right: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: #fff;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <header class="header">
        <img src="logo.png" alt="Logotipo"> <!-- Substituir pelo caminho correto -->
        <h1>Gestão de Perfil</h1>
    </header>

    <div class="container">
        <div class="profile-container">
            <h2 class="text-center mb-4">Gestão de Perfil</h2>

            <?php if (!empty($mensagem)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" 
                           value="<?= htmlspecialchars($utilizador['nome'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" name="login" id="login" class="form-control" 
                           value="<?= htmlspecialchars($utilizador['login'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" name="senha" id="senha" class="form-control" 
                           placeholder="Deixe em branco para manter a senha atual">
                </div>
                <button type="submit" class="btn btn-custom w-100">Atualizar Perfil</button>
            </form>

            <div class="mt-3 text-center">
                <?php   
                if ($_SESSION['user']['nivel'] === 'administrador') {  ?>
                   <a href='dashboard_admin.php' class='btn btn-outline-primary'>Voltar ao Painel</a>
                <?php
                   }
                else if($_SESSION['user']['nivel'] === 'tecnico'){ ?>
                   <a href='dashboard_tecnico.php' class='btn btn-outline-primary'>Voltar ao Painel</a>
                <?php
                }
                else if($_SESSION['user']['nivel'] === 'utilizador'){ ?>
                    <a href='dashboard_utilizador.php' class='btn btn-outline-primary'>Voltar ao Painel</a>
                 <?php
                 }
                 ?>
                
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
