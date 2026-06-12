<?php
require_once "../Controller/UsuarioController.php";
require_once "../DB/DataBase.php";


$UsuarioController = new UsuarioController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $cargo = $_POST['cargo'];
    $fotoperfil = null;

  if (!empty($_FILES['foto_perfil']['tmp_name'])) {

    $tamanhoMaximo = 2 * 1024 * 1024; // 2MB

    if ($_FILES['foto_perfil']['size'] > $tamanhoMaximo) {
        echo "<script>
                alert('A imagem é muito grande! Tamanho máximo permitido: 2MB.');
                window.location.href = 'CadastrarUsuario.php';
              </script>";
        exit();
    }

    $fotoperfil = file_get_contents($_FILES['foto_perfil']['tmp_name']);
}

    $resultado = $UsuarioController->cadastrar($nome, $email, $senha, $cargo, $fotoperfil);

     if ($resultado === "duplicado") {
        echo "<script>
                alert('Email já cadastrado. Por favor, use outro email.');
                window.location.href = 'CadastrarUsuario.php';
              </script>";
    }
    if ($cargo === "professor") {

        echo "
<style>
    .se{
    position: fixed;
    top: 0;
    left: 0;

    width: 100%;
    height: 100vh;

    background: rgba(0,0,0,0.6);

    display: flex;
    justify-content: center;
    align-items: center;

    z-index: 9999;
}

.cndb{
    background:
        linear-gradient(
            135deg,
            var(--bg-primary),
            var(--bg-secondary),
            var(--bg-third)
        );

    padding: 30px;

    border-radius: 12px;

    width: 350px;

    display: flex;
    flex-direction: column;
    gap: 15px;

}
    .cndb label{
        font-weight: bold;
color: #ffffff;
    }

    .cndb input{
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        color: #000000;
        font-size: 16px;
    }

        </style>

<section class='se'>

    <section class='cndb' id='cndb'>

        <label>Número da CNDB</label>

        <input
            type='text'
            name='cndb'
            placeholder='Número da CNDB'>

        <a href='index.php' class='btn-primary'>
            Concluir Registro
        </a>

    </section>

</section>

";
    } else {
        if($resultado === "duplicado") {
            echo "<script>
                    alert('Email já cadastrado. Por favor, use outro email.');
                    window.location.href = 'CadastrarUsuario.php';
                  </script>";
            exit();
        }else{
            header("Location: assinar.php?email=" . urlencode($email));
            exit();
            
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar-se</title>
    <link rel="stylesheet" href="../CSS/Login.css">
</head>

<body>

    <form method="post" class="card-form" enctype="multipart/form-data">

        <div class="logo">
            <img src="../IMG/logo-sem-fundo.png">
        </div>

        <h2 class="form-title"> Cadastrar-se </h2>

        <!-- PRIMEIRA TELA -->
        <section class="form-screen" id="inicio">

            <label>Nome</label>
            <input type="text" name="nome" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <div class="avatar-container">
    <label class="avatar-label">Foto de Perfil</label>
    <label for="foto_perfil" class="avatar-wrapper">
        <div class="avatar-preview" id="avatarPreview">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="camera-icon"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path><circle cx="12" cy="13" r="3"></circle></svg>
        </div>
    </label>
    <input type="file" name="foto_perfil" id="foto_perfil" accept="image/*" onchange="previewImagem(event)">
</div>

            <!-- abre segunda tela -->
            <a href="#cargo" class="btn-primary">
                Continuar
            </a>
             <a href="index.php">
                Possui uma conta ? Faça login
            </a>

        </section>

        <!-- SEGUNDA TELA -->
        <section class="cargo-screen" id="cargo">

            <div class="cargo-header">

                <h2>Escolha seu perfil</h2>

                <p>
                    Selecione como deseja usar a plataforma
                </p>

            </div>

            <div class="cargo-options">

                <!-- BOTÃO ALUNO -->
                <button
                    type="submit"
                    name="cargo"
                    value="aluno"
                    class="cargo-card">

                    <div class="cargo-icon">
                        <img src="../IMG/aluno.png" alt="Aluno">
                    </div>

                    <h3>Aluno</h3>

                    <p>
                        Acessar cursos e conteúdos
                    </p>

                </button>

                <!-- BOTÃO PROFESSOR -->
                <button
                    type="submit"
                    name="cargo"
                    value="professor"
                    class="cargo-card">

                    <div class="cargo-icon">
                        <img src="../IMG/professor.png" alt="Professor">
                    </div>


                    <h3>Professor</h3>

                    <p>
                        Criar aulas e gerenciar cursos
                    </p>

                </button>

            </div>

            <a href="#" class="voltar">
                Voltar
            </a>

        </section>

    </form>

    <script>
    function selecionarCargo(cargo) {
        document.getElementById("cargoInput").value = cargo;
        window.location.href = "#inicio";
    }

    function previewImagem(event) {
        const input = event.target;
        const preview = document.getElementById('avatarPreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Define a foto selecionada como plano de fundo do círculo
                preview.style.backgroundImage = `url('${e.target.result}')`;
                // Limpa o ícone da câmera de dentro do círculo
                preview.innerHTML = '';
                // Muda a borda tracejada para uma linha sólida elegante
                preview.style.borderStyle = 'solid';
                preview.style.borderColor = 'var(--purple)';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>

</html>