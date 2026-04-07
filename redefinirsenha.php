<?php
include("config.php");

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $mysqli->real_escape_string($_POST['email']);
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    if ($nova_senha !== $confirmar_senha) {
        $mensagem = "As senhas não coincidem!";
    } else {
        // Verifica se o e-mail existe
        $sql_busca = "SELECT id_usuario FROM usuario WHERE email = '$email'";
        $res = $mysqli->query($sql_busca);

        if ($res->num_rows > 0) {
            // APLICANDO A CRIPTOGRAFIA PADRÃO DO SEU SISTEMA
            $senha_cripto = sha1(md5(trim($nova_senha)));
            
            $sqlUpdate = "UPDATE usuario SET senha = '$senha_cripto' WHERE email = '$email'";
            
            if ($mysqli->query($sqlUpdate)) {
                // REDIRECIONA PARA O LOGIN COM AVISO DE SUCESSO
                header("Location: login.php?sucesso=senha_alterada");
                exit();
            }
        } else {
            $mensagem = "E-mail não encontrado.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VagaLivre - Redefinir Senha</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1a1a2e 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: white;
            width: 90%;
            max-width: 400px;
            padding: 40px;
            border-radius: 25px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .logo {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            font-size: 26px;
            font-weight: 800;
            color: #1A2B4D;
            margin-bottom: 5px;
        }

        .logo i { color: #1A2B4D; }
        .logo span { color: #2DAB61; }

        h2 {
            font-size: 18px;
            color: #333;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 20px;
            border: none;
            background-color: #f1f3f5;
            border-radius: 12px;
            font-size: 14px;
            color: #495057;
            outline: none;
        }

        .btn-entrar {
            width: 60%;
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 25px;
            background: linear-gradient(to right, #2c3e50, #000000);
            color: white;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-entrar:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 15px;
        }

        /* Botão para voltar se desistir de mudar a senha */
        .btn-voltar {
            display: block;
            margin-top: 20px;
            color: #777;
            text-decoration: none;
            font-size: 12px;
        }
    </style>
</head>
<body>

    <div class="card">
        <div class="logo">
            <i class="fa-solid fa-car-side"></i> Vaga<span>Livre</span>
        </div>
        
        <h2>Redefinir senha</h2>

        <?php if($mensagem != ""): ?>
            <div class="error-msg"><?php echo $mensagem; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="email" name="email" placeholder="Seu e-mail cadastrado" required>
            </div>

            <div class="form-group">
                <input type="password" name="nova_senha" placeholder="Nova senha" required>
            </div>

            <div class="form-group">
                <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required>
            </div>

            <button type="submit" class="btn-entrar">REDEFINIR</button>
        </form>

        <a href="login.php" class="btn-voltar">Voltar para o login</a>
    </div>

</body>
</html>