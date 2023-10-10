<?php
session_start();

// Verificar se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

// Lógica para inserção de dados de aluno no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Recupere os dados do formulário
    $nome = $_POST['nome'];
    $matricula = $_POST['matricula'];
    $ano_inicio = $_POST['ano_inicio'];
    $datanascimento = $_POST['datanascimento'];
    $cidade = $_POST['cidade'];
    $descricao = $_POST['descricao'];
    $idusuario = $_POST['idusuario'];
    $idturma = $_POST['idturma'];

    // Gerar um número aleatório de 10 dígitos como ID do aluno
    $idaluno = mt_rand(1000000000, 9999999999);

    // Processar o upload da foto
    $foto = $_FILES['foto'];
    $foto_nome = "";
    if ($foto['error'] === 0) {
        // Verifique se o arquivo é uma imagem JPEG
        $foto_info = getimagesize($foto['tmp_name']);
        if ($foto_info !== false && $foto_info['mime'] === 'image/jpeg') {
            // Renomear a foto com o ID do aluno
            $foto_nome = $idusuario . '_' . $idaluno . '.jpg'; // Adicione o idaluno ao nome
            $foto_destino = 'imagens/' . $foto_nome;

            // Mova o arquivo para a pasta de destino
            move_uploaded_file($foto['tmp_name'], $foto_destino);
        } else {
            echo "A foto deve ser um arquivo JPEG.";
            exit;
        }
    }

    // Insira os dados na tabela de alunos (substitua 'tabela_alunos' pelo nome da sua tabela)
    $sql = "INSERT INTO aluno (nome, matricula, ano_inicio, datanascimento, cidade, descricao, idusuario, idturma, foto)
            VALUES ('$nome', '$matricula', '$ano_inicio', '$datanascimento', '$cidade', '$descricao', '$idusuario', '$idturma', '$foto_nome')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Dados de aluno inseridos com sucesso.";
    } else {
        echo "Erro ao inserir dados de aluno: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Página do Aluno</title>
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
          <a class="nav-link" href="inserir_dados.php">adicionar</a>
        </li>

      </ul>
    </div>
  </nav>

<div class="container mt-4">
    <h3>Inserir Dados do Aluno</h3>
    <form method="post" action="add_aluno.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="matricula">Matrícula:</label>
            <input type="text" class="form-control" id="matricula" name="matricula" required>
        </div>
        <div class="form-group">
            <label for="ano_inicio">Ano de Início:</label>
            <input type="date" class="form-control" id="ano_inicio" name="ano_inicio" required>
        </div>
        <div class="form-group">
            <label for="datanascimento">Data de Nascimento:</label>
            <input type="date" class="form-control" id="datanascimento" name="datanascimento" required>
        </div>
        <div class="form-group">
            <label for="cidade">Cidade:</label>
            <input type="text" class="form-control" id="cidade" name="cidade" required>
        </div>
        <div class="form-group">
            <label for="idusuario">ID do Usuário:</label>
            <input type="text" class="form-control" id="idusuario" name="idusuario" required>
        </div>
        <div class="form-group">
            <label for="idturma">ID da Turma:</label>
            <input type="text" class="form-control" id="idturma" name="idturma" required>
        </div>
        <div class="form-group">
            <label for="foto">Foto de Perfil (Apenas JPG):</label>
            <input type="file" class="form-control-file" id="foto" name="foto" accept="image/jpeg" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea class="form-control" id="descricao" name="descricao" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Inserir Aluno</button>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
