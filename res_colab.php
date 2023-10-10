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

    // Verificar se um parâmetro 'idcolabora' está presente na URL
    if (isset($_GET['idcolabora'])) {
        $idcolabora = $_GET['idcolabora'];

        // Consulta SQL para obter as informações do colaborador
        $sql_colaborador = "SELECT * FROM colaborador WHERE idcolabora = $idcolabora";
        $result_colaborador = $conn->query($sql_colaborador);

        $conn->close();
    } else {
        echo "ID do colaborador não especificado.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Colaborador</title>
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
    <div class="row">
        <?php
        if ($result_colaborador->num_rows > 0) {
            $row_colaborador = $result_colaborador->fetch_assoc();
            ?>
            <div class="col-md-8">
                <h3>Informações do Colaborador</h3>
                <ul class="list-group">
                    <?php
                    foreach ($row_colaborador as $key => $value) {
                        if ($key != "idcolabora" && $key != "foto") {
                            echo "<li class='list-group-item'><strong>$key:</strong> $value</li>";
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="col-md-4">
                <?php
                // Verifica se a imagem está definida e não é vazia
                if (!empty($row_colaborador['foto'])) {
                    echo "<img src='imagens/{$row_colaborador['foto']}' alt='Foto do Colaborador' class='img-fluid'>";
                } else {
                    echo "Nenhuma imagem disponível para este colaborador.";
                }
                ?>
            </div>
            <?php
        } else {
            echo "Nenhum colaborador encontrado com o ID especificado.";
        }
        ?>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
