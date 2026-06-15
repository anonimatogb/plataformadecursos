<?php
session_start();
require_once "../controller/UsuarioController.php";
require_once "../controller/CursosController.php";
require_once "../controller/MatriculaController.php";
require_once "../db/database.php";

if (!isset($_SESSION['cargo']) || $_SESSION['cargo'] !== 'aluno') {
    header("Location: Index.php");
    exit();
}

$cursosController = new CursosController($pdo);
$cursos = $cursosController->todos();

$matriculaController = new MatriculaController($pdo);
$sei = $matriculaController->confe($_SESSION['id']);

$usuarioController = new UsuarioController($pdo);
$usuario = $usuarioController->buscarUsuario($_SESSION['id']);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/PaginaInicial.css">
    <title>Início - Lunex</title>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1>Lunex</h1>
            <span class="badge-role">Área do Aluno</span>
        </div>
        
        <div class="nav-right">
            <a href="paginainicial.php">Início</a>
            <a href="meus.php">Meus cursos</a>
            
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
            <span class="hero-badge">🚀 Área de Aprendizado</span>
            <h1 class="hero-title">
                Olá, <span class="highlight"><?= htmlspecialchars($usuario['nome']); ?></span>!<br>
                Pronto para o próximo nível?
            </h1>
            <p class="hero-subtitle">Continue sua jornada de estudos de onde parou ou explore novas habilidades para impulsionar a sua carreira na <strong>Lunex</strong>.</p>
            
            <div class="hero-actions">
                <a href="#cursos" class="btn-primary">Explorar Catálogo</a>
                <a href="meus.php" class="btn-secondary">Meus Cursos</a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="glass-card">
                <div class="glass-icon">💡</div>
                <div class="glass-text">
                    <p class="glass-title">Continue evoluindo</p>
                    <p class="glass-desc">O conhecimento transforma.</p>
                </div>
            </div>
            <div class="glow-orb"></div>
        </div>
    </section>

    <h2 id="cursos">Cursos em Destaque:</h2>
    
    <ul>
        <?php foreach ($cursos as $curso) : ?>
            <?php if (isset($curso['ativo']) && !$curso['ativo']) {
                continue; // Pula módulos inativos
            } ?>
            <li>
                <img src="data:image/jpeg;base64,<?= base64_encode($curso['fotocapa']) ?>" alt="<?= htmlspecialchars($curso['nome']) ?>">
                <strong><?php echo htmlspecialchars($curso['nome']); ?></strong>
                <p><?php echo htmlspecialchars($curso['descricao']); ?></p>
                <a href="detalhes.php?curso_id=<?php echo $curso['id']; ?>&professor_id=<?php echo $curso['professor']; ?>">Detalhes</a>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html> 