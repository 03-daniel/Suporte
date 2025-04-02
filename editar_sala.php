<?php
session_start();
require 'conexao.php';

// Verifica se o utilizador está autenticado e tem permissões
if (!isset($_SESSION['user']) || $_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Verifica se o ID da sala foi passado pela URL
if (!isset($_GET['id'])) {
    header("Location: gestão_salas.php"); // Caso o ID não seja encontrado, redireciona para a página de lista de salas
    exit;
}

$id_sala = $_GET['id'];

// Obtém a sala para editar
$stmt = $pdo->prepare("SELECT salas.*, blocos.descricao_bloco, pisos.Descricao_piso FROM salas
                        JOIN blocos ON salas.Bloco_sala = blocos.cod_bloco
                        JOIN pisos ON salas.Piso_sala = pisos.Cod_piso
                        WHERE cod_sala = ?");
$stmt->execute([$id_sala]);
$sala = $stmt->fetch();

// Caso a sala não exista, redireciona para a lista de salas
if (!$sala) {
    header("Location: gestão_salas.php");
    exit;
}

// Atualizar a sala no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coleta os dados do formulário
    $nome_sala = $_POST['nome_sala'];
    $bloco_sala = $_POST['bloco_sala'];
    $piso_sala = $_POST['piso_sala'];
    $observacoes = $_POST['observacoes'];
    $estado = $_POST['estado'];

    // Atualiza a sala no banco de dados
    $stmt_update = $pdo->prepare("UPDATE salas SET 
                                    Nome_sala = ?, 
                                    Bloco_sala = ?, 
                                    Piso_sala = ?, 
                                    Observações = ?, 
                                    Estado = ? 
                                  WHERE cod_sala = ?");
    $stmt_update->execute([$nome_sala, $bloco_sala, $piso_sala, $observacoes, $estado, $id_sala]);

    // Redireciona para a lista de salas após a atualização
    header("Location: gestão_salas.php");
    exit;
}

// Obtém as opções de blocos e pisos
$stmt_blocos = $pdo->prepare("SELECT * FROM blocos");
$stmt_blocos->execute();
$blocos = $stmt_blocos->fetchAll();

$stmt_pisos = $pdo->prepare("SELECT * FROM pisos");
$stmt_pisos->execute();
$pisos = $stmt_pisos->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <header class="bg-white text-center position-relative py-4 shadow-sm">
        <img src="https://aevaledeste.pt/site/wp-content/uploads/2022/10/Logo-3_transp-556x532.png" 
             class="position-absolute top-50 start-0 translate-middle-y ms-3" 
             style="width: 60px; height: auto;" 
             alt="Logotipo">
        <h1 class="text-primary fw-bold">Editar Sala</h1>
    </header>

    <main class="container my-5">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-lg rounded-4">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nome_sala" class="form-label">Nome da Sala</label>
                            <input type="text" class="form-control" id="nome_sala" name="nome_sala" value="<?= htmlspecialchars($sala['Nome_sala']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="bloco_sala" class="form-label">Bloco</label>
                            <select class="form-select" id="bloco_sala" name="bloco_sala" required>
                                <?php foreach ($blocos as $bloco): ?>
                                    <option value="<?= $bloco['cod_bloco'] ?>" <?= $bloco['cod_bloco'] == $sala['Bloco_sala'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($bloco['descricao_bloco']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="piso_sala" class="form-label">Piso</label>
                            <select class="form-select" id="piso_sala" name="piso_sala" required>
                                <?php foreach ($pisos as $piso): ?>
                                    <option value="<?= $piso['Cod_piso'] ?>" <?= $piso['Cod_piso'] == $sala['Piso_sala'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($piso['Descricao_piso']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea class="form-control" id="observacoes" name="observacoes" rows="3"><?= htmlspecialchars($sala['Observações']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado" required>
                                <option value="1" <?= $sala['Estado'] == 1 ? 'selected' : '' ?>>Ativa</option>
                                <option value="0" <?= $sala['Estado'] == 0 ? 'selected' : '' ?>>Inativa</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="gestao_de_salas.php" class="btn btn-secondary btn-lg">⬅️ Voltar</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
