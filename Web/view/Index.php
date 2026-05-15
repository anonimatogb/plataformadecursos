<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<form method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required><br><br>

    <input type="submit" value="Login">
    <a href="CadastrarUsuario.php">Não tem uma conta? Cadastre-se aqui.</a>
</body>
</html>
<?php
require_once "../Controller/UsuarioController.php";
require_once "../DB/DataBase.php";

$UsuarioController = new UsuarioController($pdo);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    
    $resultado = $UsuarioController->login($email, $senha);
    if ($resultado) {
        if ($resultado['cargo'] === 'aluno') {
            header("Location: incial.php");
            exit();
        } elseif ($resultado['cargo'] === 'professor') {
            header("Location: admin.php");
            exit();
        } else {
            echo "Cargo desconhecido. Por favor, contate o suporte.";
        }
    } else {
        echo "Email ou senha incorretos. Por favor, tente novamente.";
    }
    }