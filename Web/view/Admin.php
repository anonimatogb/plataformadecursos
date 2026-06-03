<?php
session_start();
require_once "../DB/DataBase.php";
require_once "../Controller/UsuarioController.php";
require_once "../Controller/CursosController.php";
require_once "../Controller/MatriculaController.php";


if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'admin') {
    header("Location: Index.php");
    exit();
}

$UsuarioController = new UsuarioController($pdo);
$cursosController = new CursosController($pdo);
$matriculaController = new MatriculaController($pdo);

$users = $UsuarioController->todos();
$cursos = $cursosController->todos();
$trazer = $matriculaController->relacao();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
</head>
<body>
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Esta é a página inicial para administradores.</p>
    <a href="logout.php">Sair</a>

<?php

if (empty($users)) {
    echo "<p>Nenhum usuário cadastrado.</p>";
} else {
    echo "<h2>Usuários Cadastrados:</h2><ul>";
    foreach ($users as $user) {
        echo "<li>ID: " . $user['id'] . " | Nome: " . $user['nome'] . " | Email: " . $user['email'] . " | Senha: " . $user['senha'] . " | Cargo: " . $user['cargo'] . "</li>";
    }
    echo "</ul>";
}

if (empty($cursos)) {
    echo "<p>Nenhum curso cadastrado.</p>";
} else {
    echo "<h2>Cursos Cadastrados:</h2><ul>";
    foreach ($cursos as $curso) {
        echo "<li>ID: " . $curso['id'] . " | Nome: " . $curso['nome'] . " | Descrição: " . $curso['descricao'] . " | Carga Horária: " . $curso['carga_horaria'] . " horas | Professor ID: " . $curso['professor'] . "</li>";
    }
    echo "</ul>";
}

    if (empty($trazer)) {
            echo "<p>Nenhum aluno matriculado ainda.</p>";
        }else{
            echo "<h2>Relação de Matrículas:</h2><ul>";
       foreach ($trazer as $matriculado) {
            echo "<li>" . "Aluno: " . $matriculado['aluno'] . " | Curso: " . $matriculado['curso'] . " | Data de Matrícula: " . date('d/m/Y', strtotime($matriculado['data_matricula'])) . "</li>";
        }
        echo "</ul>";
        }

?>



</body>
</html>