<?php
session_start();

// Verificar se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

// Verificar se o cookie com o id_user está definido
if (isset($_SESSION['iduser'])) {
    $id_user = $_SESSION['iduser'];

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
                <li class="nav-item">
          <a class="nav-link" href="lista_usuarios.php">Lista de Usuários</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <div class="jumbotron rounded card">
      <h1 class="display-4">Bem-vindo ao Sistema</h1>
      <p class="lead">O Portal Estudantil Mais Bem Otimizado e Lindo.</p>
      <hr class="my-4">
      <p>Recadinhos da Paroquia.</p>
      <a class="btn btn-primary btn-lg" href="noticias.php" role="button">Saiba mais</a>
    </div>
  </div>

  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
    $conn->close();
} else {
    echo "ID do usuário não encontrado.";
}
?>
