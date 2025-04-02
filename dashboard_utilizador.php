<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é do nível "utilizador"
if ($_SESSION['user']['nivel'] !== 'utilizador') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

// Selecionar apenas as ocorrências do utilizador autenticado usando a coluna correta 'idutil'
$stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE idutil = ?");
$stmt->execute([$user['id']]);
$ocorrencias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Utilizador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos comuns */
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: auto;
        }
        .content {
            margin-top: 80px;
        }
        .btn-block-custom {
            display: block;
            width: 100%;
            padding: 0.5rem;
        }
        table {
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 1rem;
            text-align: center;
        }
        .table-bordered {
            border: 1px solid #dee2e6;
        }
        .table-dark th {
            background-color: #343a40;
            color: white;
        }
        .container {
            max-width: 1200px;
        }
        /* Menu */
        .menu-container {
            background-color: #e7f3ff;
            padding: 10px 0;
            border-bottom: 3px solid #007bff;
        }
        .menu-items {
            display: flex;
            justify-content: center; /* Centraliza os itens horizontalmente */
            align-items: center;  /* Alinha verticalmente */
            flex-wrap: nowrap; /* Não quebra linha */
            gap: 10px; /* Espaço reduzido entre os itens */
        }
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 6px 10px;
            color: #007bff;
            font-weight: bold;
            font-size: 0.9rem;
            min-width: 120px; /* Largura mínima para consistência */
        }
        .menu-item:hover {
            background-color: #d0e7ff;
            border-radius: 5px;
            cursor: pointer;
        }
        .menu-item a {
            text-decoration: none;
            color: inherit;
        }
        .menu-item img {
            width: 50px;
            height: auto;
        }
        @media (max-width: 768px) {
            .menu-item {
                font-size: 0.8rem;
                min-width: 100px;
            }
            .menu-item img {
                width: 40px;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Cabeçalho -->
    <header class="menu-container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="https://aevaledeste.pt/site/wp-content/uploads/2022/10/Logo-3_transp.png" alt="Logo" class="me-3" style="width: 40px;">
            <h4 class="text-primary m-0">Sistema Portal de Ocorrências</h4>
        </div>
        <div>
            <span class="me-3">Bem-vindo, <?= htmlspecialchars($user['nome']) ?></span>
            <a href="logout.php" class="btn btn-outline-primary">Logout</a>
        </div>
    </header>

    <!-- Menu específico para utilizador -->
    <nav class="menu-container">
        <div class="container">
            <div class="menu-items">
                <div class="menu-item">
                    <a href="gestao_de_perfil.php">
                        <img src="gestao_perfil.png" alt="Gestão de Perfil" class="img-fluid">
                        <p>Gestão de Perfil</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="registar_ocorrencia_user.php">
                        <img src="gestao_ocorrencias.png" alt="Gestão de Ocorrências" class="img-fluid">
                        <p>Gestão de Ocorrências</p>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5 content">
        <h1 class="mb-4 text-center">Painel do Utilizador</h1>

        <!-- Dados do Utilizador -->
        <h2 class="mb-4">Meus Dados</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Login</th>
                    <th>Nível</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['nome']) ?></td>
                    <td><?= htmlspecialchars($user['login']) ?></td>
                    <td><?= htmlspecialchars($user['nivel']) ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Problema</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($ocorrencias) > 0): ?>
                    <?php foreach ($ocorrencias as $ocorrencia): ?>
                        <tr>
                            <td><?= htmlspecialchars($ocorrencia['id_ocorrencia']) ?></td>
                            <td><?= htmlspecialchars($ocorrencia['prob_utilizador']) ?></td>
                            <td><?= htmlspecialchars($ocorrencia['estado']) ?></td>
                            <td><?= htmlspecialchars($ocorrencia['tecnico']) ?></td>
                            <td>
                                <a href="atualizar_ocorrencia.php?id=<?= htmlspecialchars($ocorrencia['id_ocorrencia']) ?>" class="btn btn-outline-primary btn-sm-custom btn-block-custom mb-2">Detalhes</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Nenhuma ocorrência encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
