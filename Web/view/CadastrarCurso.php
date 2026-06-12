<?php
session_start();
require_once "../Controller/CursosController.php";
require_once "../DB/DataBase.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor' ) {
    header('Location: Index.php');
    exit();
}

$CursosController = new CursosController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $_SESSION['cursos_id_modulo'] = null;
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $carga_horaria = $_POST['carga_horaria'];
    $professor = $_SESSION['id'];
    $cursos_id = $_SESSION['cursos_id_modulo'];
    $fotocapa = null;
    $certificado = null;

    
    // FOTO CAPA
      if (!empty($_FILES['fotocapa']['tmp_name'])) {
  
       $tamanhoMaximo = 2 * 1024 * 1024; // 2MB
  
         if ($_FILES['fotocapa']['size'] > $tamanhoMaximo) {
          echo "<script>
                  alert('A imagem é muito grande! Tamanho máximo permitido: 2MB.');
                  window.location.href = 'CadastrarCurso.php';
                </script>";
          exit();
          }
      }
  
      // CERTIFICADO
      if (!empty($_FILES['certificado']['tmp_name'])) {
  
          $tamanhoMaximo = 2 * 1024 * 1024; // 2MB
  
          if ($_FILES['certificado']['size'] > $tamanhoMaximo) {
              echo "<script>
                      alert('O arquivo é muito grande! Tamanho máximo permitido: 2MB.');
                      window.location.href = 'CadastrarCurso.php';
                    </script>";
              exit();
          }
      }

    $resultado = $CursosController->cadastrar($nome, $descricao, $carga_horaria, $professor, $fotocapa, $certificado);
    
    if ($resultado === "ERRO") {
        echo "Erro ao cadastrar curso. Por favor, tente novamente.";
    } else {
        header("Location: CadastroModulo.php");
        exit();
    }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/CadastrarCursos.css">
    <title>Cadastrar-se</title>
</head>

<body>
    <h1>Cadastrar Curso</h1>
    <form method="post" enctype="multipart/form-data">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" required><br><br>

        <label for="carga_horaria">Carga Horária:</label>
        <input type="number" id="carga_horaria" name="carga_horaria" required><br><br>

        <label for="fotocapa">Foto Capa do Curso:</label>
        <input type="file" id="fotocapa" name="fotocapa" accept="image/*" required><br><br>

        <label for="certificado">Certificado do Curso (imagem):</label>
        <input type="file" id="certificado" name="certificado" accept="image/*" required><br><br>

        <a href="professor.php">Voltar para a Central do Aprendizado</a>
        <input type="submit" value="Cadastrar">


    </form>
</body>

</html>