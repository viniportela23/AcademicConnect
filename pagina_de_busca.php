<?php
session_start();

// Verificar se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

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

// Inicializar variáveis de busca
$busca = "";
$tipo_pesquisa = "curso"; // Valor padrão

// Verificar se o formulário de busca foi enviado
if (isset($_POST['buscar'])) {
    $busca = $_POST['busca'];
    $tipo_pesquisa = $_POST['tipo_pesquisa'];

    // Consulta SQL para buscar com base no tipo de pesquisa
    $sql = "";
    switch ($tipo_pesquisa) {
        case "curso":
            $sql = "SELECT idcurso AS id, nome FROM cursos WHERE nome LIKE ?";
            $pagina_res = 'res_curso.php';
            $idtabela = 'idcurso';
            break;
        case "turma":
            $sql = "SELECT idturma AS id, nome FROM turmas WHERE nome LIKE ?";
            $pagina_res = 'res_turma.php';
            $idtabela = 'idturma';
            break;
        case "aluno":
            $sql = "SELECT idaluno AS id, nome FROM aluno WHERE nome LIKE ?";
            $pagina_res = 'res_aluno.php';
            $idtabela = 'idaluno';
            break;
        case "colaborador":
            $sql = "SELECT idcolabora AS id, nome FROM colaborador WHERE nome LIKE ?";
            $pagina_res = 'res_colab.php';
            $idtabela = 'idcolabora';
            break;
        case "faculdade":
            $sql = "SELECT idfacul AS id, nome FROM faculdade WHERE nome LIKE ?";
            $pagina_res = 'res_facul.php';
            $idtabela = 'idfacul';
            break;
        default:
            // Tipo de pesquisa desconhecido, você pode lidar com isso de acordo com suas necessidades
            break;
    }

    // Usar instruções preparadas para evitar injeção SQL
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        // Adicionar o caractere '%' ao redor do termo de busca
        $buscaParam = "%$busca%";
        $stmt->bind_param("s", $buscaParam);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo "Erro na preparação da declaração SQL.";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Resultados da Busca</title>
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
        <div class="col-md-8">
            <h3>Resultados da Busca</h3>
            <form method="POST" action="pagina_de_busca.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="busca" placeholder="Digite o termo de busca">
                </div>
                <div class="form-group">
                    <label for="tipo_pesquisa">Escolha o que deseja pesquisar:</label>
                    <select class="form-control" name="tipo_pesquisa" id="tipo_pesquisa">
                        <option value="curso">Curso</option>
                        <option value="turma">Turma</option>
                        <option value="aluno">Aluno</option>
                        <option value="colaborador">Colaborador</option>
                        <option value="faculdade">Faculdade</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="buscar">Buscar</button>
            </form>
        </div>
    </div>
    <div class="row mt-4">
        <?php
        if (isset($result) && $result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $nome = $row['nome'];

                // Exibir o nome como um link clicável para a página correspondente
                echo '<div class="col-md-6">';
                echo '<ul class="list-group">';
                echo "<li class='list-group-item'><a href='$pagina_res?$idtabela=$id'>$nome</a></li>";
                echo '</ul>';
                echo '</div>';
            }
        } else {
            echo "Nenhum resultado encontrado com o termo de busca especificado.";
        }
        ?>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
