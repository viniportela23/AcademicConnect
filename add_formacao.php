<?php
session_start();

// Verifique se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

$mensagem = ""; // Variável para armazenar a mensagem

// Lógica para a inserção de dados
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

    // Obter dados do formulário
    $iduser = $_POST['iduser'];
    $titulo = $_POST['titulo'];
    $numero = $_POST['numero'];

    // Remover hífens da data antes de inseri-la no banco de dados
    $periodo = $_POST['periodo'];
    $periodo = str_replace("-", "", $periodo);

    // Preparar a instrução SQL para inserção
    $sql = "INSERT INTO formacoes (iduser, titulo, numero, periodo) VALUES (?, ?, ?, ?)";

    // Preparar a declaração
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Vincular os parâmetros
        $stmt->bind_param("issi", $iduser, $titulo, $numero, $periodo);

        // Executar a instrução preparada
        if ($stmt->execute()) {
            $mensagem = "Formação adicionada com sucesso."; // Define a mensagem de sucesso
        } else {
            $mensagem = "Erro ao inserir dados: " . $stmt->error;
        }

        // Fechar a declaração
        $stmt->close();
    } else {
        $mensagem = "Erro na preparação da declaração: " . $conn->error;
    }

    // Fechar a conexão com o banco de dados
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página do Aluno</title>
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
    <h3>Inserir Formação</h3>
    <form method="post">
        <div class="form-group">
            <label for="iduser">ID do Usuário:</label>
            <input type="text" class="form-control" id="iduser" name="iduser" required>
        </div>
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="form-group">
            <label for="numero">Número:</label>
            <input type="text" class="form-control" id="numero" name="numero" required>
        </div>
        <div class="form-group">
            <label for="periodo">Período:</label>
            <input type="date" class="form-control" id="periodo" name="periodo" required>
        </div>
        <button type="submit" class="btn btn-primary">Inserir Formação</button>
    </form>

    <!-- Modal para exibir a mensagem -->
    <div class="modal" tabindex="-1" role="dialog" id="mensagemModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mensagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    // Exibe a mensagem no modal
                    if (!empty($mensagem)) {
                        echo '<p>' . $mensagem . '</p>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Exibir o modal quando a página for carregada e houver uma mensagem
    $(document).ready(function () {
        <?php
        if (!empty($mensagem)) {
            echo '$("#mensagemModal").modal("show");';
        }
        ?>
    });
</script>
</body>
</html>
