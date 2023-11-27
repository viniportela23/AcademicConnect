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
    // Validar e limpar a entrada do usuário
    $idcurso = filter_var($_GET['idcurso'], FILTER_VALIDATE_INT);

    // Verificar se $idcurso é um número inteiro válido
    if ($idcurso === false) {
        echo "ID do curso inválido.";
        exit;
    }

    // Consulta SQL com declaração preparada
    $sql_curso = "SELECT cursos.*, aluno.nome AS nome_lider, GROUP_CONCAT(colaborador_prof.nome) AS professores
                  FROM cursos 
                  LEFT JOIN aluno ON cursos.idlider = aluno.idaluno
                  LEFT JOIN participa ON cursos.idcurso = participa.idcurso
                  LEFT JOIN colaborador AS colaborador_prof ON participa.idcolabora = colaborador_prof.idcolabora AND participa.funcao = 1
                  WHERE cursos.idcurso = ?
                  GROUP BY cursos.idcurso";
    
    $stmt = $conn->prepare($sql_curso);
    $stmt->bind_param("i", $idcurso);
    $stmt->execute();
    $result_curso = $stmt->get_result();

    if ($result_curso->num_rows > 0) {
        $row_curso = $result_curso->fetch_assoc();
        $curso_nome = $row_curso['nome'];
        $curso_descricao = $row_curso['descricao'];
        $curso_foto = $row_curso['foto'];
        $nome_lider = $row_curso['nome_lider'];
        $professores = $row_curso['professores'];
    } else {
        echo "Curso não encontrado.";
        exit;
    }

    // Consulta adicional (a que você forneceu)
    $sql_adicional = "SELECT coordenador.nome AS coordenador
                      FROM participa AS participa_colaborador
                      LEFT JOIN participa AS participa_coordenador ON participa_colaborador.idcurso = participa_coordenador.idcurso AND participa_coordenador.funcao = 2
                      LEFT JOIN colaborador AS coordenador ON participa_coordenador.idcolabora = coordenador.idcolabora
                      WHERE participa_colaborador.idcurso = ?";
    
    $stmt_adicional = $conn->prepare($sql_adicional);
    $stmt_adicional->bind_param("i", $idcurso);
    $stmt_adicional->execute();
    $result_adicional = $stmt_adicional->get_result();

    if ($result_adicional->num_rows > 0) {
        $row_adicional = $result_adicional->fetch_assoc();
        $coordenador = $row_adicional['coordenador'];
    } else {
        $coordenador = "Nenhum coordenador encontrado.";
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
            if (isset($curso_nome) && isset($curso_descricao)) {
                echo "<h3>$curso_nome</h3>";
                echo "<p>$curso_descricao</p>";
                echo "<p>Coordenador: $coordenador</p>";
                echo "<p>Professores: $professores</p>";
                echo "<p>Líder: $nome_lider</p>";
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
