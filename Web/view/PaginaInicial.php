<?php
session_start();
require_once "../controller/UsuarioController.php";
require_once "../controller/CursosController.php";
require_once "../controller/MatriculaController.php";
require_once "../db/database.php";

if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit();
}

$cursosController = new CursosController($pdo);
$cursos = $cursosController->todos();

$matriculaController = new MatriculaController($pdo);
$sei = $matriculaController->confe($_SESSION['id']);

$usuarioController = new UsuarioController($pdo);
$usuario = $usuarioController->buscarUsuario($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/PaginaInicial.css">
    <title>Início</title>
</head>

<body>
    <nav>
        <h1>Lunex</h1>
        <a href="paginainicial.php">Início</a>
        <a href="meus.php">Meus cursos</a>
          <a href="editarusuario.php"><img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_perfil']) ?>" alt="Foto de Perfil" width="100"></a>
    <p><?php echo htmlspecialchars($usuario['nome']) ?></p>
    <div class="acoes-topo">
    <a href="logout.php">Sair</a>
    </nav>
    <h1>Bem-vindo, <?php echo $usuario['nome']; ?>!</h1>
    <p>Esta é a página inicial para alunos.</p>


    <h2>Cursos:</h2>
    <ul>
        <?php foreach ($cursos as $curso) : ?>
             <?php  if (!$curso['ativo']) {
                continue; // Pula módulos inativos
            } ?>
            <li>
<img 
    src="data:image/jpeg;base64,<?= base64_encode($curso['fotocapa']) ?>"
    alt="<?= $curso['nome'] ?>"
    width="100">
                <strong><?php echo $curso['nome']; ?></strong><br>
                <?php echo $curso['descricao']; ?><br>
                <a href="detalhes.php?curso_id=<?php echo $curso['id']; ?>&professor_id=<?php echo $curso['professor']; ?>">Detalhes</a>
            </li>
        <?php endforeach; ?>


    </ul>

</body>

</html>