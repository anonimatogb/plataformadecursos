<?php
session_start();
require_once "../Controller/UsuarioController.php";
require_once "../DB/DataBase.php";

$UsuarioController = new UsuarioController($pdo);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $resultado = $UsuarioController->login($email, $senha);

    if ($resultado) {
        $_SESSION['cargo'] = $resultado['cargo'];
        $_SESSION['nome'] = $resultado['nome'];
        $_SESSION['id'] = $resultado['id'];
        if ($resultado['cargo'] === 'aluno') {
            header("Location: Paginainicial.php");
            exit();
        } elseif ($resultado['cargo'] === 'professor') {
            header("Location: professor.php");
            exit();
        } elseif ($resultado['cargo'] === 'admin') {
            header("Location: admin.php");
            exit();
        } else {
            echo "Cargo desconhecido. Por favor, contate o suporte.";
        }
    } else {
        echo "Email ou senha incorretos. Por favor, tente novamente.";
    }
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/style2.css">
</head>

<body>

    <form method="post" class="card-form">
        <div class="logo">
    <img src="../IMG/logo-sem-fundo.png" alt="Logo Lunex">
</div>

        <label for="email">Email:</label class="input-text">
        <input type="email" id="email" name="email" required>
        <label for="senha">Senha:</label class="input-text">
        <input type="password" id="senha" name="senha" required>

        <input type="submit" value="Login" class="btn-primary">
        <a href="CadastrarUsuario.php">Não tem uma conta? Cadastre-se aqui.</a>
</body>

</html>