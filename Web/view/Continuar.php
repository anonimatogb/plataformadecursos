<?php
session_start();
require_once "../db/database.php";
require_once "../controller/CursosController.php";
require_once "../controller/MatriculaController.php";
require_once "../controller/moduloController.php";
require_once "../controller/ModuloController.php";
if (!isset($_SESSION['nome']) || $_SESSION['cargo'] !== 'aluno') {
    header('Location: Index.php');
    exit();
}

$cursoId = (int)($_GET['id'] ?? 0);
$mod = (int)($_GET['mod'] ?? 0);

$moduloController = new ModuloController($pdo);

$modulos = $moduloController->porcurso($cursoId);

// Filtra apenas módulos ativos
$modulosAtivos = [];

foreach ($modulos as $modulo) {

    if (!$modulo['ativo']) {
        continue;
    }

    $modulosAtivos[] = $modulo;
}

$moduloAtual = $modulosAtivos[$mod] ?? null;


$matriculaController = new MatriculaController($pdo);
$cursosController = new CursosController($pdo);
$cursoAtual = $cursosController->tra($cursoId);

if ($moduloAtual !== null) {
    $videoUrl = $moduloAtual['video'] ?? '';
    $embed = str_replace("watch?v=", "embed/", $videoUrl);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunex</title>
</head>

<body>
    <nav>
        <h1>Lunex</h1>
        <a href="paginainicial.php">Início</a>
        <a href="meus.php">Meus cursos</a>
        <p><?php echo htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
        <a href="logout.php">Sair</a>
    </nav>

    <main>
        <?php if ($moduloAtual === null): 
            // Ao concluir (quando não há mais módulos), marca matricula.concluido = 1
            $matr = $matriculaController->confe((int)($_SESSION['id'] ?? 0));
            foreach ($matr as $m) {
                if ((int)($m['cursos_id'] ?? 0) === (int)$cursoId) {
                    $matriculaController->concluirCurso((int)($m['id'] ?? 0));
                    break;
                }
            }
        ?>
            <h2>Curso concluído</h2>
            <p>Você finalizou todos os módulos deste curso.</p>

            <?php
                $certificado = $cursoAtual['certificado'] ?? null;
                if (!empty($certificado)):
            ?>
                <p>

<a href="download.php?id=<?= $cursoAtual['id'] ?>">
    Baixar Certificado
</a>
                </p>
            <?php endif; ?>

            <a href="meus.php">Voltar para meus cursos</a>


        <?php else: ?>
            <h2><?php echo htmlspecialchars($moduloAtual['titulo'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h2>

            <iframe width="560" height="315" src="<?= $embed ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

            <div style="margin-top: 16px;">

<?php if ($mod > 0): ?>

    <a href="continuar.php?id=<?php echo (int)$cursoId; ?>&mod=<?php echo (int)($mod - 1); ?>">
        <button type="button">Voltar</button>
    </a>

<?php endif; ?>

<a href="continuar.php?id=<?php echo (int)$cursoId; ?>&mod=<?php echo (int)($mod + 1); ?>">
    <button type="button">Continuar</button>
</a>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>
