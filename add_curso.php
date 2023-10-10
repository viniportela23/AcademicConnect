<?php
session_start();

// Verificar se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

$mensagem = ""; // Variável para armazenar a mensagem

// Lógica para inserção de dados de curso no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar ao banco de dados
    $credenciais = parse_ini_file("credenciais_banco.txt");
    $host = $credenciais['host'];
    $username = $credenciais['username'];
    $password = $credenciais['password'];
    $dbname = $credenciais['dbname'];

    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Recupere os dados do formulário
    $nome = $_POST['nome'];
    $idfacul = $_POST['idfacul'];
    $descricao = $_POST['descricao'];
    $turno = $_POST['turno'];
    $idlider = $_POST['idlider'];

    // Gerar um número aleatório de 10 dígitos como ID do curso
    $idcurso = mt_rand(1000000000, 9999999999);

    // Processar o upload da foto
    $foto = $_FILES['foto'];
    $foto_nome = "";
    if ($foto['error'] === 0) {
        // Verifique se o arquivo é uma imagem JPEG
        $foto_info = getimagesize($foto['tmp_name']);
        if ($foto_info !== false && $foto_info['mime'] === 'image/jpeg') {
            // Renomear a foto com o ID do curso
            $foto_nome = $idcurso . '.jpg'; // Adicione o idcurso ao nome
            $foto_destino = 'imagens/' . $foto_nome;

            // Mova o arquivo para a pasta de destino
            move_uploaded_file($foto['tmp_name'], $foto_destino);
        } else {
            echo "A foto deve ser um arquivo JPEG.";
            exit;
        }
    }

    // Insira os dados na tabela de cursos (substitua 'tabela_cursos' pelo nome da sua tabela)
    $sql = "INSERT INTO cursos (nome, idfacul, descricao, turno, idlider, foto)
            VALUES ('$nome', '$idfacul', '$descricao', '$turno', '$idlider', '$foto_nome')";
    
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Dados de curso inseridos com sucesso.";
    } else {
        $mensagem = "Erro ao inserir dados de curso: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Inserir Curso</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>    
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Logo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="faculdade.php">Faculdades</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pagina_de_busca.php">Busca</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="inserir_dados.php">Adicionar</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4">
    <h3>Inserir Dados do Curso</h3>
    <form method="post" action="add_curso.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="idfacul">ID da Faculdade:</label>
            <input type="text" class="form-control" id="idfacul" name="idfacul" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descricao" required></textarea>
        </div>
        <div class="form-group">
            <label for="turno">Turno:</label>
            <input type="text" class="form-control" id="turno" name="turno" required>
        </div>
        <div class="form-group">
            <label for="idlider">ID do Líder:</label>
            <input type="text" class="form-control" id="idlider" name="idlider" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto do Curso (Apenas JPG):</label>
            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/jpeg" required>
        </div>
        <button type="submit" class="btn btn-primary">Inserir Curso</button>
    </form>
  </div>

  <!-- Modal de Mensagem -->
  <div class="modal fade" id="mensagemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Mensagem</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <?php echo $mensagem; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Script para exibir o modal automaticamente -->
  <?php if (!empty($mensagem)): ?>
  <script>
    $(document).ready(function() {
      $('#mensagemModal').modal('show');
    });
  </script>
  <?php endif; ?>
</body>
</html>
