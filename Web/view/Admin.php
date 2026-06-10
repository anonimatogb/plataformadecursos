<?php
session_start();
require_once "../DB/DataBase.php";
require_once "../Controller/UsuarioController.php";
require_once "../Controller/CursosController.php";
require_once "../Controller/MatriculaController.php";
require_once "../Controller/ModuloController.php";


if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'admin') {
    header("Location: Index.php");
    exit();
}

$UsuarioController = new UsuarioController($pdo);
$cursosController = new CursosController($pdo);
$matriculaController = new MatriculaController($pdo);
$moduloController = new ModuloController($pdo);

$users = $UsuarioController->todos();
$cursos = $cursosController->todos();
$trazer = $matriculaController->relacao();
$modulos = $moduloController->todos();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
</head>

<body>
    <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Esta é a página inicial para administradores.</p>
    <a href="logout.php">Sair</a>

    <?php

    if (empty($users)) {
        echo "<p>Nenhum usuário cadastrado.</p>";
    } else {
        echo "<h2>Usuários Cadastrados:</h2><ul>";
        foreach ($users as $user) {
            echo "<li>ID: " . $user['id'] . " | <img 
    src='data:image/jpeg;base64," . base64_encode($user['foto_perfil']) . "'
    alt='" . $user['nome'] . "'
     width='100'> | Nome: " . $user['nome'] . " | Email: " . $user['email'] . " | Senha: " . $user['senha'] . " | Cargo: " . $user['cargo'] . "</li>";
        }
        echo "</ul>";
    }


    if (empty($cursos)) {
        echo "<p>Nenhum curso cadastrado.</p>";
    } else {
        echo "<h2>Cursos Ativos:</h2><ul>";
        foreach ($cursos as $curso) {
            if (!$curso['ativo']) {
                continue; // Pula cursos inativos
            }
            echo "<li>ID: " . $curso['id'] . " | <img 
    src='data:image/jpeg;base64," . base64_encode($curso['fotocapa']) . "'
    alt='" . $curso['nome'] . "'
     width='100'> | Nome: " . $curso['nome'] . " | Descrição: " . $curso['descricao'] . " | Carga Horária: " . $curso['carga_horaria'] . " horas | Professor ID: " . $curso['professor'] . "</li><a href='editarcurso.php?id_curso=" . $curso['id'] . "'>Editar</a>" . "<a href='deletarcurso.php?id_curso=" . $curso['id'] . "'>Deletar</a>";
        }
        echo "</ul>";
    

        echo "<h2>Cursos Desativados:</h2><ul>";
        foreach ($cursos as $curso) {
            if ($curso['ativo']) {
                continue; // Pula cursos inativos
            }
            echo "<li>ID: " . $curso['id'] . " | <img 
    src='data:image/jpeg;base64," . base64_encode($curso['fotocapa']) . "'
    alt='" . $curso['nome'] . "'
     width='100'> | Nome: " . $curso['nome'] . " | Descrição: " . $curso['descricao'] . " | Carga Horária: " . $curso['carga_horaria'] . " horas | Professor ID: " . $curso['professor'] . "</li>";
        }
        echo "</ul>";
    
    }

if (empty($trazer)) {
    echo "<p>Nenhuma matrícula ativa.</p>";
} else {
    echo "<h2>Matrículas Ativas:</h2><ul>";
    foreach ($trazer as $matriculado) {
        if (!$matriculado['ativo']) {
            continue; // Pula matrículas inativas
        }
        echo "<li>Aluno: " . $matriculado['aluno'] . " | Curso: " . $matriculado['curso'] . " | Data de Matrícula: " . date('d/m/Y', strtotime($matriculado['data_matricula'])) . "</li>";
    }
    echo "</ul>";
    echo "<h2>Matrículas Inativas:</h2><ul>";
    foreach ($trazer as $matriculado) {
        if ($matriculado['ativo']) {
            continue; // Pula matrículas ativas
        }
        echo "<li>Aluno: " . $matriculado['aluno'] . " | Curso: " . $matriculado['curso'] . " | Data de Matrícula: " . date('d/m/Y', strtotime($matriculado['data_matricula'])) . "</li>";
    }
    echo "</ul>";
}

if (empty($modulos)) {
    echo "<p>Nenhum módulo cadastrado.</p>";
} else {
    echo "<h2>Módulos Ativos:</h2><ul>";
    foreach ($modulos as $modulo) {
        if (!$modulo['ativo']) {
            continue; // Pula módulos inativos
        }
        echo "<li>ID: " . $modulo['id'] . " | Nome: " . $modulo['nome'] . " | Descrição: " . $modulo['descricao'] . " | Curso ID: " . $modulo['cursos_id'] . "</li>";
    }
    echo "</ul>";
    echo "<h2>Módulos Inativos:</h2><ul>";
    foreach ($modulos as $modulo) {
        if ($modulo['ativo']) {
            continue; // Pula módulos ativos
        }
        echo "<li>ID: " . $modulo['id'] . " | Nome: " . $modulo['nome'] . " | Descrição: " . $modulo['descricao'] . " | Curso ID: " . $modulo['cursos_id'] . "</li>";
    }
    echo "</ul>";
}




       ?>


    


</body>

</html>