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
        $fotoperfil = file_get_contents($_FILES['foto_perfil']['tmp_name']);
    }

    $resultado = $UsuarioController->cadastrar($nome, $email, $senha, $cargo, $fotoperfil);

    if ($resultado === "duplicado") {
        echo "Email já cadastrado. Por favor, use outro email.";
    } else {
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar-se</title>
    <link rel="stylesheet" href="../CSS/style2.css">
</head>

<body>

    <form method="post" class="card-form" enctype="multipart/form-data">

        <div class="logo">
            <img src="../IMG/logo-sem-fundo.png">
        </div>

        <h2 class="form-title"> Cadastrar-se </h2>

        <!-- PRIMEIRA TELA -->
        <section class="form-screen" id="inicio" >

            <label>Nome</label>
            <input type="text" name="nome" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Senha</label>
            <input type="password" name="senha" required>

            <label>Foto de Perfil</label>
            <input type="file" name="foto_perfil" accept="image/*">

            <!-- abre segunda tela -->
            <a href="#cargo" class="btn-primary">
                Continuar
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
    </script>
</body>

</html>