<?php
session_start();
require 'conexao.php';
require 'valida_session.php';

require_once('dompdf/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

// Verificar se o utilizador é administrador opu técnico
if (!($_SESSION['user']['nivel'] === 'administrador' || $_SESSION['user']['nivel'] === 'tecnico')) {
    header("Location: login.php");
    exit;
}

function gerarRelatorioPDF($salas, $equipamentos, $ocorrencias) {
  $dompdf = new Dompdf();
  
  $html = '
  <html>
  <head>
    <meta charset="UTF-8">
    <style>
      /* Configurações gerais */
      @page {
        margin: 50px 40px;
      }
      body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        color: #444;
        margin: 0;
        padding: 0;
      }
      /* Cabeçalho */
      .header {
        text-align: center;
        padding: 15px 0;
        margin-bottom: 20px;
        border-bottom: 3px solid #3498db;
        background: #f9f9f9;
      }
      .header h1 {
        margin: 0;
        font-size: 28px;
        color: #3498db;
      }
      /* Seções */
      .section {
        margin-bottom: 30px;
      }
      .section h2 {
        font-size: 18px;
        color: #3498db;
        margin-bottom: 10px;
        padding: 5px 10px;
        border-left: 4px solid #3498db;
        background: #f2f2f2;
      }
      /* Tabelas */
      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
      }
      thead {
        background-color: #3498db;
        color: #fff;
      }
      th, td {
        padding: 8px 10px;
        border: 1px solid #ccc;
        text-align: left;
      }
      tr:nth-child(even) {
        background-color: #f9f9f9;
      }
      tr:hover {
        background-color: #f1f1f1;
      }
      /* Rodapé com numeração */
      .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 10px;
        color: #666;
        border-top: 1px solid #ccc;
        padding-top: 5px;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <h1>Relatório Detalhado</h1>
    </div>
    
    <div class="section">
      <h2>Salas</h2>
      <table>
        <thead>
          <tr>
            <th>Nome da Sala</th>
            <th>Bloco</th>
            <th>Piso</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($salas as $sala) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($sala['Nome_sala']) . '</td>
                        <td>' . htmlspecialchars($sala['descricao_bloco']) . '</td>
                        <td>' . htmlspecialchars($sala['Descricao_piso']) . '</td>
                      </tr>';
        }
  $html .= '
        </tbody>
      </table>
    </div>
    
    <div class="section">
      <h2>Equipamentos</h2>
      <table>
        <thead>
          <tr>
            <th>Equipamento</th>
            <th>Sala</th>
            <th>Estado</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($equipamentos as $equipamento) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($equipamento['Descricao_Equipamento']) . '</td>
                        <td>' . htmlspecialchars($equipamento['Nome_sala']) . '</td>
                        <td>' . htmlspecialchars($equipamento['Estado_localizacao']) . '</td>
                      </tr>';
        }
  $html .= '
        </tbody>
      </table>
    </div>
    
    <div class="section">
      <h2>Ocorrências</h2>
      <table>
        <thead>
          <tr>
            <th>Estado</th>
            <th>Equipamento</th>
            <th>Problema Reportado</th>
            <th>Solução</th>
          </tr>
        </thead>
        <tbody>';
        foreach ($ocorrencias as $ocorrencia) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($ocorrencia['estado']) . '</td>
                        <td>' . htmlspecialchars($ocorrencia['equipamento']) . '</td>
                        <td>' . htmlspecialchars($ocorrencia['prob_utilizador']) . '</td>
                        <td>' . htmlspecialchars($ocorrencia['solucao']) . '</td>
                      </tr>';
        }
  $html .= '
        </tbody>
      </table>
    </div>
    
    <div class="footer">
      Página <script type="text/php">
        if (isset($pdf)) {
          $font = $pdf->getFontMetrics()->get_font("Helvetica", "normal");
          $pdf->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 10, array(0,0,0));
        }
      </script>
    </div>
  </body>
  </html>';
  
  $dompdf->loadHtml($html);
  $dompdf->setPaper("A4", "portrait");
  $dompdf->render();
  $dompdf->stream("relatorio_detalhado.pdf", array("Attachment" => 0));
}

// Consultas SQL
$stmt_salas = $pdo->prepare("SELECT s.cod_sala, s.Nome_sala, b.descricao_bloco, p.Descricao_piso FROM salas s JOIN blocos b ON s.Bloco_sala = b.cod_bloco JOIN pisos p ON s.Piso_sala = p.Cod_piso");
$stmt_salas->execute();
$salas = $stmt_salas->fetchAll();

$stmt_equipamentos = $pdo->prepare("SELECT e.Descricao_Equipamento, el.Estado_localizacao, s.Nome_sala FROM equipamentos e JOIN equipamentos_localizacao el ON e.Cod_Equipamento = el.Cod_Equipamento JOIN salas s ON el.Cod_Sala = s.cod_sala");
$stmt_equipamentos->execute();
$equipamentos = $stmt_equipamentos->fetchAll();

$stmt_ocorrencias = $pdo->prepare("SELECT o.estado, o.equipamento, o.prob_utilizador, o.solucao FROM ocorrencias o");
$stmt_ocorrencias->execute();
$ocorrencias = $stmt_ocorrencias->fetchAll();

if (isset($_GET['baixar_pdf']) && $_GET['baixar_pdf'] == 1) {
    gerarRelatorioPDF($salas, $equipamentos, $ocorrencias);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Relatórios Detalhados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f7f9fb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #444;
    }
    header {
      background: linear-gradient(135deg,rgb(255, 255, 255),rgb(255, 255, 255));
      padding: 20px;
      position: relative;
      text-align: center;
      color: #337ab7; /* Títulos em azul */
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      border-bottom: 3px solid #fff;
    }
    header img {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
    }
    header h1 {
      font-size: 2.2rem;
      margin: 0;
      color:#007bff; /* Título principal em azul */
    }
    main {
      margin-top: 30px;
    }
    .card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }
    .card-header {
      background-color: #fff;
      border-bottom: none;
      font-size: 1.6rem;
      text-align: center;
      color: #337ab7; /* Todos os títulos dos cards em azul */
      padding: 15px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .list-group-item {
      font-size: 1rem;
      border: none;
      border-bottom: 1px solid #eee;
      padding: 15px 20px;
    }
    .list-group-item:last-child {
      border-bottom: none;
    }
    .btn-custom {
      transition: background-color 0.3s, transform 0.3s;
    }
    .btn-custom:hover {
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <!-- Cabeçalho -->
  <header>
    <img src="https://aevaledeste.pt/site/wp-content/uploads/2022/10/Logo-3_transp-556x532.png" alt="Logotipo">
    <h1>Relatórios Detalhados</h1>
  </header>

  <!-- Conteúdo Principal -->
  <main class="container my-5">
    <!-- Seção Salas -->
    <section class="card">
      <div class="card-header">Salas</div>
      <div class="card-body">
        <div class="list-group">
          <?php foreach ($salas as $sala): ?>
            <div class="list-group-item">
              <strong><?= htmlspecialchars($sala['Nome_sala']) ?></strong> – Bloco: <?= htmlspecialchars($sala['descricao_bloco']) ?>, Piso: <?= htmlspecialchars($sala['Descricao_piso']) ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Seção Equipamentos -->
    <section class="card">
      <div class="card-header">Equipamentos</div>
      <div class="card-body">
        <div class="list-group">
          <?php foreach ($equipamentos as $equipamento): ?>
            <div class="list-group-item">
              <strong><?= htmlspecialchars($equipamento['Descricao_Equipamento']) ?></strong> – Sala: <?= htmlspecialchars($equipamento['Nome_sala']) ?>, Estado: <?= htmlspecialchars($equipamento['Estado_localizacao']) ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Seção Ocorrências -->
    <section class="card">
      <div class="card-header">Ocorrências</div>
      <div class="card-body">
        <div class="list-group">
          <?php foreach ($ocorrencias as $ocorrencia): ?>
            <div class="list-group-item">
              <strong>Estado:</strong> <?= htmlspecialchars($ocorrencia['estado']) ?> – 
              <strong>Equipamento:</strong> <?= htmlspecialchars($ocorrencia['equipamento']) ?><br>
              <strong>Problema Reportado:</strong> <?= htmlspecialchars($ocorrencia['prob_utilizador']) ?><br>
              <strong>Solução:</strong> <?= htmlspecialchars($ocorrencia['solucao']) ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Botões de Navegação -->
    <div class="d-flex justify-content-center gap-3">
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
      <a href="?baixar_pdf=1" class="btn btn-primary btn-custom">Baixar Relatório em PDF</a>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
