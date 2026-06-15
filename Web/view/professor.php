<?php
session_start();
require_once "../db/database.php";
require_once "../controller/UsuarioController.php";
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

$usuarioController = new UsuarioController($pdo);
$usuario = $usuarioController->buscarUsuario($_SESSION['id']);

// Contadores rápidos para o Dashboard Hero
$totalCursos = is_array($cursos) ? count($cursos) : 0;
$totalAlunos = is_array($trazer) ? count($trazer) : 0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/PaginaProfessor.css">
    <title>Painel do Instrutor - Lunex</title>
</head>

<body>
    <nav>
        <div style="display: flex; align-items: center; gap: 10px;">
            <h1>Lunex</h1>
            <span class="badge-role">Painel do Instrutor</span>
        </div>
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="professor.php">Início</a>
            <a href="editarusuario.php" class="profile-container">
                <?php if (!empty($usuario['foto_perfil'])) : ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($usuario['foto_perfil']) ?>" alt="Foto de Perfil">
                <?php else : ?>
                    <div class="profile-pic-placeholder">
                        <?= strtoupper(substr($usuario['nome'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <p><?= htmlspecialchars($usuario['nome']) ?></p>
            </a>
            <a href="logout.php" class="logout-btn">Sair</a>
        </div>
    </nav>

    <section class="hero-section">
        <div class="hero-content">
            <span class="hero-badge">💼 Central de Gestão</span>
            <h1 class="hero-title">
                Bem-vindo, Professor <br><span class="highlight"><?= htmlspecialchars($usuario['nome']); ?></span>!
            </h1>
            <p class="hero-subtitle">Gerencie suas turmas, planeje novas grades de ensino e acompanhe a evolução de seus alunos na Lunex.</p>
            
            <div class="hero-quick-actions">
                <a href="cadastrarCurso.php" class="btn-dash-primary">✨ Novo Curso</a>
                <a href="CadastroModulo.php" class="btn-dash-secondary">📂 Novo Módulo</a>
            </div>
        </div>

        <div class="hero-stats">
            <div class="stat-card">
                <div class="stat-icon">🎓</div>
                <div class="stat-data">
                    <span class="stat-number"><?= $totalCursos; ?></span>
                    <span class="stat-label">Cursos Ativos</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-data">
                    <span class="stat-number"><?= $totalAlunos; ?></span>
                    <span class="stat-label">Alunos Matriculados</span>
                </div>
            </div>
            <div class="glow-orb"></div>
        </div>
    </section>

    <h3>Seus Cursos:</h3>
    <ul>
        <?php if (empty($cursos)): ?>
            <li class="empty-list">Nenhum curso cadastrado ainda.</li>
        <?php else: ?>
            <?php foreach ($cursos as $curso): ?>
                <?php if (!$curso['ativo']) continue; ?>
                <li>
                    <div>
                        <strong><?= htmlspecialchars($curso['nome']) ?></strong> 
                        <span style="color: var(--placeholder); margin: 0 8px;">|</span> 
                        <?= htmlspecialchars($curso['descricao']) ?>
                    </div>
                    <div>
                        <span class="badge-hours"><?= htmlspecialchars($curso['carga_horaria']) ?>h</span>
                        <a href="editarcurso.php?id_curso=<?= $curso['id'] ?>">Editar</a>
                        <a href="deletarcurso.php?id_curso=<?= $curso['id'] ?>" style="color: #ef4444;" onclick="return confirm('Tem certeza que deseja deletar este curso?')">Deletar</a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <h3>Matriculados:</h3>
    <ul>
        <?php if (empty($trazer)): ?>
            <li class="empty-list">Nenhum aluno matriculado ainda.</li>
        <?php else: ?>
            <?php foreach ($trazer as $matriculado): ?>
                <?php if (!$matriculado['matricula_ativa']) continue; ?>
                <li>
                    <div>
                        <span style="color: var(--blue); font-weight: 500;">👤 Aluno:</span> <?= htmlspecialchars($matriculado['aluno']) ?> 
                        <span style="color: var(--placeholder); margin: 0 8px;">|</span> 
                        <span style="color: var(--purple); font-weight: 500;">📚 Curso:</span> <?= htmlspecialchars($matriculado['curso']) ?>
                    </div>
                    <span style="font-size: 0.85rem; color: var(--placeholder);">
                        📅 <?= date('d/m/Y', strtotime($matriculado['data_matricula'])) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <h3>Seus Módulos por Curso:</h3>
    <?php if (empty($modulos)): ?>
        <ul>
            <li class="empty-list">Nenhum módulo cadastrado ainda.</li>
        </ul>
    <?php else: ?>
        <?php
        $modulosPorCurso = [];
        foreach ($modulos as $modulo) {
            if (!$modulo['ativo']) continue;
            $cursoNome = $modulo['curso_nome'] ?? ('Curso #' . ($modulo['cursos_id'] ?? ''));
            if (!isset($modulosPorCurso[$cursoNome])) {
                $modulosPorCurso[$cursoNome] = [];
            }
            $modulosPorCurso[$cursoNome][] = $modulo;
        }

        foreach ($modulosPorCurso as $cursoNome => $listaModulos):
        ?>
            <h4>📁 <?= htmlspecialchars($cursoNome) ?></h4>
            <ul>
                <?php foreach ($listaModulos as $modulo): 
                    $video = $modulo['video'] ?? '';
                    if (!empty($video) && !preg_match('/^https?:\/\//', $video)) {
                        $video = "https://" . $video;
                    }
                    $video = preg_replace('#^https:/([^/])#', 'https://$1', $video);
                ?>
                    <li>
                        <div>
                            <span style="color: var(--placeholder); margin-right: 8px;">ID: <?= (int)$modulo['id'] ?></span>
                            <strong><?= htmlspecialchars($modulo['titulo']) ?></strong>
                        </div>
                        <div>
                            <a href="<?= htmlspecialchars($video) ?>" target="_blank" class="btn-watch">Assistir Vídeo</a>
                            <a href="deletarmodulo.php?id_modulo=<?= (int)$modulo['id'] ?>" style="color: #ef4444;" onclick="return confirm('Tem certeza que deseja deletar este módulo?')">Deletar</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>

</body>
</html>