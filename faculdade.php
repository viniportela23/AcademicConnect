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

    // Verifica se um parâmetro 'idfacul' está presente na URL (foi clicado em uma faculdade)
    if (isset($_GET['idfacul'])) {
        $idfacul = $_GET['idfacul'];

        // Consulta SQL para obter os dados da faculdade selecionada
        $sql_faculdade = "SELECT * FROM faculdade WHERE idfacul = $idfacul";
        $result_faculdade = $conn->query($sql_faculdade);

        if ($result_faculdade->num_rows > 0) {
            $row_faculdade = $result_faculdade->fetch_assoc();
            $faculdade_nome = $row_faculdade['nome'];
            $faculdade_descricao = $row_faculdade['descricao'];
        } else {
            echo "Faculdade não encontrada.";
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Faculdades</title>
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
    <?php
    if (isset($faculdade_nome) && isset($faculdade_descricao)) {
        echo "<h3>$faculdade_nome</h3>";
        echo "<p>$faculdade_descricao</p>";
    } else {
        echo "<h3>Selecione uma faculdade abaixo:</h3>";
        echo "<ul class='list-group'>";
        // Consulta SQL para obter todas as faculdades
        $sql_faculdades = "SELECT * FROM faculdade";
        $result_faculdades = $conn->query($sql_faculdades);

        if ($result_faculdades->num_rows > 0) {
            while ($row_faculdade = $result_faculdades->fetch_assoc()) {
                $idfacul = $row_faculdade['idfacul'];
                $nome = $row_faculdade['nome'];
                echo "<li class='list-group-item'><a href='res_facul.php?idfacul=$idfacul'>$nome</a></li>";
            }
        } else {
            echo "<li class='list-group-item'>Nenhuma faculdade encontrada.</li>";
        }
        echo "</ul>";
    }
    ?>
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
