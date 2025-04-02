<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é administrador ou técnico
if (!($_SESSION['user']['nivel'] === 'administrador' || $_SESSION['user']['nivel'] === 'tecnico')) {
    header("Location: login.php");
    exit;
}

// Obtém a lista de salas com informações complementares
$stmt = $pdo->prepare("SELECT salas.*, blocos.descricao_bloco, pisos.Descricao_piso 
                       FROM salas 
                       JOIN blocos ON salas.Bloco_sala = blocos.cod_bloco
                       JOIN pisos ON salas.Piso_sala = pisos.Cod_piso");
$stmt->execute();
$salas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Salas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Estilo global */
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 0;
        }
        /* Cabeçalho */
        .header {
            background-color: #ffffff;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e0e0e0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .header img {
            height: 60px;
            margin-right: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #007bff;
        }
        /* Botões customizados */
        .btn-custom {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        /* Cartão e tabela */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        .table thead {
            background-color: #343a40;
            color: #ffffff;
        }
        .table tbody tr:hover {
            background-color: #f1f3f5;
        }
        .badge {
            font-size: 0.9rem;
            padding: 0.5em 0.75em;
        }
        .action-buttons a {
            margin: 0 5px;
        }
        /* Container principal */
        .container {
            margin-top: 40px;
            margin-bottom: 40px;
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <header class="header">
        <img src="logo.png" alt="Logotipo"> <!-- Atualize o caminho da imagem, se necessário -->
        <h1>Gestão de Salas</h1>
    </header>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Cabeçalho da seção -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="text-dark fw-bold">Lista de Salas</h2>
                    <a href="nova_sala.php" class="btn btn-primary btn-lg btn-custom">
                        Adicionar Nova Sala
                    </a>
                </div>
                <!-- Cartão com a tabela -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 text-center">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Bloco</th>
                                        <th>Piso</th>
                                        <th>Observações</th>
                                        <th>Estado</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($salas as $sala): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($sala['cod_sala']) ?></td>
                                        <td><?= htmlspecialchars($sala['Nome_sala']) ?></td>
                                        <td><?= htmlspecialchars($sala['descricao_bloco']) ?></td>
                                        <td><?= htmlspecialchars($sala['Descricao_piso']) ?></td>
                                        <td><?= htmlspecialchars($sala['Observações']) ?></td>
                                        <td>
                                            <span class="badge <?= $sala['Estado'] ? 'bg-primary' : 'bg-danger' ?>">
                                                <?= $sala['Estado'] ? 'Ativa' : 'Inativa' ?>
                                            </span>
                                        </td>
                                        <td class="action-buttons">
                                            <a href="editar_sala.php?id=<?= $sala['cod_sala'] ?>" class="btn btn-warning btn-sm btn-custom">
                                                Editar
                                            </a>
                                            <a href="remover_sala.php?id=<?= $sala['cod_sala'] ?>" class="btn btn-danger btn-sm btn-custom" onclick="return confirm('Tem certeza que deseja remover esta sala?');">
                                                Remover
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Botão de retorno -->
                <?php if ($_SESSION['user']['nivel'] === 'administrador') { ?>
                    <a href="dashboard_admin.php" class="btn btn-outline-primary mt-4">Voltar ao Painel</a>
                <?php } else if ($_SESSION['user']['nivel'] === 'tecnico') { ?>
                    <a href="dashboard_tecnico.php" class="btn btn-outline-primary mt-4">Voltar</a>
                <?php } ?>
            </div>
        </div>
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
