<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = md5($_POST['pass']);
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE login = ? AND pass = ?");
    $stmt->execute([$login, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
        switch ($user['nivel']) {
            case 'administrador':
                header("Location: dashboard_admin.php");
                break;
            case 'tecnico':
                header("Location: dashboard_tecnico.php");
                break;
            case 'utilizador':
                header("Location: dashboard_utilizador.php");
                break;
            default:
                $error = "Nível de acesso desconhecido!";
        }
        exit;
    } else {
        $error = "Credenciais inválidas!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal de Ocorrências</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        /* Botão de voltar com ícone */
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10;
            background-color: transparent;
            border: none;
            font-size: 2rem;
            color: #fff;
            text-decoration: none;
        }
        /* Fundo em Vídeo */
        #bgVideo {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }
        /* Sobreposição para escurecer o vídeo e melhorar a legibilidade */
        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: -1;
        }
        /* Container do Login */
        .login-container {
            width: 100%;
            max-width: 450px;
            margin: 5% auto;
            padding: 2.5rem;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            height: 45px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            padding: 10px;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .alert {
            text-align: center;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
        }
        /* Ajustes para dispositivos móveis */
        @media (max-width: 576px) {
            .login-container {
                margin: 10% auto;
                padding: 1.5rem;
            }
            .login-container h1 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body>
    <!-- Botão de voltar com ícone -->
    <a href="index.html" class="back-btn"><i class="bi bi-arrow-left"></i></a>

    <!-- Vídeo de fundo -->
    <video autoplay muted loop id="bgVideo">
        <source src="video.mp4" type="video/mp4">
        Seu navegador não suporta a exibição de vídeos.
    </video>
    <!-- Overlay para melhorar o contraste -->
    <div class="video-overlay"></div>

    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="login-container">
            <h1>Login</h1>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="login" class="form-label">Login:</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Palavra-passe:</label>
                    <input type="password" class="form-control" id="pass" name="pass" required>
                </div>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            <?php if (isset($error)) echo "<div class='alert alert-danger mt-3'>$error</div>"; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
