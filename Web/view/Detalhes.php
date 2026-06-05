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

$matriculaController = new MatriculaController($pdo);
$cursosController = new CursosController($pdo);
$curso = $cursosController->tra($_GET['curso_id']);
$sei = $matriculaController->confe($_SESSION['id']);



$quantidade=1;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes</title>
</head>

<body>
    <nav>
        <h1>Lunex</h1>
        <a href="PaginaInicial.php">Início</a>
        <a href="meus.php">Meus cursos</a>
        <p><?php echo $_SESSION['nome']; ?></p>
        <a href="logout.php">Sair</a>
    </nav>
    <div>
        <a href="paginainicial.php">Voltar</a>
        <h2><?php echo $curso['nome']; ?></h2>
        <img src="data:image/jpeg;base64,<?= base64_encode($curso['fotocapa']) ?>" alt="<?php echo $curso['nome']; ?>" width="100">
        <h4>Professor: <?php echo $curso['professor_nome'] ?></h4>

            <h3>Descriçao do Curso</h3>
            <p><?php echo $curso['descricao']; ?></p>
            <h3>Conteúdo do Curso</h3>
            <p>Carga Horária: <?php echo $curso['carga_horaria'];
                                if ($curso['carga_horaria'] == 1) {
                                    echo " hora";
                                } else {
                                    echo " horas";
                                }
                                ?> </p>
            <?php
            if (in_array($curso['id'], array_column($sei, 'cursos_id'))) {
                echo "<a href='meus.php'>Acessar curso</a>";
            } else {
                echo "<a href='Matricular.php?curso_id=" . $_GET['curso_id'] . "&professor_id=" . $_GET['professor_id'] . "'>Inscrever-se</a>";
            }

            ?>

    </div>

</body>

</html>