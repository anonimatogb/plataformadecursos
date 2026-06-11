<?php
session_start();

require_once "../controller/ModuloController.php";
require_once "../db/database.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor' && $_SESSION['cargo'] !== 'admin') {
    header('Location: Index.php');
    exit();
}

if (!isset($_GET['id_modulo'])) {
    echo "<script>
        alert('ID do módulo não fornecido!');
    </script>";
    header("Location: professor.php");
    exit;
}

$idModulo = (int)$_GET['id_modulo'];

$moduloController = new ModuloController($pdo);
$resultado = $moduloController->desativar($idModulo);

if ($resultado && $_SESSION['cargo'] == 'professor') {
    header("Location: professor.php");
    exit;
}if($resultado && $_SESSION['cargo'] == 'admin'){
    header("Location: admin.php");
    exit;
} else {
    echo "Erro ao deletar módulo. Por favor, tente novamente.";
}
