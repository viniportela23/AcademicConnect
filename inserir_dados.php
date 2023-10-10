<?php
session_start();

// Verifique se o usuário está autenticado e tem nível igual a 1
require_once('credenciais_aluno.php');

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
          <a class="nav-link" href="inserir_dados.php">Adicionar</a>
        </li>

      </ul>
    </div>
  </nav>


<div class="container mt-4">
    <h3>Escolha o tipo de dados que deseja inserir:</h3>
    <form method="post" id="tipoForm">
        <div class="form-group">
            <label for="tipo_pagina">Escolha:</label>
            <select class="form-control" id="tipo_pagina" name="tipo_pagina">
                <option value="formacao">Formação</option>
                <option value="aluno">Aluno</option>
                <option value="colaborador">Colaborador</option>
                <option value="curso">Curso</option>
                <option value="faculdade">Faculdade</option>
                <option value="turma">Turma</option>
                <option value="usuario">Usuário</option>

            </select>
        </div>
        <button type="submit" class="btn btn-primary">Inserir Dados</button>
    </form>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Script para redirecionar para a página correta com base na opção selecionada
    document.getElementById("tipoForm").addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o envio padrão do formulário

        var tipoPagina = document.getElementById("tipo_pagina").value;

        // Redireciona para a página apropriada com base na opção selecionada
        window.location.href = "add_" + tipoPagina + ".php";
    });
</script>
</body>
</html>
