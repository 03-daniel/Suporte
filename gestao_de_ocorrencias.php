<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador está autenticado
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Inserir nova ocorrência no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idutil = $_POST['idutil'];
    $andar = $_POST['andar'];
    $setor = $_POST['setor'];
    $contato = $_POST['contato'];
    $escritorio = $_POST['escritorio'];
    $prob_utilizador = $_POST['prob_utilizador'];
    $prob_encontrado = $_POST['prob_encontrado'];
    $solucao = $_POST['solucao'];
    $estado = $_POST['estado'];
    $tecnico = $_POST['tecnico'];
    $equipamento = $_POST['equipamento'];

    $sql = "INSERT INTO ocorrencias (idutil, andar, setor, contato, escritorio, prob_utilizador, prob_encontrado, solucao, estado, tecnico, equipamento)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idutil, $andar, $setor, $contato, $escritorio, $prob_utilizador, $prob_encontrado, $solucao, $estado, $tecnico, $equipamento]);

    header("Location: gestao_de_ocorrencias.php");
    exit;
}

// Obter todas as ocorrências da tabela ocorrencias
$query = $pdo->query("SELECT * FROM ocorrencias ORDER BY data_abertura DESC");
$ocorrencias = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <header class="header">
        <img src="logo.png" alt="Logotipo"> <!-- Substituir pelo caminho correto -->
        <h1>Gestão de Ocorrências</h1>
    </header>

    <div class="container">
        <h2 class="text-center mb-4">Lista de Ocorrências</h2>

        <!-- Formulário para adicionar nova ocorrência -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Adicionar Nova Ocorrência</div>
            <div class="card-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">ID Utilizador</label>
                            <input type="number" name="idutil" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Andar</label>
                            <input type="text" name="andar" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Setor</label>
                            <input type="text" name="setor" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Contato</label>
                            <input type="text" name="contato" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Escritório</label>
                            <input type="text" name="escritorio" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Técnico</label>
                            <input type="text" name="tecnico" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Problema Relatado</label>
                            <textarea name="prob_utilizador" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Problema Encontrado</label>
                            <textarea name="prob_encontrado" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Solução</label>
                            <textarea name="solucao" class="form-control"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Equipamento</label>
                            <input type="text" name="equipamento" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select" required>
                                <option value="ABERTO">Aberto</option>
                                <option value="EM CURSO">Em Curso</option>
                                <option value="RESOLVIDO">Resolvido</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Adicionar Ocorrência</button>
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
                </form>
            </div>
        </div>

       <!-- Tabela de ocorrencias -->
       <section class="bg-white shadow-lg rounded-3 p-4">
            <h2 class="text-center">Registro de Ocorrências</h2>
            <div class="table-responsive mt-3">
                <table class="table table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>ID Utilizador</th>
                            <th>Contato</th>
                            <th>Problema do Utilizador</th>
                            <th>Problema Encontrado</th>
                            <th>Solução</th>
                            <th>Estado</th>
                            <th>Data Abertura</th>
                            <th>Data Decorrer</th>
                            <th>Data Finalizada</th>
                            <th>Técnico</th>
                            <th>Equipamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ocorrencias as $registro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registro['id_ocorrencia']); ?></td>
                                <td><?php echo htmlspecialchars($registro['idutil']); ?></td>
                                <td><?php echo htmlspecialchars($registro['contato']); ?></td>
                                <td><?php echo htmlspecialchars($registro['prob_utilizador']); ?></td>
                                <td><?php echo htmlspecialchars($registro['prob_encontrado']); ?></td>
                                <td><?php echo htmlspecialchars($registro['solucao']); ?></td>
                                <td><?php echo htmlspecialchars($registro['estado']); ?></td>
                                <td><?php echo htmlspecialchars($registro['data_abertura']); ?></td>
                                <td><?php echo htmlspecialchars($registro['data_decorrer']); ?></td>
                                <td><?php echo htmlspecialchars($registro['data_finalizada']); ?></td>
                                <td><?php echo htmlspecialchars($registro['tecnico']); ?></td>
                                <td><?php echo htmlspecialchars($registro['equipamento']); ?></td>
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