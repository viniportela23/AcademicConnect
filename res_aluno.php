<?php
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

// Verificar se um parâmetro 'idaluno' está presente na URL
if (isset($_GET['idaluno'])) {
    $idaluno = $_GET['idaluno'];

    // Consulta SQL para obter os dados do aluno selecionado
    $sql_aluno = "SELECT * FROM aluno WHERE idaluno = $idaluno";
    $result_aluno = $conn->query($sql_aluno);

    if ($result_aluno->num_rows > 0) {
        $row_aluno = $result_aluno->fetch_assoc();
        $aluno_nome = $row_aluno['nome'];
        $aluno_matricula = $row_aluno['matricula'];
        $aluno_ano_inicio = $row_aluno['ano_inicio'];
        $aluno_datanascimento = $row_aluno['datanascimento'];
        $aluno_foto = $row_aluno['foto'];
        $aluno_cidade = $row_aluno['cidade'];
        $aluno_descricao = $row_aluno['descricao'];
        $aluno_idturma = $row_aluno['idturma'];
        // Adicione aqui outras colunas que deseja exibir
    } else {
        echo "Aluno não encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aluno</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Logo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="faculdade.php">Faculdade</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contato</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php
            if (isset($aluno_nome)) {
                echo "<h3>Informações do Aluno</h3>";
                echo "<p>Nome: $aluno_nome</p>";
                echo "<p>Matrícula: $aluno_matricula</p>";
                echo "<p>Ano de Início: $aluno_ano_inicio</p>";
                echo "<p>Data de Nascimento: $aluno_datanascimento</p>";
                echo "<p>Cidade: $aluno_cidade</p>";
                echo "<p>Descrição: $aluno_descricao</p>";
                echo "<p>ID da Turma: $aluno_idturma</p>";
                // Adicione aqui outras informações do aluno que deseja exibir
            } else {
                echo "Nenhum aluno selecionado.";
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if (!empty($aluno_foto)) {
                echo "<img src='imagens/$aluno_foto' alt='$aluno_nome' class='img-fluid'>";
            } else {
                echo "Nenhuma imagem disponível para este aluno.";
            }
            ?>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>