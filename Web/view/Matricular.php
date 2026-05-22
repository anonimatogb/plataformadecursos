<?php
try{
session_start();

require_once "../Controller/MatriculaController.php";
require_once "../DB/DataBase.php";


$matriculaController = new MatriculaController($pdo);

if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit();
}

if (!isset($_GET['curso_id'])) {
    echo "<script>
    alert('ID do curso não fornecido!');
    </script>";
    header("Location: PaginaInicial.php");
    exit;
}

$aluno_id=$_SESSION['id'];
$cursos_id=$_GET['curso_id'];   
$professor_id=$_GET['professor_id'];

$resultado = $matriculaController->matricular($aluno_id, $cursos_id, $professor_id);

if ($resultado) {

    header("Location: detalhes.php?curso_id=" . $_GET['curso_id'] . "&professor_id=" . $_GET['professor_id']);
    exit;
} else {
    echo "<script>
    alert('ERRO ao realizar matrícula!');
    </script>";
    header("Location: PaginaInicial.php");
    exit;
}

}catch(Exception $e){
    echo "Erro: " . $e->getMessage();
}