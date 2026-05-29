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
    <nav>
        <h1>Lunex</h1>
        <a href="paginainicial.php">Início</a>
        <a href="meus.php">Meus cursos</a>
        <p><?php echo $_SESSION['nome']; ?></p>
        <a href="logout.php">Sair</a>
    </nav>
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Esta é a página inicial para alunos.</p>
    

    <h2>Cursos:</h2>
    <ul>
        <?php foreach ($cursos as $curso) : ?>
            <li>
                <img src="../<?php echo $curso['fotocapa']; ?>" alt="<?php echo $curso['nome']; ?>" width="100">
                <strong><?php echo $curso['nome']; ?></strong><br>
                <?php echo $curso['descricao']; ?><br>
                <a href="detalhes.php?curso_id=<?php echo $curso['id']; ?>&professor_id=<?php echo $curso['professor']; ?>">Detalhes</a>
            </li>
        <?php endforeach; ?>
  

    </ul>

</body>

</html>