<?php
session_start();

require_once "../Controller/moduloController.php";
require_once "../db/database.php";

if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {
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
$resultado = $moduloController->deletar($idModulo);

if ($resultado) {
    header("Location: professor.php");
    exit;
}

echo "Erro ao deletar módulo. Por favor, tente novamente.";

