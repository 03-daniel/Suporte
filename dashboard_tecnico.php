<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é administrador
if ($_SESSION['user']['nivel'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

// Obter listas de utilizadores e ocorrências
$utilizadoresStmt = $pdo->query("SELECT * FROM utilizadores");
$utilizadores = $utilizadoresStmt->fetchAll();
$ocorrenciasStmt = $pdo->query("SELECT * FROM ocorrencias");
$ocorrencias = $ocorrenciasStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Logo no canto superior esquerdo */
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 50px;
            height: auto;
        }

        /* Ajustes para o conteúdo principal */
        .content {
            margin-top: 80px;
        }

        /* Estilo para os botões para garantir que todos tenham o mesmo tamanho */
        .btn-block-custom {
            display: block;
            width: 100%;
            padding: 0.5rem;
        }

        /* Tabelas com bordas suaves */
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

        /* Ajuste para a largura da página */
        .container {
            max-width: 1200px;
        }

        /* Menu ajustado para exibir itens lado a lado e centralizados */
        .menu-container {
            background-color: #e7f3ff; /* Azul claro */
            padding: 10px 0; /* Ajusta o padding */
            border-bottom: 3px solid #007bff;
        }

        .menu-items {
            display: flex;
            justify-content: space-between; /* Garante que os itens fiquem igualmente distribuídos */
            flex-wrap: wrap; /* Permite a quebra de linha se necessário */
            gap: 15px; /* Aumenta o espaço entre os itens */
        }

        .menu-item {
            text-align: center;
            padding: 6px 0; /* Menos padding para reduzir o tamanho */
            color: #007bff;
            font-weight: bold;
            font-size: 0.9rem; /* Tamanho de fonte ligeiramente maior */
            flex-basis: 10%; /* Cada item ocupa aproximadamente 1/10 da largura */
            min-width: 100px; /* Define uma largura mínima para cada item */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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
            width: 50px; /* Aumenta o tamanho da imagem */
            height: auto;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .menu-item {
                font-size: 0.8rem; /* Tamanho de fonte menor em telas pequenas */
                flex-basis: 20%; /* Cada item ocupa 1/5 da largura em telas pequenas */
            }

            .menu-items {
                justify-content: center; /* Centraliza os itens em telas pequenas */
            }

            .menu-item img {
                width: 40px;  /* Tamanho da imagem um pouco menor em telas pequenas */
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Cabeçalho e Menu -->
    <header class="menu-container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="https://aevaledeste.pt/site/wp-content/uploads/2022/10/Logo-3_transp.png" alt="Logo" class="me-3" style="width: 40px;">
            <h4 class="text-primary m-0">Sistema Portal de Ocorrências</h4>
        </div>
        <div>
            <span class="me-3">Bem-vindo, <?= htmlspecialchars($_SESSION['user']['nome']) ?></span>
            <a href="logout.php" class="btn btn-outline-primary">Logout</a>
        </div>
    </header>

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
                    <a href="gestao_de_ocorrencias.php">
                        <img src="gestao_ocorrencias.png" alt="Gestão de Ocorrências" class="img-fluid">
                        <p>Gestão de Ocorrências</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="gestao_de_salas.php">
                        <img src="gestao_salas.png" alt="Gestão de Salas" class="img-fluid">
                        <p>Gestão de Salas</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="gestao_de_equipamentos.php">
                        <img src="gestao_equipamentos.png" alt="Gestão de Equipamentos" class="img-fluid">
                        <p>Gestão de Equipamentos</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="reparacoes.php">
                        <img src="gestao_reparador.png" alt="Gestão de Reparações" class="img-fluid">
                        <p>Gestão de Reparações</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="relatorios.php">
                        <img src="gestao_relatorio.png" alt="Relatórios" class="img-fluid">
                        <p>Relatórios</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="gestao_de_tecnicos.php">
                        <img src="gestao_tecnicos.png" alt="Gestão de Técnicos" class="img-fluid">
                        <p>Gestão de Técnicos</p>
                    </a>
                </div>
            </div>
        </div>
    </nav>

<div class="container mt-5 content">
    <h1 class="mb-4 text-center">Gestão do Sistema</h1>

    <h2 class="mb-4">Utilizadores</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Login</th>
                <th>Status</th>
                <th>Nível</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilizadores as $utilizador): ?>
            <tr>
                <td><?= htmlspecialchars($utilizador['id']) ?></td>
                <td><?= htmlspecialchars($utilizador['nome']) ?></td>
                <td><?= htmlspecialchars($utilizador['login']) ?></td>
                <td><?= htmlspecialchars($utilizador['status']) ?></td>
                <td><?= htmlspecialchars($utilizador['nivel']) ?></td>
                <td>
                    <a href="editar_utilizador.php?id=<?= htmlspecialchars($utilizador['id']) ?>" class="btn btn-outline-primary btn-sm-custom btn-block-custom mb-2">Editar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2 class="mb-4">Ocorrências</h2>
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
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>