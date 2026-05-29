<?php
session_start();
require_once "../Controller/CursosController.php";
require_once "../DB/DataBase.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {
    header('Location: Index.php');
    exit();
}

$CursosController = new CursosController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $carga_horaria = $_POST['carga_horaria'];
    $professor = $_SESSION['id'];
    $cursos_id = $_SESSION['cursos_id_modulo'];

    $fotocapaPath = null;

    if (!empty($_FILES['fotocapa']['name'])) {
        $erro = $_FILES['fotocapa']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($erro === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['fotocapa']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid('capa_', true) . ($ext ? '.' . $ext : '');

            $destDir = __DIR__ . '/../uploads/cursos/';
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }

            $destFullPath = $destDir . $nomeArquivo;

            if (move_uploaded_file($_FILES['fotocapa']['tmp_name'], $destFullPath)) {
                $fotocapaPath = 'uploads/cursos/' . $nomeArquivo;
            }
        }
    }

    $resultado = $CursosController->cadastrar($nome, $descricao, $carga_horaria, $professor, $fotocapaPath);
    
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

        <a href="professor.php">Voltar para a Central do Aprendizado</a>
        <input type="submit" value="Cadastrar">


    </form>
</body>

</html>