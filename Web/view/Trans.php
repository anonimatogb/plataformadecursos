<?php
session_start();
require_once "../db/database.php";
require_once "../Controller/usuariocontroller.php";
if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'admin') {
    header("Location: Index.php");
    exit;
}

$usuarioController = new UsuarioController($pdo);

if (!isset($_GET['id_curso'])) {
    echo "<script>
    alert('ID do usuário não fornecido!');
    </script>";
    header("Location: admin.php");
    exit;
}

$resultado = $usuarioController->trans($_GET['id_curso']);
if($resultado){
    header("Location: admin.php");
    exit;
} else {
    echo "Erro ao mudar cargo. Por favor, tente novamente.";
}