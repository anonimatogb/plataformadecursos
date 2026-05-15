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

    $resultado = $CursosController->cadastrar($nome, $descricao, $carga_horaria, $professor);

    if ($resultado === "ERRO") {
        echo "Erro ao cadastrar curso. Por favor, tente novamente.";
    } else {
        header("Location: professor.php");
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
    <form method="post">

        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="descricao">Descrição:</label>
        <input type="text" id="descricao" name="descricao" required><br><br>

        <label for="carga_horaria">Carga Horária:</label>
        <input type="number" id="carga_horaria" name="carga_horaria" required><br><br>
        <a href="professor.php">Voltar para a Central do Aprendizado</a>
        <input type="submit" value="Cadastrar">


    </form>
</body>

</html>