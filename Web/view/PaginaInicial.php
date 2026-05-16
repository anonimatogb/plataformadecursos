<?php
session_start();
require_once "../Controller/UsuarioController.php";
require_once "../DB/DataBase.php";

if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit();
}

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
</body>
</html>