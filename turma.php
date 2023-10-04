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

    // Verificar se um parâmetro 'idcurso' está presente na URL
    if (isset($_GET['idcurso'])) {
        $idcurso = $_GET['idcurso'];

        // Consulta SQL para obter as turmas relacionadas ao curso
        $sql_turmas = "SELECT * FROM turmas WHERE idcurso = $idcurso";
        $result_turmas = $conn->query($sql_turmas);

        $turmas = array();
        if ($result_turmas->num_rows > 0) {
            while ($row_turma = $result_turmas->fetch_assoc()) {
                $idturma = $row_turma['idturma'];
                $turma_nome = $row_turma['nome'];
                $turmas[] = array('idturma' => $idturma, 'nome' => $turma_nome);
            }
        } else {
            $turmas[] = array('idturma' => null, 'nome' => "Nenhuma turma encontrada para este curso.");
        }
    } else {
        echo "ID do curso não especificado.";
        exit;
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
    <div class="row mt-4">
        <div class="col-md-12">
            <h4>Turmas Disponíveis</h4>
            <ul class="list-group">
                <?php
                foreach ($turmas as $turma) {
                    $idturma = $turma['idturma'];
                    $turma_nome = $turma['nome'];
                    echo "<li class='list-group-item'><a href='res_turma.php?idturma=$idturma'>$turma_nome</a></li>";
                }
                ?>
            </ul>
        </div>
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
