<?php
session_start();
require_once "../db/database.php";
require_once "../controller/CursosController.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor' && $_SESSION['cargo'] !== 'admin') {
    header("Location: Index.php");
    exit;
}

$cursosController = new CursosController($pdo);

$cursos = $cursosController->buscarum($_GET['id_curso']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fotocapa = null;
    $certificado = null;

    // FOTO CAPA
    if (isset($_FILES['fotocapa']) && $_FILES['fotocapa']['error'] == 0) {

        $tipo = mime_content_type($_FILES['fotocapa']['tmp_name']);

        if (str_contains($tipo, 'image')) {
            $fotocapa = file_get_contents($_FILES['fotocapa']['tmp_name']);
        }
    }

    // CERTIFICADO
    if (isset($_FILES['certificado']) && $_FILES['certificado']['error'] == 0) {

        $tipo = mime_content_type($_FILES['certificado']['tmp_name']);

        if (str_contains($tipo, 'image')) {
            $certificado = file_get_contents($_FILES['certificado']['tmp_name']);
        }
    }

    $cursoAtualizado = $cursosController->atualizar(
        $_GET['id_curso'],
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['carga_horaria'],
        $fotocapa,
        $certificado
    );

    if ($cursoAtualizado) {

        header("Location: professor.php");
        exit;

    } else {

        echo "Erro ao atualizar curso.";

    }
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
    <link rel="stylesheet" href="../css/EditarCursos.css">
    <title>Editar Curso</title>
</head>

<link rel="stylesheet" href="css/style2.css">



<form method="POST" enctype="multipart/form-data">
    Nome: <input type="text" name="nome" value="<?= $cursos['nome'] ?>"><br><br>

    Descrição: <input type="text" name="descricao" value="<?= $cursos['descricao'] ?>"><br><br>

    Carga Horária: <input type="number" name="carga_horaria" value="<?= $cursos['carga_horaria'] ?>"><br><br>

    <label for="fotocapa">Trocar Foto Capa do Curso (opcional):</label>
    <input type="file" id="fotocapa" name="fotocapa" accept="image/*"><br><br>

    <label for="certificado">Trocar Certificado do Curso (opcional):</label>
    <input type="file" id="certificado" name="certificado" accept="image/*"><br><br>

    <button type="submit">Atualizar</button>
    <a href="professor.php">Voltar</a>
</form>

