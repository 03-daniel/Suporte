<!DOCTYPE html>
<?php
session_start();
require 'conexao.php';
require 'valida_session.php';
?>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Técnicos</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Cabeçalho unificado */
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
        
        /* Layout geral da página */
        body {
            background-color: #eef1f5;
            font-family: 'Arial', sans-serif;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn {
            border-radius: 5px;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho unificado -->
    <header class="header">
        <img src="logo.png" alt="Logotipo">
        <h1>Gestão de Perfil</h1>
    </header>

    <main class="container my-4">
        <h2 class="mb-4 text-center">Técnicos Cadastrados</h2>
        <div class="table-container">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = $pdo->query("SELECT id, nome, login, status FROM utilizadores WHERE nivel = 'tecnico'");
                    $result = $query->fetchAll();

                    if (count($result) > 0) {
                        foreach ($result as $row): 
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['nome'] . "</td>";
                            echo "<td>" . $row['login'] . "</td>";
                            echo "<td>" . ucfirst($row['status']) . "</td>";
                            echo "<td>";
                            echo "<a class='btn btn-warning btn-sm me-2' href='editar_tecnico.php?id=" . $row['id'] . "'>Editar</a>";
                            echo "<a class='btn btn-danger btn-sm' href='deletar_tecnico.php?id=" . $row['id'] . "'>Excluir</a>";
                            echo "</td>";
                            echo "</tr>";
                        endforeach;
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>Nenhum técnico registrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h2 class="mt-5 text-center">Adicionar Novo Técnico</h2>
        <div class="form-container">
            <form action="adicionar_tecnico.php" method="post">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome do Técnico:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Login:</label>
                    <input type="text" id="login" name="login" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" id="senha" name="senha" class="form-control" required>
                </div>
                <input type="hidden" name="nivel" value="tecnico">
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-custom">Adicionar</button>
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
            </form>
        </div>
    </main>

    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 - Sistema de Gestão</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
