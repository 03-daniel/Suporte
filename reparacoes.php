<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é administrador opu técnico
if (!($_SESSION['user']['nivel'] === 'administrador' || $_SESSION['user']['nivel'] === 'tecnico')) {
    header("Location: login.php");
    exit;
}
// Obtém as ocorrências de reparações
$stmt_reparacoes = $pdo->prepare("SELECT * FROM ocorrencias WHERE estado IN ('EM CURSO', 'RESOLVIDO')");
$stmt_reparacoes->execute();
$reparacoes = $stmt_reparacoes->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reparações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Cabeçalho padrão */
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
    </style>
</head>
<body class="bg-white">
    <!-- Cabeçalho com o mesmo logo e texto -->
    <header class="header">
        <img src="logo.png" alt="Logotipo">
        <h1>Gestão de Reparações</h1>
    </header>

    <div class="container my-5">
        <table class="table table-striped bg-white shadow-lg rounded-3 p-4">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Utilizador</th>
                    <th>Contacto</th>
                    <th>Problema Reportado</th>
                    <th>Problema Encontrado</th>
                    <th>Estado</th>
                    <th>Técnico</th>
                    <th>Data Abertura</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reparacoes as $reparacao): ?>
                    <tr>
                        <td><?= htmlspecialchars($reparacao['id_ocorrencia']) ?></td>
                        <td><?= htmlspecialchars($reparacao['idutil']) ?></td>
                        <td><?= htmlspecialchars($reparacao['contato']) ?></td>
                        <td><?= htmlspecialchars($reparacao['prob_utilizador']) ?></td>
                        <td><?= htmlspecialchars($reparacao['prob_encontrado'] ?? 'N/D') ?></td>
                        <td><?= htmlspecialchars($reparacao['estado']) ?></td>
                        <td><?= htmlspecialchars($reparacao['tecnico'] ?? 'N/D') ?></td>
                        <td><?= htmlspecialchars($reparacao['data_abertura']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php   
                if ($_SESSION['user']['nivel'] === 'administrador') {  ?>
                   <a href='dashboard_admin.php' class='btn btn-outline-primary'>Voltar</a>
                <?php
                   }
                else if($_SESSION['user']['nivel'] === 'tecnico'){ ?>
                   <a href='dashboard_tecnico.php' class='btn btn-outline-primary'>Voltar</a>
                <?php
                }
                 
                 ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
