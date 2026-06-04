<?php
session_start();
require_once "../Controller/ModuloController.php";
require_once "../Controller/CursosController.php";
require_once "../db/database.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {
    header('Location: Index.php');
    exit();
}

$cursosController = new CursosController($pdo);
$cursos = $cursosController->todosprof($_SESSION['id']);

$ModuloController = new ModuloController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = $ModuloController->cadastrar(
        $_POST['titulo'],
        $_POST['cursos_id'],
        $_POST['video']
    );

    echo $msg;
    if ($msg === "Módulo cadastrado com sucesso") {
        header("Location: professor.php");
        exit();
    } else {
        echo "Erro ao cadastrar módulo. Por favor, tente novamente.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Módulos</title>
</head>
<body>
    <h1>Cadastro de Módulos</h1>

    <form method="post" enctype="multipart/form-data">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="cursos_id">Curso:</label>
        <select id="cursos_id" name="cursos_id" required>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo (int)$curso['id']; ?>"><?php echo htmlspecialchars($curso['nome']); ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="video">Link do Vídeo:</label>
        <input type="text" name="video" id="video" required><br><br>

        <a href="professor.php">Voltar para a Central do Aprendizado</a>
        <input type="submit" value="Cadastrar">
    </form>
</body>
</html>
