<?php
session_start();

$credenciais = parse_ini_file("credenciais_banco.txt");
$endereco = $credenciais["host"];
$usuario = $credenciais["username"];
$senha = $credenciais["password"];
$banco = $credenciais["dbname"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar entrada de usuário
    $usuario_entrada = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
    $senha_entrada = $_POST["senha"];

    if (empty($usuario_entrada) || empty($senha_entrada)) {
        // Exibir mensagem de erro se o usuário ou senha estiverem em branco
        echo "<script>alert('Usuário e senha são obrigatórios.');</script>";
    } else {
        try {
            $pdo = new PDO("mysql:dbname=".$banco.";host=".$endereco, $usuario, $senha);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT iduser, senha_criptografada, nivel FROM usuarios WHERE usuario = :usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':usuario', $usuario_entrada);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && password_verify($senha_entrada, $row['senha_criptografada'])) {
                // Senha correta, usuário autenticado
                $_SESSION['usuario'] = $usuario_entrada;
                $_SESSION['iduser'] = $row['iduser'];
                $_SESSION['nivel'] = $row['nivel'];

                // Verificar o nível do usuário e redirecionar para a página correspondente
                $nivel = $row['nivel'];
                if ($nivel == 1) {
                    header("Location: home_aluno.php");
                } elseif ($nivel == 2) {
                    header("Location: home_professor.php");
                } else {
                    // Nível inválido
                    echo "<script>alert('Nível de usuário inválido.');</script>";
                }
                exit();
            } else {
                // Senha incorreta
                echo "<script>alert('Usuário ou senha incorretos.');</script>";
            }
        } catch (PDOException $e) {
            // Registrar erros em um arquivo de log
            error_log("Erro: " . $e->getMessage(), 0);
            echo "<script>alert('Ocorreu um erro durante a autenticação.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>    
    <form class="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="card">
            <div class="card_top">
                <h1 class="titulo">Seja bem-vindo ao sistema!</h1>
                <p class="texto1">Digite seu usuário e senha</p>
            </div>
            <div class="texto2">
                <label>Usuário:</label>
                <input type='text' name='usuario' placeholder="Digite seu usuário">
            </div>
            <div class="texto2">
                <label>Senha:</label>
                <input type='password' name='senha' placeholder="Digite sua senha">
            </div>
            <div class="texto2">
                <label><input type="checkbox"> Lembre-me</label>
            </div>
            <div class="texto2 btn">
                <button type='submit'>Continuar</button>
            </div>
        </div>
    </form>
</body>
</html>
