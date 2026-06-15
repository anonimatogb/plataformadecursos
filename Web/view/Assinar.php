<?php

if (empty($_GET['email'])) {
    header("Location: cadastrarusuario.php");
    exit();
}

$email = $_GET['email'];

$finalar = isset($_GET['finalizar']);


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/Assinar.css">
    <title>Assinatura</title>

    <style>
        .pagamento-box {
            display: none;

            margin-top: 20px;
            padding: 20px;

            border: 1px solid #ccc;
        }
    </style>

</head>

<body>

    <div class='container'>

        <h1>
            Bem-vindo,
            <?php echo htmlspecialchars($email); ?>!
        </h1>

        <p>
            Por favor, faça o pagamento para concluir seu cadastro.
        </p>

        <h2>Apenas R$ 55,00</h2>

        <h3>Escolha sua forma de pagamento:</h3>

        <select name="pagamento" id="pagamento">

            <option value="">Selecione</option>

            <option value="cartao">
                Cartão
            </option>

            <option value="pix">
                PIX
            </option>

        </select>

        <!-- PIX -->
        <div id="pix-box" class="pagamento-box">

            <h3>Pagamento via PIX</h3>

            <img src="../IMG/qr-code.png" width="250">

            <p>
                Escaneie o QR Code para pagar ou faça o pagamento para o email:
            </p>
            <h2><strong>Zucolin@gmail.com</strong></h2>


            <button onclick="finalizar()">
                Finalizar Pagamento
            </button>

        </div>

        <!-- CARTÃO -->
        <div id="cartao-box" class="pagamento-box">

            <h3>Pagamento via Cartão</h3>

            <input type="text" placeholder="Número do cartão" required>
            <br><br>

            <input type="text" placeholder="Nome no cartão" required>
            <br><br>

            <input type="text" placeholder="CVV" required>
            <br><br>

            <input type="text" placeholder="Validade (MM/AA)" required>
            <br><br>

            <button onclick="finalizar()">
                Finalizar Pagamento
            </button>

            
        </div>

    </div>

    <script>
        const select = document.getElementById('pagamento');

        const pixBox = document.getElementById('pix-box');

        const cartaoBox = document.getElementById('cartao-box');

        select.addEventListener('change', function() {

            // Esconde tudo
            pixBox.style.display = 'none';
            cartaoBox.style.display = 'none';

            // Mostra conforme seleção
            if (this.value === 'pix') {

                pixBox.style.display = 'block';

            }

            if (this.value === 'cartao') {

                cartaoBox.style.display = 'block';

            }

        });

        function finalizar() {

            alert("Pagamento realizado com sucesso!" + "\n" + "Faça login para acessar seus cursos.");

            window.location.href = "index.php";

        }
    </script>

</body>

</html>
