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

    // Função para obter o idcolabora com base no nome do colaborador
    function getIdColaborador($conn, $nomeColaborador) {
        // Consulta SQL para obter o idcolabora com base no nome do colaborador
        $sql = "SELECT idcolabora FROM colaborador WHERE nome = '$nomeColaborador'";
        
        // Executa a consulta
        $result = $conn->query($sql);
        
        // Verifica se há resultados e retorna o idcolabora
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['idcolabora'];
        } else {
            return null;
        }
    }

    // Verificar o tipo de ID recebido (idturma, idcurso, idfacul)
    if (isset($_GET['idturma'])) {
        // ID é do tipo idturma
        $idturma = $_GET['idturma'];

        // Consulta SQL para obter colaboradores com base no idturma
        $sql = "SELECT colaborador.nome FROM colaborador WHERE colaborador.idturma = $idturma";
    } elseif (isset($_GET['idcurso'])) {
        // ID é do tipo idcurso
        $idcurso = $_GET['idcurso'];

        // Consulta SQL para obter colaboradores com base no idcurso
        $sql = "SELECT colaborador.nome
                FROM colaborador
                INNER JOIN cursos ON colaborador.idcordenador = cursos.idcurso
                WHERE cursos.idcurso = $idcurso";
    } elseif (isset($_GET['idfacul'])) {
        // ID é do tipo idfacul
        $idfacul = $_GET['idfacul'];

        // Consulta SQL para obter colaboradores com base no idfacul
        $sql = "SELECT colaborador.nome
                FROM colaborador
                INNER JOIN cursos ON colaborador.idcordenador = cursos.idcurso
                INNER JOIN faculdade ON cursos.idfacul = faculdade.idfacul
                WHERE faculdade.idfacul = $idfacul";
    } else {
        echo "Tipo de ID não especificado.";
        exit;
    }

    // Executar a consulta SQL
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $colaboradores = array();
        while ($row_colaborador = $result->fetch_assoc()) {
            $nomeColaborador = $row_colaborador['nome'];
            $idcolabora = getIdColaborador($conn, $nomeColaborador);
            
            // Cria um link clicável para redirecionar para res_colaborador.php com o idcolabora
            $colaboradores[] = "<a href='res_colaborador.php?idcolabora=$idcolabora'>$nomeColaborador</a>";
        }
    } else {
        $colaboradores = array("Nenhum colaborador encontrado com base no ID fornecido.");
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
                <a class="nav-link" href="#">Contato</a>
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
