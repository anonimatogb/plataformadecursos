<?php
session_start();
require_once "../db/database.php";
require_once "../controller/CursosController.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {
    header("Location: Index.php");
    exit;
}

$cursosController = new CursosController($pdo);

$cursos = $cursosController->buscarum($_GET['id_curso']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fotocapaPath = null;

    if (!empty($_FILES['fotocapa']['name'])) {
        $erro = $_FILES['fotocapa']['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($erro === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['fotocapa']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid('capa_', true) . ($ext ? '.' . $ext : '');

            $destDir = __DIR__ . '/../uploads/cursos/';
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }

            $destFullPath = $destDir . $nomeArquivo;

            if (move_uploaded_file($_FILES['fotocapa']['tmp_name'], $destFullPath)) {
                $fotocapaPath = 'uploads/cursos/' . $nomeArquivo;
            }
        }
    }

    $cursos = $cursosController->atualizar(
        $_GET['id_curso'],
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['carga_horaria'],
        $fotocapaPath
    );
}
if(!isset($_GET['id_curso'])){
    echo "<script>
    alert('ID do curso não fornecido!');
    </script>";
    header("Location: professor.php");
    exit;

}

?>
<head>
    <title>Editar Curso</title>
</head>

<link rel="stylesheet" href="css/style2.css">



<form method="POST" enctype="multipart/form-data">
    Nome: <input type="text" name="nome" value="<?= $cursos['nome'] ?>"><br><br>

    Descrição: <input type="text" name="descricao" value="<?= $cursos['descricao'] ?>"><br><br>

    Carga Horária: <input type="number" name="carga_horaria" value="<?= $cursos['carga_horaria'] ?>"><br><br>

    <label for="fotocapa">Trocar Foto Capa do Curso (opcional):</label>
    <input type="file" id="fotocapa" name="fotocapa" accept="image/*"><br><br>

    <button type="submit">Atualizar</button>
    <a href="professor.php">Voltar</a>
</form>