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

// Verificar se um parâmetro 'idturma' está presente na URL
if (isset($_GET['idturma'])) {
    // Validar e limpar a entrada do usuário
    $idturma = filter_var($_GET['idturma'], FILTER_VALIDATE_INT);

    // Verificar se $idturma é um número inteiro válido
    if ($idturma === false) {
        echo "ID da turma inválido.";
        exit;
    }

    // Consulta SQL para obter os detalhes da turma selecionada usando declaração preparada
    $sql_turma = "SELECT * FROM turmas WHERE idturma = ?";
    $stmt_turma = $conn->prepare($sql_turma);
    $stmt_turma->bind_param("i", $idturma);
    $stmt_turma->execute();
    $result_turma = $stmt_turma->get_result();

    if ($result_turma->num_rows > 0) {
        $row_turma = $result_turma->fetch_assoc();
        $turma_nome = $row_turma['nome'];
        $turma_idcurso = $row_turma['idcurso'];
        $turma_foto = $row_turma['foto'];
        $turma_ano_inicio = $row_turma['ano_inicio'];
        $turma_ano_fim = $row_turma['ano_fim'];
        $turma_turno = $row_turma['turno'];
    } else {
        echo "Turma não encontrada.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes da Turma</title>
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
            if (isset($turma_nome)) {
                echo "<h3>$turma_nome</h3>";
                echo "<p>Ano de Início: $turma_ano_inicio</p>";
                echo "<p>Ano de Fim: $turma_ano_fim</p>";
                echo "<p>Turno: $turma_turno</p>";
            } else {
                echo "Nenhuma turma selecionada.";
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if (!empty($turma_foto)) {
                echo "<img src='imagens/$turma_foto' alt='$turma_nome' class='img-fluid'>";
            } else {
                echo "Nenhuma imagem disponível para esta turma.";
            }
            ?>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <a href="alunos.php?idturma=<?php echo $idturma; ?>" class="btn btn-primary btn-block">Alunos da Turma</a>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
