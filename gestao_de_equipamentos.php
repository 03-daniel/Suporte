<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é administrador opu técnico
if (!($_SESSION['user']['nivel'] === 'administrador' || $_SESSION['user']['nivel'] === 'tecnico')) {
    header("Location: login.php");
    exit;
}
// Processa a remoção do equipamento, se solicitado
if (isset($_GET['delete'])) {
    $idToDelete = (int) $_GET['delete'];
    
    // Prepara e executa a query de remoção
    $stmtDelete = $pdo->prepare("DELETE FROM equipamentos WHERE Cod_Equipamento = :id");
    $stmtDelete->execute([':id' => $idToDelete]);
    
    // Redireciona para evitar reenvio da query e atualizar a lista
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Processa o formulário de adição de equipamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura e sanitiza os dados do formulário
    $descricao = trim($_POST['descricao']);
    $observacoes = trim($_POST['observacoes']);
    $estado = trim($_POST['estado']);

    // Prepara e executa a query de inserção
    $stmt = $pdo->prepare("INSERT INTO equipamentos (Descricao_Equipamento, Obs_Equipamento, Estado_Equipamento) VALUES (:descricao, :observacoes, :estado)");
    $stmt->execute([
        ':descricao'    => $descricao,
        ':observacoes'  => $observacoes,
        ':estado'       => $estado
    ]);

    // Redireciona para evitar reenvio do formulário ao atualizar a página
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Obtém os equipamentos
$stmt_equipamentos = $pdo->prepare("SELECT * FROM equipamentos");
$stmt_equipamentos->execute();
$equipamentos = $stmt_equipamentos->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
    <header class="header">
        <img src="logo.png" alt="Logotipo"> <!-- Utilize o mesmo logo -->
        <h1>Gestão de Perfil</h1> <!-- Mesmo texto -->
    </header>

    <main class="container my-5">
        <section class="bg-white shadow-lg rounded-3 p-4 mb-5">
            <h2 class="text-center">Adicionar Novo Equipamento</h2>
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                    </div>
                    <div class="col-md-6">
                        <label for="observacoes" class="form-label">Observações</label>
                        <input type="text" class="form-control" id="observacoes" name="observacoes">
                    </div>
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Ativo">Ativo</option>
                            <option value="Inativo">Inativo</option>
                        </select>
                    </div>
                </div>
                <!-- Botões aprimorados: centralizados, com espaçamento e tamanho mínimo definido -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center gap-3">
                        <button type="submit" class="btn btn-primary btn-sm" style="min-width: 150px;">Adicionar</button>
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
                </div>
            </form>
        </section>

        <section class="bg-white shadow-lg rounded-3 p-4">
            <h2 class="text-center">Lista de Equipamentos</h2>
            <div class="table-responsive mt-3">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Descrição</th>
                            <th>Observações</th>
                            <th>Estado</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipamentos as $equipamento): ?>
                            <tr>
                                <td><?= htmlspecialchars($equipamento['Cod_Equipamento']) ?></td>
                                <td><?= htmlspecialchars($equipamento['Descricao_Equipamento']) ?></td>
                                <td><?= htmlspecialchars($equipamento['Obs_Equipamento']) ?></td>
                                <td><?= htmlspecialchars($equipamento['Estado_Equipamento']) ?></td>
                                <td>
                                    <a href="?delete=<?= $equipamento['Cod_Equipamento'] ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Tem certeza que deseja remover este equipamento?');">
                                        Remover
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
