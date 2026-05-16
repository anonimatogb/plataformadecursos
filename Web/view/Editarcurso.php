<?php
session_start();
require_once "../db/database.php";
require_once "../controller/CursosController.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {
    header("Location: Index.php");
    exit;
}

$cursosController = new CursosController($pdo);
$cursos = $cursosController->buscar($_SESSION['edit']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cursosController->atualizar(
        $_SESSION['edit'],
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['carga_horaria']
    );
}
if(!isset($_SESSION['edit'])){
   echo "<script>
    alert('Curso não encontrado!');
    </script>";
header("Location: professor.php");
exit;
   
}

?>
<head>
    <title>Editar Curso</title>
</head>

<link rel="stylesheet" href="css/style2.css">



<form method="POST">
    Nome: <input type="text" name="nome" value="<?= $cursos['nome'] ?>"><br><br>

    Descrição: <input type="text" name="descricao" value="<?= $cursos['descricao'] ?>"><br><br>

    Carga Horária: <input type="number" name="carga_horaria" value="<?= $cursos['carga_horaria'] ?>"><br><br>

    <button type="submit">Atualizar</button>
    <a href="professor.php">Voltar</a>
</form>