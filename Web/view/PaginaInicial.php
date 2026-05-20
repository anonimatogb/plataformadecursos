<?php
session_start();
require_once "../Controller/UsuarioController.php";
require_once "../Controller/CursosController.php";
require_once "../Controller/MatriculaController.php";
require_once "../DB/DataBase.php";

if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit();
}

$cursosController = new CursosController($pdo);
$cursos = $cursosController->todos();

$matriculaController = new MatriculaController($pdo);
$sei = $matriculaController->confe($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
</head>

<body>
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Esta é a página inicial para alunos.</p>
    <a href="logout.php">Sair</a>

    <h2>Cursos:</h2>
    <ul>
        <?php foreach ($cursos as $curso) : ?>
            <li>
                <strong><?php echo $curso['nome']; ?></strong><br>
             <?php
if (in_array($curso['id'], array_column($sei, 'cursos_id'))) {
    echo "<em>Matriculado</em>";
} else {
    echo "<a href='Matricular.php?curso_id=" . $curso['id'] . "&professor_id=" . $curso['professor'] . "'>Matricular-se</a>";
}


?>
            </li>
        <?php endforeach; ?>

      

    </ul>

</body>

</html>