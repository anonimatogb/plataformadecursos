<?php
session_start();
require_once "../DB/DataBase.php";
require_once "../Controller/CursosController.php";

if  (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit();
}
$cursosController = new CursosController($pdo);

$id = $_GET['id'];
$curso = $cursosController->obterCursoPorId($id);

if (!$curso) {
    die("Arquivo não encontrado");
}

header("Content-Type: image/jpeg");
header("Content-Disposition: attachment; filename=certificado.jpg");

echo $curso['certificado'];

