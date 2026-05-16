<?php
require_once "../Controller/UsuarioController.php";
require_once "../DB/DataBase.php";


$UsuarioController = new UsuarioController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cargo = $_POST['cargo'];


    $resultado = $UsuarioController->cadastrar($nome, $email, $senha, $cargo);

    if ($resultado === "duplicado") {
        echo "Email já cadastrado. Por favor, use outro email.";
    } else {
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar-se</title>
</head>

<body>
    <h1>Cadastrar-se</h1>
    <form method="post">
        <section id="voltar">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required><br><br>

            <a href="index.php">Já tem uma conta? Faça login aqui.</a>
            <a href="#cargo">Cadastrar-se</a>
        </section>

        <section id="cargo">
            <label for="cargo">Cargo:</label>
            <select id="cargo" name="cargo">
                <option value="aluno">Aluno</option>
                <option value="professor">Professor</option>
            </select>
            <a href="#voltar">voltar</a>
            <input type="submit" value="Cadastrar">
        </section>

    </form>
</body>

</html>