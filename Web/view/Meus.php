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
    <link rel="stylesheet" href="../CSS/MeusCursos.css">
    <title>Meus Cursos</title>
</head>

<body>

    <div class="acoes-topo">
    <a href="PaginaInicial.php" class="btn-topo btn-voltar">
        ← Página Inicial
    </a>

    <a href="logout.php" class="btn-topo btn-sair">
        Sair
    </a>
</div>

<h1>Meus Cursos</h1>
    <ul>
        <?php
        foreach ($cursos as $curso): ?>
            
            <li>
                <img 
    src="data:image/jpeg;base64,<?= base64_encode($curso['fotocapa']) ?>"
    alt="<?= $curso['nome'] ?>"
    width="100">
                <?php echo $curso['nome'] . " | " . $curso['descricao'] . " | " . ($curso['concluido'] == 0 ? "Em andamento" : "Concluído") . " | " . ($curso['ativo'] == 0 ? " Curso Desativado" : "<a href='continuar.php?id=" . $curso['id'] . "'>Continuar</a>"); ?>
            </li>
        <?php endforeach;
        if (empty($cursos)) {
            echo "Nenhum curso matriculado ainda.";
        }
        ?>
    </ul>


</body>

</html>