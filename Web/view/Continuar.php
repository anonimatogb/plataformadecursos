<?php
session_start();
require_once "../db/database.php";
require_once "../Controller/CursosController.php";
require_once "../Controller/MatriculaController.php";
require_once "../Controller/moduloController.php";
if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'aluno') {
    header('Location: Index.php');
    exit();
}

var_dump($_GET['id']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunex</title>
</head>

<body>






</body>

</html>