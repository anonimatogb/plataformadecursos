<?php
session_start();
require_once "../db/database.php";
require_once "../Controller/CursosController.php";
require_once "../Controller/MatriculaController.php";
require_once "../Controller/moduloController.php";
if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'professor') {

    header('Location: Index.php');
    exit();
}
$cursosController = new CursosController($pdo);
$cursos = $cursosController->todosprof($_SESSION['id']);
$matriculaController = new MatriculaController($pdo);
$trazer = $matriculaController->macho($_SESSION['id']);
$moduloController = new ModuloController($pdo);
$modulos = $moduloController->porprof($_SESSION['id']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Central do Aprendizado</title>
</head>

<body>
    <h1>Central do Aprendizado</h1>
    <h2>Bem-vindo, Professor <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h2>
    <p>Aqui você pode gerenciar seus alunos e criar cursos.</p>
    <a href="logout.php">Sair</a>

    <h3>Seus Cursos:</h3>
    <ul>
        <?php foreach ($cursos as $curso): ?>
            <?php if (!$curso['ativo']) {
                continue; // Pula módulos inativos
            } ?>
            <li><?php echo ($curso['nome']) . " | " . ($curso['descricao']) . " | " . ($curso['carga_horaria']) . " horas" . "<a href='editarcurso.php?id_curso=" . $curso['id'] . "'>Editar</a>" . "<a href='deletarcurso.php?id_curso=" . $curso['id'] . "'>Deletar</a>";  ?></li>
        <?php endforeach;
        if (empty($cursos)) {
            echo "Nenhum curso cadastrado ainda.";
        }
        ?>

        <a href="cadastrarCurso.php">Cadastrar Novo Curso</a>
    </ul>
    <h3>Matriculados</h3>
    <ul>
        <?php
        if (empty($trazer)) {
            echo "Nenhum aluno matriculado ainda.";
        } else {
            foreach ($trazer as $matriculado) {
                if (!$matriculado['ativo']) {
                    continue; // Pula módulos inativos
                }
                echo "<li>" . "Aluno: " . $matriculado['aluno'] . " | Curso: " . $matriculado['curso'] . " | Data de Matrícula: " . date('d/m/Y', strtotime($matriculado['data_matricula'])) . "</li>";
            }
        }
        ?>

    </ul>
    <h3>Seus Módulos:</h3>
    <?php
    if (empty($modulos)) {
        echo "Nenhum módulo cadastrado ainda.";
    } else {
        // $modulos já vem ordenado por: curso_nome (A-Z) e dentro por m.id (menor -> maior)
        $modulosPorCurso = [];
        foreach ($modulos as $modulo) {
            if (!$modulo['ativo']) {
                continue; // Pula módulos inativos
            }
            $cursoNome = $modulo['curso_nome'] ?? ('Curso #' . ($modulo['cursos_id'] ?? ''));
            if (!isset($modulosPorCurso[$cursoNome])) {
                $modulosPorCurso[$cursoNome] = [];
            }
            $modulosPorCurso[$cursoNome][] = $modulo;
        }

        foreach ($modulosPorCurso as $cursoNome => $listaModulos) {

            echo '<h4>' . htmlspecialchars($cursoNome) . '</h4>';
            echo '<ul>';
            foreach ($listaModulos as $modulo) {
                echo "<li>";
                echo htmlspecialchars($modulo['titulo']);
                echo " | <a href=\"" . ($modulo['video']) . "\" target=\"_blank\">Assistir Vídeo</a>";
                echo " | <a href='deletarmodulo.php?id_modulo=" . (int)$modulo['id'] . "'>Deletar</a>";
                echo "</li>";
            }
            echo '</ul>';
        }
    }
    ?>

    <a href="CadastroModulo.php">Cadastrar Novo Módulo</a>
</body>

</html>