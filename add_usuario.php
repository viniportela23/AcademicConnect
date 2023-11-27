<?php
session_start();

require_once('credenciais_aluno.php');

$credenciais = parse_ini_file("credenciais_banco.txt");
$endereco = $credenciais["host"];
$usuario = $credenciais["username"];
$senha = $credenciais["password"];
$banco = $credenciais["dbname"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar e filtrar os dados de entrada
    $usuario_entrada = filter_var($_POST["usuario"], FILTER_SANITIZE_STRING);
    $senha_entrada = filter_var($_POST["senha"], FILTER_SANITIZE_STRING);

    // Verificar se o usuário já existe
    try {
        $pdo = new PDO("mysql:dbname=".$banco.";host=".$endereco, $usuario, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $verificar_sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
        $verificar_stmt = $pdo->prepare($verificar_sql);
        $verificar_stmt->bindParam(':usuario', $usuario_entrada);
        $verificar_stmt->execute();

        if ($verificar_stmt->rowCount() > 0) {
            // Redirecionar para a página de usuário não criado
            header("Location: usuario_nao_criado.php");
            exit();
        }

        // Criptografar a senha
        $senha_criptografada = password_hash($senha_entrada, PASSWORD_DEFAULT);

        // Inserir novo usuário
        $inserir_sql = "INSERT INTO usuarios (usuario, senha_criptografada) VALUES (:usuario, :senha_criptografada)";
        $inserir_stmt = $pdo->prepare($inserir_sql);
        $inserir_stmt->bindParam(':usuario', $usuario_entrada);
        $inserir_stmt->bindParam(':senha_criptografada', $senha_criptografada);
        $inserir_stmt->execute();

        if ($inserir_stmt->rowCount() > 0) {
            // Redirecionar para a página de sucesso
            header("Location: usuario_criado.php");
            exit();
        } else {
            // Redirecionar para a página de usuário não criado
            header("Location: usuario_nao_criado.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        exit();  // Importante sair após um erro
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="card">
            <div class="card_top">
                <h1 class="titulo">Adicione um usuário ao sistema!</h1>
            </div>
            <div class="texto2">
                <label>Usuário:</label>
                <input type="text" name="usuario" placeholder="Digite o nome de usuário" required>
            </div>
            <div class="texto2">
                <label>Senha:</label>
                <input type="password" name="senha" placeholder="Digite a senha" required>
            </div>
            <div class="texto2 btn">
                <button type="submit">Criar</button>
            </div>
        </div>
    </form>
</body>
</html>
