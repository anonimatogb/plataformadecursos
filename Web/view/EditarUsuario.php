<?php
session_start();
require_once "../db/database.php";
require_once "../controller/UsuarioController.php";

if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] !== 'professor' && $_SESSION['cargo'] !== 'aluno' && $_SESSION['cargo'] !== 'admin')) {
    header("Location: Index.php");
    exit;
}

$usuarioController = new UsuarioController($pdo);

$usuarios = $usuarioController->buscarusuario($_SESSION['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mantém a imagem atual por padrão
    $foto_perfil = $usuarios['foto_perfil'];

    // Se enviou nova imagem
    if (
        isset($_FILES['foto_perfil']) &&
        $_FILES['foto_perfil']['error'] == 0
    ) {

        $tipo = mime_content_type($_FILES['foto_perfil']['tmp_name']);

        if (str_contains($tipo, 'image')) {

            $foto_perfil = file_get_contents(
                $_FILES['foto_perfil']['tmp_name']
            );
        }
    }

    $senha = $usuarios['senha'];

if (!empty($_POST['senha'])) {
    $senha = $_POST['senha'];
}

    $usuarios = $usuarioController->atualizar(
        $_SESSION['id'],
        $_POST['nome'],
        $_POST['email'],
        $senha,
        $foto_perfil
    );

    if (!$usuarios) {

        echo "Erro ao atualizar usuário.";
        exit;
    }

    header(
        "Location: " .
            ($_SESSION['cargo'] === 'professor'
                ? 'professor.php'
                : 'paginainicial.php')
    );

    exit;
}

?>

<head>
    <title>Editar Usuário</title>
</head>




<form method="POST" enctype="multipart/form-data">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($usuarios['nome']) ?>"><br><br>

    Email: <input type="email" name="email" value="<?= htmlspecialchars($usuarios['email']) ?>"><br><br>

    Senha: <input type="password" name="senha" ><br><br>

    <label for="fotocapa">Trocar Foto Perfil (opcional):</label>
    <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*"><br><br>

    <button type="submit">Atualizar</button>
    <a href="<?= $_SESSION['cargo'] === 'professor'
    ? 'professor.php'
    : 'paginainicial.php' ?>">
    Voltar
</a>
</form>