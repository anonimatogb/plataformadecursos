<?php
session_start();
require_once "../db/database.php";
require_once "../Controller/CursosController.php";
require_once "../Controller/MatriculaController.php";
require_once "../Controller/moduloController.php";
if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'aluno') {
    header('Location: Index.php');
    exit();
}
$cursosController = new CursosController($pdo);
$cursos = $cursosController->todosaluno($_SESSION['id']);




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Cursos</title>
</head>
<body>

    <h1>Meus Cursos</h1>
    <a href="PaginaInicial.php">Voltar para a Página Inicial</a>
    <ul>
        <?php foreach ($cursos as $curso): ?>
            <li>
                <?php echo $curso['nome'] . " | " . $curso['descricao'] . " | " . $curso['carga_horaria'] . " horas"; ?>
            </li>
        <?php endforeach; 
        if (empty($cursos)) {
            echo "Nenhum curso matriculado ainda.";
        }
        ?>
    </ul>

   
</body>
</html>