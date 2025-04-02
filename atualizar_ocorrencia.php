<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

// Verificar se o utilizador é técnico
if ($_SESSION['user']['nivel'] !== 'tecnico') {
    header("Location: login.php");
    exit;
}

// Obter detalhes da ocorrência
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM ocorrencias WHERE id = ?");
$stmt->execute([$id]);
$ocorrencia = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado = htmlspecialchars($_POST['estado']);
    $solucao = htmlspecialchars($_POST['solucao']);

    $updateStmt = $pdo->prepare("UPDATE ocorrencias SET estado = ?, solucao = ?, data_finalizada = NOW() WHERE id = ?");
    $updateStmt->execute([$estado, $solucao, $id]);

    header("Location: dashboard_tecnico.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Ocorrência</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Atualizar Ocorrência</h1>

        <form method="POST" action="" class="needs-validation" novalidate>
            <div class="mb-3">
                <label class="form-label"><strong>Problema relatado pelo utilizador:</strong></label>
                <p class="form-control-plaintext border p-2"><?= htmlspecialchars($ocorrencia['prob_utilizador']); ?></p>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado:</label>
                <select id="estado" name="estado" class="form-select" required>
                    <option value="EM CURSO" <?= $ocorrencia['estado'] === 'EM CURSO' ? 'selected' : ''; ?>>Em Curso</option>
                    <option value="RESOLVIDO" <?= $ocorrencia['estado'] === 'RESOLVIDO' ? 'selected' : ''; ?>>Resolvido</option>
                </select>
                <div class="invalid-feedback">Por favor, selecione um estado.</div>
            </div>

            <div class="mb-3">
                <label for="solucao" class="form-label">Solução:</label>
                <textarea id="solucao" name="solucao" class="form-control" rows="4" required><?= htmlspecialchars($ocorrencia['solucao']); ?></textarea>
                <div class="invalid-feedback">Por favor, insira a solução.</div>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="dashboard_tecnico.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
        })();
    </script>
</body>
</html>
