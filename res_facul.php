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

// Verificar se um parâmetro 'idfacul' está presente na URL
if (isset($_GET['idfacul'])) {
    // Validar e limpar a entrada do usuário
    $idfacul = filter_var($_GET['idfacul'], FILTER_VALIDATE_INT);

    // Verificar se $idfacul é um número inteiro válido
    if ($idfacul === false) {
        echo "ID da faculdade inválido.";
        exit;
    }

    // Consulta SQL para obter os dados da faculdade selecionada usando declaração preparada
    $sql_faculdade = "SELECT * FROM faculdade WHERE idfacul = ?";
    $stmt_faculdade = $conn->prepare($sql_faculdade);
    $stmt_faculdade->bind_param("i", $idfacul);
    $stmt_faculdade->execute();
    $result_faculdade = $stmt_faculdade->get_result();

    if ($result_faculdade->num_rows > 0) {
        $row_faculdade = $result_faculdade->fetch_assoc();
        $faculdade_nome = $row_faculdade['nome'];
        $faculdade_descricao = $row_faculdade['descricao'];
        $faculdade_foto = $row_faculdade['foto'];
        $diretor = $row_faculdade['diretor'];
        $ano_fundacao = $row_faculdade['ano_fundacao'];

    } else {
        echo "Faculdade não encontrada.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculdade</title>
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
            </li>        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php
            if (isset($faculdade_nome) && isset($faculdade_descricao)) {
                echo "<h3>$faculdade_nome</h3>";
                echo "<p>Drecrição: $faculdade_descricao</p>";
                echo "<p>Diretor: $diretor</p>";
                echo "<p>Ano de Fundação: $ano_fundacao</p>";

            } else {
                echo "Nenhuma faculdade selecionada.";
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if (!empty($faculdade_foto)) {
                echo "<img src='imagens/$faculdade_foto' alt='$faculdade_nome' class='img-fluid'>";
            } else {
                echo "Nenhuma imagem disponível para esta faculdade.";
            }
            ?>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <a href="cursos.php?idfacul=<?php echo $idfacul; ?>" class="btn btn-primary btn-block">Cursos</a>
        </div>
        <div class="col-md-4">
            <a href="alunos.php?idfacul=<?php echo $idfacul; ?>" class="btn btn-primary btn-block">Alunos</a>
        </div>
        <div class="col-md-4">
            <a href="colaboradores.php?idfacul=<?php echo $idfacul; ?>" class="btn btn-primary btn-block">Colaboradores</a>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
