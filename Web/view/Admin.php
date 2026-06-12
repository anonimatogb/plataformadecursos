    <?php
    session_start();
    require_once "../db/database.php";
    require_once "../controller/UsuarioController.php";
    require_once "../controller/CursosController.php";
    require_once "../controller/MatriculaController.php";
    require_once "../controller/ModuloController.php";


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
        <link rel="stylesheet" href="../CSS/Admin.css">
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
    if($user['cargo'] !== 'admin' ){
    echo "<a href='trans.php?id_curso=" . $user['id'] . "'>Admin</a>";
    }
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
        width='100'> | Nome: " . $curso['nome'] . " | Descrição: " . $curso['descricao'] . " | Carga Horária: " . $curso['carga_horaria'] . " horas | Professor ID: " . $curso['professor'] . "</li><a href='editarcurso.php?id_curso=" . $curso['id'] . "'>Editar</a>" . "<a href='deletarcurso.php?id_curso=" . $curso['id'] . "'>Desativar</a>";
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
        $modulosPorCurso = [];
        foreach ($modulos as $modulo) {
            if (!$modulo['ativo']) {
                continue; // Pula módulos inativos
            }
         $https = ("https://") ;
    $video = $modulo['video'] ?? '';
    if (!preg_match('/^https?:\/\//', $video)) {
        $video = $https . $video;
    }

        $video = preg_replace('#^https:/([^/])#', 'https://$1', $video);
    echo "<li>ID: {$modulo['id']} | Nome: {$modulo['titulo']} | Curso ID: {$modulo['cursos_id']}</li>
    <a href=\"{$video}\" target=\"_blank\">Assistir Vídeo</a>

    <a href='deletarmodulo.php?id_modulo={$modulo['id']}'>Desativar</a>";

        }


        echo "</ul>";
        echo "<h2>Módulos Inativos:</h2><ul>";
        foreach ($modulos as $modulo) {
            if ($modulo['ativo']) {
                continue; // Pula módulos ativos
            }
            echo "<li>ID: " . $modulo['id'] . " | Nome: " . $modulo['titulo'] . " | Curso ID: " . $modulo['cursos_id'] . "</li>";
        }
        echo "</ul>";
    }



        ?>


        


    </body>

    </html>