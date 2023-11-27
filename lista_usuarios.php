<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Logo</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <div class="container">
        <h1 class="mt-4">Lista de Usuários</h1>

        <div class="table-responsive mt-4">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Nível</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();

                    // Verificar se o usuário está autenticado e tem nível igual a 1
                    require_once('credenciais_aluno.php');

                    $credenciais = parse_ini_file("credenciais_banco.txt");
                    $endereco = $credenciais["host"];
                    $usuario = $credenciais["username"];
                    $senha = $credenciais["password"];
                    $banco = $credenciais["dbname"];

                    try {
                        $pdo = new PDO("mysql:host=".$endereco.";dbname=".$banco, $usuario, $senha);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "SELECT iduser, usuario, nivel, email FROM usuarios";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>".htmlspecialchars($row['iduser'])."</td>";
                            echo "<td>".htmlspecialchars($row['usuario'])."</td>";
                            echo "<td>".htmlspecialchars($row['nivel'])."</td>";
                            echo "<td>".htmlspecialchars($row['email'])."</td>";
                            echo "</tr>";
                        }
                    } catch (PDOException $e) {
                        echo "Erro na consulta SQL.";
                        // Log do erro em um arquivo ou local seguro, não exiba mensagens detalhadas na produção.
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <a class="btn btn-primary mt-4" href="add_usuario.php" role="button">Adicionar Novo Usuário</a>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
