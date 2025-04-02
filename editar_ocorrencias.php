<?php
session_start();
require 'conexao.php';

if (!isset($_SESSION['USER']) || in_array($_SESSION['user']['nivel'], ['tecnico', 'administrador'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM ocorrencias ORDER BY data_abertura DESC");
$stmt->execute();
$ocorrencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar todas as ocorrências</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Editar todas as ocorrências</h1>

        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Autor</th>
                    <th>Andar</th>
                    <th>Setor</th>
                    <th>Contato</th>
                    <th>Escritório</th>
                    <th>Problema relatado</th>
                    <th>Estado</th>
                    <th>Data abertura</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ocorrencias) > 0): ?>
                    <?php foreach ($ocorrencias as $ocorrencia): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ocorrencia['id']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['idutil']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['andar']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['setor']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['contato']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['escritorio']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['prob_utilizador']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['estado']); ?></td>
                            <td><?php echo htmlspecialchars($ocorrencia['data_abertura']); ?></td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="editar_ocorrencia.php?id=<?php echo $ocorrencia['id']; ?>">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Nenhuma ocorrência encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>