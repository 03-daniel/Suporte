<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Ocorrência</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Editar Ocorrência</h1>
        <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="andar" class="form-label">Andar</label>
                <input type="text" class="form-control" id="andar" name="andar" value="<?php echo htmlspecialchars($ocorrencia['andar']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="setor" class="form-label">Setor</label>
                <input type="text" class="form-control" id="setor" name="setor" value="<?php echo htmlspecialchars($ocorrencia['setor']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="contacto" class="form-label">Contacto</label>
                <input type="text" class="form-control" id="contacto" name="contacto" value="<?php echo htmlspecialchars($ocorrencia['contacto']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="escritorio" class="form-label">Escritório</label>
                <input type="text" class="form-control" id="escritorio" name="escritorio" value="<?php echo htmlspecialchars($ocorrencia['escritorio']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="prob_utilizador" class="form-label">Problema Relatado pelo Utilizador</label>
                <textarea class="form-control" id="prob_utilizador" name="prob_utilizador" rows="3" required><?php echo htmlspecialchars($ocorrencia['prob_utilizador']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="prob_encontrado" class="form-label">Problema Encontrado</label>
                <textarea class="form-control" id="prob_encontrado" name="prob_encontrado" rows="3"><?php echo htmlspecialchars($ocorrencia['prob_encontrado']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="solucao" class="form-label">Solução</label>
                <textarea class="form-control" id="solucao" name="solucao" rows="3"><?php echo htmlspecialchars($ocorrencia['solucao']); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="ABERTO" <?php echo $ocorrencia['estado'] === 'ABERTO' ? 'selected' : ''; ?>>Aberto</option>
                    <option value="EM PROGRESSO" <?php echo $ocorrencia['estado'] === 'EM PROGRESSO' ? 'selected' : ''; ?>>Em Progresso</option>
                    <option value="FINALIZADO" <?php echo $ocorrencia['estado'] === 'FINALIZADO' ? 'selected' : ''; ?>>Finalizado</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tecnico" class="form-label">Técnico</label>
                <select class="form-select" id="tecnico" name="tecnico" required>
                    <option value="">Selecione um Técnico ou Administrador</option>
                    <?php foreach ($tecnicos as $tecnico): ?>
                        <option value="<?php echo $tecnico['id']; ?>" <?php echo $ocorrencia['tecnico'] == $tecnico['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tecnico['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="equipamento" class="form-label">Equipamento</label>
                <input type="text" class="form-control" id="equipamento" name="equipamento" value="<?php echo htmlspecialchars($ocorrencia['equipamento']); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Guardar Alterações</button>
            <a href="gestao_de_ocorrencias.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
