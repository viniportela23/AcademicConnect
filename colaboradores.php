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

    // Verificar se um parâmetro 'idfacul' ou 'idcurso' está presente na URL
    if (isset($_GET['idfacul'])) {
        $idfacul = $_GET['idfacul'];

        // Consulta SQL para obter os colaboradores da faculdade selecionada
        $sql_colaboradores = "SELECT * FROM colaborador WHERE idcolabora IN (SELECT idcolabora FROM participa WHERE idcurso IN (SELECT idcurso FROM cursos WHERE idfacul = $idfacul))";
        $result_colaboradores = $conn->query($sql_colaboradores);

        if (!$result_colaboradores) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        if ($result_colaboradores->num_rows > 0) {
            $colaboradores = array();
            while ($row_colaborador = $result_colaboradores->fetch_assoc()) {
                $idcolabora = $row_colaborador['idcolabora'];
                $nomecolabora = $row_colaborador['nome'];
                $colaboradores[] = "<a href='res_colab.php?idcolabora=$idcolabora'>$nomecolabora</a>";
            }
        } else {
            $colaboradores = array("Nenhum colaborador encontrado para esta faculdade.");
        }
    } elseif (isset($_GET['idcurso'])) {
        $idcurso = $_GET['idcurso'];

        // Consulta SQL para obter os colaboradores do curso selecionado
        $sql_colaboradores = "SELECT * FROM colaborador WHERE idcolabora IN (SELECT idcolabora FROM participa WHERE idcurso = $idcurso)";
        $result_colaboradores = $conn->query($sql_colaboradores);

        if (!$result_colaboradores) {
            die("Erro na consulta SQL: " . $conn->error);
        }

        if ($result_colaboradores->num_rows > 0) {
            $colaboradores = array();
            while ($row_colaborador = $result_colaboradores->fetch_assoc()) {
                $idcolabora = $row_colaborador['idcolabora'];
                $nomecolabora = $row_colaborador['nome'];
                $colaboradores[] = "<a href='res_colab.php?idcolabora=$idcolabora'>$nomecolabora</a>";
            }
        } else {
            $colaboradores = array("Nenhum colaborador encontrado para este curso.");
        }
    } else {
        echo "ID da faculdade ou curso não especificado.";
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Colaboradores</title>
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
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h3>Colaboradores</h3>
    <ul class="list-group">
        <?php
        foreach ($colaboradores as $colaborador) {
            echo "<li class='list-group-item'>$colaborador</li>";
        }
        ?>
    </ul>
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
