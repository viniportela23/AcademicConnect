<?php
session_start();

// Verificar se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

$mensagem = ""; // Variável para armazenar a mensagem

// Lógica para inserção de dados da turma no banco de dados
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
    $idcurso = $_POST['idcurso'];
    $ano_inicio = $_POST['ano_inicio'];
    $ano_fim = $_POST['ano_fim'];
    $turno = $_POST['turno'];

    // Processar o upload da foto
    $foto = $_FILES['foto'];
    $foto_nome = "";

    if ($foto['error'] === 0) {
        $foto_info = getimagesize($foto['tmp_name']);
        if ($foto_info !== false && $foto_info['mime'] === 'image/jpeg') {
            // Gerar um nome aleatório para a foto
            $foto_nome = uniqid() . '.jpg';
            $foto_destino = 'imagens/' . $foto_nome;
            move_uploaded_file($foto['tmp_name'], $foto_destino);
        } else {
            echo "A foto deve ser um arquivo JPEG.";
            exit;
        }
    }

    // Use declarações preparadas para evitar injeção SQL
    $stmt = $conn->prepare("INSERT INTO turmas (nome, idcurso, foto, ano_inicio, ano_fim, turno) VALUES (?, ?, ?, ?, ?, ?)");

    // Vincular parâmetros
    $stmt->bind_param('ssssss', $nome, $idcurso, $foto_nome, $ano_inicio, $ano_fim, $turno);

    if ($stmt->execute()) {
        $mensagem = "Dados da turma inseridos com sucesso.";
    } else {
        $mensagem = "Erro ao inserir dados da turma: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inserir Turma</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Logo</a>
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
  </nav></nav>

<div class="container mt-4">
    <h3>Inserir Dados da Turma</h3>
    <form method="post" action="add_turma.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="idcurso">ID do Curso:</label>
            <input type="text" class="form-control" id="idcurso" name="idcurso" required>
        </div>
        <div class="form-group">
            <label for="ano_inicio">Ano de Início:</label>
            <input type="date" class="form-control" id="ano_inicio" name="ano_inicio" required>
        </div>
        <div class="form-group">
            <label for="ano_fim">Ano de Fim:</label>
            <input type="date" class="form-control" id="ano_fim" name="ano_fim" required>
        </div>
        <div class="form-group">
            <label for="turno">Turno:</label>
            <input type="text" class="form-control" id="turno" name="turno" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto de Perfil (Apenas JPG):</label>
            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/jpeg" required>
        </div>
        <button type="submit" class="btn btn-primary">Inserir Turma</button>
    </form>
</div>

<!-- Se a mensagem estiver definida, exibe o modal -->
<?php if (!empty($mensagem)) : ?>
    <div class="modal" tabindex="-1" role="dialog" id="myModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mensagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php echo $mensagem; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Abre automaticamente o modal quando a página carregar
        $(document).ready(function() {
            $('#myModal').modal('show');
        });
    </script>
<?php endif; ?>

</body>
</html>
