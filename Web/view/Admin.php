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
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Admin.css">
    <title>ADMIN</title>
</head>

<body>

    <div class="admin-card">
        
        <div class="admin-header">
            <h1>Bem-vindo, <?php echo $_SESSION['nome']; ?>!</h1>
            <p>Esta é a página inicial para administradores.</p>
            <a href="logout.php" class="logout-link">Sair</a>
        </div>

        <div class="admin-section">
            <h2 class="section-title">Usuários Cadastrados</h2>
            
            <?php if (empty($users)): ?>
                <p class="empty-message">Nenhum usuário cadastrado.</p>
            <?php else: ?>
                <ul class="data-list">
                    <?php foreach ($users as $user): ?>
                        <li class="data-item">
                            <?php if(!empty($user['foto_perfil'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($user['foto_perfil']) ?>" alt="<?= $user['nome'] ?>">
                            <?php endif; ?>
                            
                            <div class="data-content">
                                <span class="data-field"><strong>ID:</strong> <?= $user['id'] ?></span>
                                <span class="data-field"><strong>Nome:</strong> <?= $user['nome'] ?></span>
                                <span class="data-field"><strong>Email:</strong> <?= $user['email'] ?></span>
                                <span class="data-field"><strong>Cargo:</strong> <?= $user['cargo'] ?></span>
                            </div>

                            <div class="data-actions">
                                <?php if($user['cargo'] !== 'admin'): ?>
                                    <a href="trans.php?id_curso=<?= $user['id'] ?>" class="action-admin">Tornar Admin</a>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <?php if (empty($cursos)): ?>
                <h2 class="section-title">Cursos</h2>
                <p class="empty-message">Nenhum curso cadastrado.</p>
            <?php else: ?>
                
                <h2 class="section-title">Cursos Ativos</h2>
                <ul class="data-list">
                    <?php foreach ($cursos as $curso): 
                        if (!$curso['ativo']) continue; 
                    ?>
                        <li class="data-item">
                            <?php if(!empty($curso['fotocapa'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($curso['fotocapa']) ?>" alt="<?= $curso['nome'] ?>">
                            <?php endif; ?>
                            
                            <div class="data-content">
                                <span class="data-field"><strong>ID:</strong> <?= $curso['id'] ?></span>
                                <span class="data-field"><strong>Nome:</strong> <?= $curso['nome'] ?></span>
                                <span class="data-field"><strong>Carga Horária:</strong> <?= $curso['carga_horaria'] ?>h</span>
                                <span class="data-field"><strong>Professor ID:</strong> <?= $curso['professor'] ?></span>
                            </div>

                            <div class="data-actions">
                                <a href="editarcurso.php?id_curso=<?= $curso['id'] ?>" class="action-edit">Editar</a>
                                <a href="deletarcurso.php?id_curso=<?= $curso['id'] ?>" class="action-disable">Desativar</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h2 class="section-title" style="margin-top: 24px;">Cursos Desativados</h2>
                <ul class="data-list">
                    <?php foreach ($cursos as $curso): 
                        if ($curso['ativo']) continue; 
                    ?>
                        <li class="data-item inactive">
                            <?php if(!empty($curso['fotocapa'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($curso['fotocapa']) ?>" alt="<?= $curso['nome'] ?>">
                            <?php endif; ?>
                            
                            <div class="data-content">
                                <span class="data-field"><strong>ID:</strong> <?= $curso['id'] ?></span>
                                <span class="data-field"><strong>Nome:</strong> <?= $curso['nome'] ?></span>
                                <span class="data-field"><strong>Carga Horária:</strong> <?= $curso['carga_horaria'] ?>h</span>
                                <span class="data-field"><strong>Professor ID:</strong> <?= $curso['professor'] ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <?php if (empty($trazer)): ?>
                <h2 class="section-title">Matrículas</h2>
                <p class="empty-message">Nenhuma matrícula registrada.</p>
            <?php else: ?>
                
                <h2 class="section-title">Matrículas Ativas</h2>
                <ul class="data-list">
                    <?php foreach ($trazer as $matriculado): 
                        if (!$matriculado['ativo']) continue; 
                    ?>
                        <li class="data-item">
                            <div class="data-content">
                                <span class="data-field"><strong>Aluno ID:</strong> <?= $matriculado['aluno'] ?></span>
                                <span class="data-field"><strong>Curso ID:</strong> <?= $matriculado['curso'] ?></span>
                                <span class="data-field"><strong>Data:</strong> <?= date('d/m/Y', strtotime($matriculado['data_matricula'])) ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h2 class="section-title" style="margin-top: 24px;">Matrículas Inativas</h2>
                <ul class="data-list">
                    <?php foreach ($trazer as $matriculado): 
                        if ($matriculado['ativo']) continue; 
                    ?>
                        <li class="data-item inactive">
                            <div class="data-content">
                                <span class="data-field"><strong>Aluno ID:</strong> <?= $matriculado['aluno'] ?></span>
                                <span class="data-field"><strong>Curso ID:</strong> <?= $matriculado['curso'] ?></span>
                                <span class="data-field"><strong>Data:</strong> <?= date('d/m/Y', strtotime($matriculado['data_matricula'])) ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <?php if (empty($modulos)): ?>
                <h2 class="section-title">Módulos</h2>
                <p class="empty-message">Nenhum módulo cadastrado.</p>
            <?php else: ?>
                
                <h2 class="section-title">Módulos Ativos</h2>
                <ul class="data-list">
                    <?php foreach ($modulos as $modulo): 
                        if (!$modulo['ativo']) continue; 

                        $https = "https://";
                        $video = $modulo['video'] ?? '';
                        if (!preg_match('/^https?:\/\//', $video)) {
                            $video = $https . $video;
                        }
                        $video = preg_replace('#^https:/([^/])#', 'https://$1', $video);
                    ?>
                        <li class="data-item">
                            <div class="data-content">
                                <span class="data-field"><strong>ID:</strong> <?= $modulo['id'] ?></span>
                                <span class="data-field"><strong>Nome:</strong> <?= $modulo['titulo'] ?></span>
                                <span class="data-field"><strong>Curso ID:</strong> <?= $modulo['cursos_id'] ?></span>
                            </div>

                            <div class="data-actions">
                                <a href="<?= $video ?>" target="_blank" class="action-edit">Assistir Vídeo</a>
                                <a href="deletarmodulo.php?id_modulo=<?= $modulo['id'] ?>" class="action-disable">Desativar</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h2 class="section-title" style="margin-top: 24px;">Módulos Inativos</h2>
                <ul class="data-list">
                    <?php foreach ($modulos as $modulo): 
                        if ($modulo['ativo']) continue; 
                    ?>
                        <li class="data-item inactive">
                            <div class="data-content">
                                <span class="data-field"><strong>ID:</strong> <?= $modulo['id'] ?></span>
                                <span class="data-field"><strong>Nome:</strong> <?= $modulo['titulo'] ?></span>
                                <span class="data-field"><strong>Curso ID:</strong> <?= $modulo['cursos_id'] ?></span>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>