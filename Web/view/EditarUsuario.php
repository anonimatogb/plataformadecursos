<?php
session_start();
require_once "../db/database.php";
require_once "../controller/UsuarioController.php";

if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] !== 'professor' && $_SESSION['cargo'] !== 'aluno' && $_SESSION['cargo'] !== 'admin')) {
    header("Location: Index.php");
    exit;
}

$usuarioController = new UsuarioController($pdo);

$usuarios = $usuarioController->buscarusuario($_SESSION['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mantém a imagem atual por padrão
    $foto_perfil = $usuarios['foto_perfil'];

    // Se enviou nova imagem
    if (
        isset($_FILES['foto_perfil']) &&
        $_FILES['foto_perfil']['error'] == 0
    ) {

        $tipo = mime_content_type($_FILES['foto_perfil']['tmp_name']);

        if (str_contains($tipo, 'image')) {

            $foto_perfil = file_get_contents(
                $_FILES['foto_perfil']['tmp_name']
            );
        }
    }

    $senha = $usuarios['senha'];

    if (!empty($_POST['senha'])) {
        $senha = $_POST['senha'];
    }

    $usuarios_atualizado = $usuarioController->atualizar(
        $_SESSION['id'],
        $_POST['nome'],
        $_POST['email'],
        $senha,
        $foto_perfil
    );

    if (!$usuarios_atualizado) {
        echo "Erro ao atualizar usuário.";
        exit;
    }

    header(
        "Location: " .
            ($_SESSION['cargo'] === 'professor'
                ? 'professor.php'
                : 'paginainicial.php')
    );

    exit;
}

// LÓGICA DO AVATAR ATUAL (Base64 para o preview de fundo)
$avatarStyle = '';
$hasAvatar = false;

if (!empty($usuarios['foto_perfil'])) {
    $hasAvatar = true;
    $base64 = base64_encode($usuarios['foto_perfil']);
    $avatarStyle = "background-image: url('data:image/jpeg;base64,{$base64}'); border-style: solid; border-color: var(--purple);";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/EditarUsuarios.css">
    <title>Editar Usuário</title>
</head>
<body>

    <div class="card-form">
        
        <h2 class="form-title" style="margin-bottom: 10px;">Editar Perfil</h2>

        <form method="POST" enctype="multipart/form-data">
            
            <div class="avatar-container">
                <label class="avatar-label">Foto de Perfil</label>
                <label for="foto_perfil" class="avatar-wrapper">
                    <div class="avatar-preview" id="avatarPreview" style="<?= $avatarStyle ?>">
                        <?php if (!$hasAvatar): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="camera-icon"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path><circle cx="12" cy="13" r="3"></circle></svg>
                        <?php endif; ?>
                    </div>
                </label>
                <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*" onchange="previewImagem(event)">
            </div>

            <div class="input-group">
                <label>Nome</label>
                <input type="text" name="nome" value="<?= htmlspecialchars($usuarios['nome']) ?>" required>
            </div>

            <div class="input-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuarios['email']) ?>" required>
            </div>

            <div class="input-group">
                <label>Senha (deixe em branco para não alterar)</label>
                <input type="password" name="senha" placeholder="••••••••">
            </div>

            <div class="action-buttons">
                <button type="submit">Atualizar</button>
                <a href="<?= $_SESSION['cargo'] === 'professor' ? 'professor.php' : 'paginainicial.php' ?>">
                    Voltar
                </a>
            </div>

        </form>
    </div>

    <script>
        function previewImagem(event) {
            const input = event.target;
            const preview = document.getElementById('avatarPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.style.backgroundImage = `url('${e.target.result}')`;
                    preview.innerHTML = '';
                    preview.style.borderStyle = 'solid';
                    preview.style.borderColor = 'var(--purple)';
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>