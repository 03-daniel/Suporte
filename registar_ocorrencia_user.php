<?php
session_start(); 
require 'conexao.php'; 
require 'valida_session.php';  

// O id do utilizador vem da sessão
$idutil = $_SESSION['user']['id'];

// Se o formulário de inserção foi enviado (via POST), insere a ocorrência
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contato = $_POST['contato'];
    $prob_encontrado = $_POST['prob_encontrado'];
    $equipamento = $_POST['equipamento']; // id do equipamento selecionado

    $query = "INSERT INTO ocorrencias (idutil, contato, prob_encontrado, equipamento)
              VALUES (:idutil, :contato, :prob_encontrado, :equipamento)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ':idutil'          => $idutil,
        ':contato'         => $contato,
        ':prob_encontrado' => $prob_encontrado,
        ':equipamento'     => $equipamento
    ]);
    header("Location: dashboard_utilizador.php");
    exit();
}

// Consulta para carregar as salas ativas (supondo que Estado = 1 indica sala ativa)
$querySalas = "SELECT * FROM salas WHERE Estado = 1";
$stmtSalas = $pdo->prepare($querySalas);
$stmtSalas->execute();
$salas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

// Se uma sala foi selecionada via GET, carrega os equipamentos vinculados ao mesmo bloco e piso
$selectedSala = isset($_GET['sala']) ? $_GET['sala'] : '';
$equipamentos = [];
if ($selectedSala) {
    // Obtém os detalhes da sala selecionada
    $salaSelected = null;
    foreach ($salas as $sala) {
        if ($sala['cod_sala'] == $selectedSala) {
            $salaSelected = $sala;
            break;
        }
    }
    if ($salaSelected) {
        // Pega o bloco e piso da sala selecionada
        $bloco = $salaSelected['Bloco_sala'];
        $piso = $salaSelected['Piso_sala'];
        // Encontra todos os códigos de sala que possuem o mesmo bloco e piso
        $salaCodes = [];
        foreach ($salas as $sala) {
            if ($sala['Bloco_sala'] == $bloco && $sala['Piso_sala'] == $piso) {
                $salaCodes[] = $sala['cod_sala'];
            }
        }
        // Cria placeholders para a cláusula IN
        $placeholders = implode(',', array_fill(0, count($salaCodes), '?'));
        $queryEquip = "SELECT e.Cod_Equipamento, e.Descricao_Equipamento
                       FROM equipamentos e
                       INNER JOIN equipamentos_localizacao el ON e.Cod_Equipamento = el.Cod_Equipamento
                       WHERE el.Cod_Sala IN ($placeholders)
                         AND (el.Data_Fim IS NULL OR el.Data_Fim > CURDATE())
                         AND el.Estado_localizacao = 'Ativo'";
        $stmtEquip = $pdo->prepare($queryEquip);
        $stmtEquip->execute($salaCodes);
        $equipamentos = $stmtEquip->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        /* Cabeçalho */
        header {
            background-color: #fff;
            padding: 20px;
            position: relative;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        header img {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
        }
        header h1 {
            color: #007bff; /* Azul */
            font-size: 2rem;
            margin: 0;
        }
        /* Card */
        .card {
            background-color: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        /* Botões */
        .btn-primary {
            background-color: #007bff; /* Azul */
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Azul mais escuro */
            border-color: #0056b3;
        }
        .btn-danger {
            background-color: #007bff !important; /* Azul */
            border-color: #007bff !important;
        }
        .btn-danger:hover {
            background-color: #0056b3 !important; /* Azul mais escuro */
            border-color: #0056b3 !important;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .form-label {
            font-weight: 500;
        }
        h2.text-center {
            margin-bottom: 20px;
        }
        /* Centralização do formulário em telas maiores */
        .offset-md-3 {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header class="bg-white text-center position-relative py-4">
        <img src="https://aevaledeste.pt/site/wp-content/uploads/2022/10/Logo-3_transp-556x532.png" 
             class="position-absolute top-50 start-0 translate-middle-y ms-3" 
             alt="Logotipo">
        <h1>Gestão de Ocorrências</h1>
    </header>

    <!-- Conteúdo Principal -->
    <main class="container my-5">
        <?php if (!$selectedSala): ?>
            <!-- Formulário para selecionar a sala (método GET) -->
            <section class="card">
                <h2 class="text-center">Selecionar Sala</h2>
                <form method="GET" action="">
                    <div class="row g-3">
                        <div class="col-md-6 offset-md-3">
                            <label for="sala" class="form-label">Sala</label>
                            <select class="form-select" id="sala" name="sala" required>
                                <option value="">Selecione uma sala</option>
                                <?php foreach ($salas as $sala): ?>
                                    <option value="<?= $sala['cod_sala'] ?>">
                                        <?= $sala['Nome_sala'] ?> - Bloco: <?= $sala['Bloco_sala'] ?> - Piso: <?= $sala['Piso_sala'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">Carregar Equipamentos</button>
                    </div>
                </form>
            </section>
        <?php else: ?>
            <!-- Formulário para inserção da ocorrência (método POST) -->
            <section class="card">
                <h2 class="text-center">Adicionar Nova Ocorrência</h2>
                <form method="POST" action="">
                    <div class="row g-3">
                        <!-- Exibe a sala selecionada -->
                        <div class="col-md-6">
                            <label class="form-label">Sala Selecionada</label>
                            <?php 
                            // Reutiliza o $salaSelected obtido acima
                            ?>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($salaSelected['Nome_sala'] . " - Bloco: " . $salaSelected['Bloco_sala'] . " - Piso: " . $salaSelected['Piso_sala']) ?>" disabled>
                            <input type="hidden" name="sala" value="<?= $selectedSala ?>">
                        </div>
                        <!-- Seleção de Equipamento -->
                        <div class="col-md-6">
                            <label for="equipamento" class="form-label">Equipamento</label>
                            <select class="form-select" id="equipamento" name="equipamento" required>
                                <option value="">Selecione um equipamento</option>
                                <?php foreach($equipamentos as $equip): ?>
                                    <option value="<?= $equip['Cod_Equipamento'] ?>">
                                        <?= htmlspecialchars($equip['Descricao_Equipamento']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Campo para Contato -->
                        <div class="col-md-6">
                            <label for="contato" class="form-label">Contato</label>
                            <input type="text" class="form-control" id="contato" name="contato" required>
                        </div>
                        <!-- Campo para Problema Encontrado -->
                        <div class="col-md-12">
                            <label for="prob_encontrado" class="form-label">Problema Encontrado</label>
                            <textarea class="form-control" id="prob_encontrado" name="prob_encontrado" required></textarea>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-danger">Adicionar</button>
                    </div>
                </form>
            </section>
            <!-- Botão para voltar ao painel -->
            <div class="text-center">
                <?php   
                if ($_SESSION['user']['nivel'] === 'administrador') {  ?>
                   <a href="dashboard_admin.php" class="btn btn-outline-primary">Voltar ao Painel</a>
                <?php
                   }
                else if($_SESSION['user']['nivel'] === 'tecnico'){ ?>
                   <a href="dashboard_tecnico.php" class="btn btn-outline-primary">Voltar ao Painel</a>
                <?php
                }
                else if($_SESSION['user']['nivel'] === 'utilizador'){ ?>
                    <a href="dashboard_utilizador.php" class="btn btn-outline-primary">Voltar ao Painel</a>
                 <?php
                 }
                 ?>
            </div>
        <?php endif; ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
