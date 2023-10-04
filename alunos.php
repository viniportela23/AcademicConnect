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

    // Função para obter o idaluno com base no nome do aluno
    function getIdAluno($conn, $nomeAluno) {
        // Consulta SQL para obter o idaluno com base no nome do aluno
        $sql = "SELECT idaluno FROM aluno WHERE nome = '$nomeAluno'";
        
        // Executa a consulta
        $result = $conn->query($sql);
        
        // Verifica se há resultados e retorna o idaluno
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['idaluno'];
        } else {
            return null;
        }
    }

    // Verificar o tipo de ID recebido (idturma, idcurso, idfacul)
    if (isset($_GET['idturma'])) {
        // ID é do tipo idturma
        $idturma = $_GET['idturma'];

        // Consulta SQL para obter alunos com base no idturma
        $sql = "SELECT aluno.nome FROM aluno WHERE aluno.idturma = $idturma";
    } elseif (isset($_GET['idcurso'])) {
        // ID é do tipo idcurso
        $idcurso = $_GET['idcurso'];

        // Consulta SQL para obter alunos com base no idcurso
        $sql = "SELECT aluno.nome
                FROM aluno
                INNER JOIN turmas ON aluno.idturma = turmas.idturma
                WHERE turmas.idcurso = $idcurso";
    } elseif (isset($_GET['idfacul'])) {
        // ID é do tipo idfacul
        $idfacul = $_GET['idfacul'];

        // Consulta SQL para obter alunos com base no idfacul
        $sql = "SELECT aluno.nome
                FROM aluno
                INNER JOIN turmas ON aluno.idturma = turmas.idturma
                INNER JOIN cursos ON turmas.idcurso = cursos.idcurso
                WHERE cursos.idfacul = $idfacul";
    } else {
        echo "Tipo de ID não especificado.";
        exit;
    }

    // Executar a consulta SQL
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $alunos = array();
        while ($row_aluno = $result->fetch_assoc()) {
            $nomeAluno = $row_aluno['nome'];
            $idaluno = getIdAluno($conn, $nomeAluno);
            
            // Cria um link clicável para redirecionar para res_aluno.php com o idaluno
            $alunos[] = "<a href='res_aluno.php?idaluno=$idaluno'>$nomeAluno</a>";
        }
    } else {
        $alunos = array("Nenhum aluno encontrado com base no ID fornecido.");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alunos</title>
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
    <h3>Alunos</h3>
    <ul class="list-group">
        <?php
        foreach ($alunos as $aluno) {
            echo "<li class='list-group-item'>$aluno</li>";
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
