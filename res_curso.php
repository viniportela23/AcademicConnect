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

// Verificar se um parâmetro 'idcurso' está presente na URL
if (isset($_GET['idcurso'])) {
    $idcurso = $_GET['idcurso'];

    // Consulta SQL para obter os detalhes do curso selecionado, incluindo o nome do coordenador
    $sql_curso = "SELECT cursos.*, colaborador.nome AS nome_coordenador 
                  FROM cursos 
                  LEFT JOIN colaborador ON cursos.idcordenador = colaborador.idcolabora 
                  WHERE idcurso = $idcurso";
    $result_curso = $conn->query($sql_curso);

    if ($result_curso->num_rows > 0) {
        $row_curso = $result_curso->fetch_assoc();
        $curso_nome = $row_curso['nome'];
        $curso_descricao = $row_curso['descricao'];
        $curso_foto = $row_curso['foto'];
        $nome_coordenador = $row_curso['nome_coordenador'];
    } else {
        echo "Curso não encontrado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Curso</title>
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
                <a class="nav-link" href="#">Contato</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php
            if (isset($curso_nome) && isset($curso_descricao)) {
                echo "<h3>$curso_nome</h3>";
                echo "<p>$curso_descricao</p>";
                if (isset($nome_coordenador)) {
                    echo "<p>Coordenador: $nome_coordenador</p>";
                }
            } else {
                echo "Nenhum curso selecionado.";
            }
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if (!empty($curso_foto)) {
                echo "<img src='imagens/$curso_foto' alt='$curso_nome' class='img-fluid'>";
            } else {
                echo "Nenhuma imagem disponível para este curso.";
            }
            ?>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <a href="turma.php?idcurso=<?php echo $idcurso; ?>" class="btn btn-primary btn-block">Turmas</a>
        </div>
        <div class="col-md-4">
            <a href="alunos.php?idcurso=<?php echo $idcurso; ?>" class="btn btn-primary btn-block">Alunos</a>
        </div>
        <div class="col-md-4">
            <a href="colaboradores.php?idcurso=<?php echo $idcurso; ?>" class="btn btn-primary btn-block">Colaboradores</a>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
