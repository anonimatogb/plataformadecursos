<?php
session_start();
require_once "../db/database.php";
require_once "../controller/UsuarioController.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit;
}

$usuarioController = new UsuarioController($pdo);

$usuarios = $usuarioController->buscarusuario($_GET['id_usuario']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   

    $usuarios = $usuarioController->atualizar(
        $_GET['id_usuario'],
        $_POST['nome'],
        $_POST['email'],
        $_POST['senha']
    );
    if ($usuarios) {
        header("Location: aluno.php");
        exit;
    } else {
        echo "Erro ao atualizar usuário. Por favor, tente novamente.";
    }
}

?>
<head>
    <title>Editar Usuário</title>
</head>

<link rel="stylesheet" href="css/style2.css">



<form method="POST" enctype="multipart/form-data">
    Nome: <input type="text" name="nome" value="<?= $usuarios['nome'] ?>"><br><br>

    Email: <input type="email" name="email" value="<?= $usuarios['email'] ?>"><br><br>

    Senha: <input type="password" name="senha"><br><br>

    <label for="fotocapa">Trocar Foto Perfil (opcional):</label>
    <input type="file" id="fotocapa" name="fotocapa" accept="image/*"><br><br>

    <button type="submit">Atualizar</button>
    <a href="professor.php">Voltar</a>
</form>