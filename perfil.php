<?php
session_start();
include("config.php");
include("restrito.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') 
    if (!isset($_SESSION['id'])) {
        header("Location: index.php");
        exit();
    }

$id = $login_usuario_id;

// --- SALVAR ALTERAÇÕES ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar'])) {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $telefone = $mysqli->real_escape_string($_POST['telefone']);

    $sqlUpdate = "UPDATE usuario SET nome = '$nome', email = '$email', telefone = '$telefone' WHERE id_usuario = $id";
    $mysqli->query($sqlUpdate);
    header("Location: perfil.php?atualizado=1");
    exit();
}

// ---  EXCLUIR PERFIL ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['excluir'])) {
    $sqlDelete = "DELETE FROM usuario WHERE id_usuario = $id";
    if ($mysqli->query($sqlDelete)) {
       
        header("Location: logout.php");
        exit();
    }
}

// --- BUSCAR DADOS PARA EXIBIR ---
$sql = "SELECT * FROM usuario WHERE id_usuario = $id";
$result = $mysqli->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VagaLivre - Dados Pessoais</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
        }

        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 40px;
            background: white;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 22px;
            font-weight: 700;
            color: #1A2B4D; /* Azul escuro do ícone */
        }

        .logo span { color: #2DAB61; } /* Verde do 'Livre' */

        .user-nav-icon {
            color: #2DAB61;
            font-size: 26px;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 40px;
        }

        .back-container {
            width: 100%;
            max-width: 600px;
            margin-bottom: 15px;
        }

        .back-link {
            text-decoration: none;
            color: #2DAB61;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        
        .card {
            background: white;
            width: 90%;
            max-width: 580px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            text-align: center;
        }

        .card-header { margin-bottom: 25px; }
        .card-header i { font-size: 55px; color: #000; margin-bottom: 10px; }
        .card-header h1 { font-size: 26px; margin: 0; font-weight: 700; }

       
        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #888;
            margin-bottom: 5px;
            padding-left: 2px;
        }

        .form-group input {
            width: 100%;
            padding: 13px 15px;
            border: none;
            background-color: #E9ECEF; /* Cinza dos inputs do Figma */
            border-radius: 10px;
            font-size: 14px;
            box-sizing: border-box;
            color: #495057;
        }

        
        .readonly-input {
            cursor: not-allowed;
            color: #999 !important;
        }

        /* Botões */
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
        }

        .btn {
            padding: 10px 35px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: 0.2s;
        }

        .btn-excluir {
            background: transparent;
            border: 1.5px solid #FF6B6B;
            color: #FF6B6B;
        }

        .btn-salvar {
            background: #D1E7DD; /* Verde claro do botão Salvar */
            color: #0F5132;
            padding: 10px 50px;
        }

        .btn:hover { opacity: 0.8; }

    </style>
</head>
<body>

<header class="navbar">
    <div class="logo">
        <i class="fa-solid fa-car-side"></i> Vaga<span>Livre</span>
    </div>
    <div class="user-nav-icon">
        <i class="fa-solid fa-circle-user"></i>
    </div>
</header>

<div class="content">
    <div class="back-container">
        <a href="./" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
    </div>

    <section class="card">
        <div class="card-header">
            <i class="fa-solid fa-circle-user"></i>
            <h1>Dados Pessoais</h1>
        </div>

        <form method="POST" action="perfil.php?id=<php echo $login_usuario_id ?>">
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required>
            </div>

            <div class="form-group">
                <label>Telefone</label>
                <input type="text" name="telefone" value="<?php echo htmlspecialchars($user['telefone']); ?>" required>
            </div>

            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" value="********" readonly class="readonly-input">
            </div>

            <div class="actions">
                <button type="submit" name="excluir" class="btn btn-excluir" onclick="return confirm('Tem certeza que deseja excluir seu perfil?')">
                    Excluir perfil
                </button>
                <button type="submit" name="salvar" class="btn btn-salvar">
                    Salvar
                </button>
            </div>
        </form>
    </section>
</div>

</body>
</html>