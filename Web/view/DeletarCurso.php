<?php
session_start();
require_once "../Controller/cursosController.php";
require_once "../db/database.php";
if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {
    header("Location: Index.php");
    exit;
}

$cursosController = new CursosController($pdo);

if (!isset($_GET['id_curso'])) {
    echo "<script>
    alert('ID do curso não fornecido!');
    </script>";
    header("Location: professor.php");
    exit;
}

$resultado = $cursosController->desativar($_GET['id_curso']);
if ($resultado) {
    header("Location: professor.php");
    exit;
} else {
    echo "Erro ao deletar curso. Por favor, tente novamente.";
}