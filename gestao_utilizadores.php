<?php
session_start();
require 'conexao.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['user']) || $_SESSION['user']['nivel'] !== 'administrador') {
    header("Location: login.php");
    exit;
}

// Busca todos os usuários da base de dados
$stmt = $pdo->query("SELECT * FROM utilizadores ORDER BY id");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestão de Utilizadores - Portal de Ocorrências</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f8f9fa;
    }
    .container {
      margin-top: 50px;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    /* Header igual ao da página de Gestão de Salas */
    .header {
      background-color: #fff;
      padding: 15px;
      display: flex;
      align-items: center;
      border-bottom: 2px solid #ddd;
      margin-bottom: 20px;
    }
    .header img {
      height: 50px;
      margin-right: 15px;
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
      font-weight: bold;
      color: #007bff;
    }
    .header-actions {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <!-- Header com logo e texto -->
  <header class="header">
    <img src="logo.png" alt="Logotipo"> <!-- Substitua pelo caminho correto do logo -->
    <h1>Gestão de Utilizadores</h1>
  </header>

  <div class="container">
    <!-- Ações do Cabeçalho -->
    <div class="header-actions">
      <a href="dashboard_admin.php" class="btn btn-secondary me-2">Voltar ao Dashboard</a>
      <a href="add_utilizador.php" class="btn btn-primary">Adicionar Novo Utilizador</a>
    </div>
    
    <!-- Tabela de Utilizadores -->
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Login</th>
          <th>Nível</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($usuarios): ?>
          <?php foreach ($usuarios as $usuario): ?>
            <tr>
              <td><?php echo htmlspecialchars($usuario['id']); ?></td>
              <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
              <td><?php echo htmlspecialchars($usuario['login']); ?></td>
              <td><?php echo htmlspecialchars($usuario['nivel']); ?></td>
              <td>
                <a href="editar_utilizador.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="excluir_utilizador.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este utilizador?');">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">Nenhum utilizador encontrado.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
