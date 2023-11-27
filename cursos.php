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

    // Função para evitar injeção de SQL e XSS
    function validateInput($input) {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    // Verifica se um parâmetro 'idfacul' está presente na URL (foi clicado em uma faculdade)
    if (isset($_GET['idfacul'])) {
        $idfacul = validateInput($_GET['idfacul']);

        // Consulta SQL preparada para obter os cursos da faculdade selecionada
        $sql_cursos = "SELECT * FROM cursos WHERE idfacul = ?";
        
        // Preparar a declaração
        $stmt_cursos = $conn->prepare($sql_cursos);

        if ($stmt_cursos) {
            // Vincular o parâmetro
            $stmt_cursos->bind_param('i', $idfacul);

            // Executar a consulta
            $stmt_cursos->execute();

            // Obter resultados
            $result_cursos = $stmt_cursos->get_result();

            if ($result_cursos->num_rows > 0) {
                $cursos = array();
                while ($row_curso = $result_cursos->fetch_assoc()) {
                    $idcurso = $row_curso['idcurso'];
                    $cursos[$idcurso] = $row_curso['nome'];
                }
            } else {
                $cursos = array("Nenhum curso encontrado para esta faculdade.");
            }

            // Fechar a declaração
            $stmt_cursos->close();
        } else {
            die("Erro na preparação da consulta SQL: " . $conn->error);
        }
    } else {
        echo "ID da faculdade não especificado.";
        exit;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cursos da Faculdade</title>
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
                <a class="nav-link" href="pagina_de_busca">Busca</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="inserir_dados.php">Adicionar</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h3>Cursos da Faculdade Selecionada</h3>
    <ul class="list-group">
        <?php
        foreach ($cursos as $idcurso => $curso) {
            echo "<li class='list-group-item'><a href='res_curso.php?idcurso=$idcurso'>$curso</a></li>";
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
