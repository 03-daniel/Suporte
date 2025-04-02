<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verifica se o utilizador é administrador ou técnico
if (!isset($_SESSION['user']) || !($_SESSION['user']['nivel'] === 'administrador' || $_SESSION['user']['nivel'] === 'tecnico')) {
    header("Location: login.php");
    exit;
}

// Obtém os blocos e pisos disponíveis
$stmt_blocos = $pdo->query("SELECT cod_bloco, descricao_bloco FROM blocos");
$blocos = $stmt_blocos->fetchAll();

$stmt_pisos = $pdo->query("SELECT Cod_piso, Descricao_piso FROM pisos");
$pisos = $stmt_pisos->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_sala = $_POST['nome_sala'] ?? '';
    $bloco_sala = $_POST['bloco_sala'] ?? '';
    $piso_sala = $_POST['piso_sala'] ?? '';
    $observacoes = $_POST['observacoes'] ?? '';
    $estado = isset($_POST['estado']) ? 1 : 0;

    if (!empty($nome_sala) && !empty($bloco_sala) && !empty($piso_sala)) {
        $stmt = $pdo->prepare("INSERT INTO salas (Nome_sala, Bloco_sala, Piso_sala, Observações, Estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nome_sala, $bloco_sala, $piso_sala, $observacoes, $estado]);
        header("Location: gestao_de_salas.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        header {
            background-color: #ffffff;
            border-bottom: 2px solid #007bff;
        }
        header h1 {
            color: #007bff;
        }
        .card {
            border-radius: 15px;
        }
        .card-body {
            padding: 2rem;
        }
        .btn-success {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-success:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-control, .form-select {
            border-radius: 10px;
        }
        .form-check-label {
            font-weight: 500;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>
    <header class="text-center position-relative py-4">
        <h1>Adicionar Nova Sala</h1>
    </header>

    <main class="container my-5">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-lg">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label" for="nome_sala">Nome da Sala</label>
                            <input type="text" id="nome_sala" name="nome_sala" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="bloco_sala">Bloco</label>
                            <select id="bloco_sala" name="bloco_sala" class="form-select" required>
                                <option value="">Selecione um Bloco</option>
                                <?php foreach ($blocos as $bloco): ?>
                                    <option value="<?= $bloco['cod_bloco'] ?>"><?= htmlspecialchars($bloco['descricao_bloco']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="piso_sala">Piso</label>
                            <select id="piso_sala" name="piso_sala" class="form-select" required>
                                <option value="">Selecione um Piso</option>
                                <?php foreach ($pisos as $piso): ?>
                                    <option value="<?= $piso['Cod_piso'] ?>"><?= htmlspecialchars($piso['Descricao_piso']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="observacoes">Observações</label>
                            <input type="text" id="observacoes" name="observacoes" class="form-control">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="estado" class="form-check-input" checked>
                            <label class="form-check-label">Ativa</label>
                        </div>
                        <button type="submit" class="btn btn-success">Adicionar</button>
                        <a href="gestao_de_salas.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
