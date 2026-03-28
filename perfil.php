<?php
session_start();
include("config.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_SESSION['id'];

// SALVAR DADOS (quando clicar em salvar)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sqlUpdate = "UPDATE usuario SET 
        email = '$email',
        telefone = '$telefone'
        WHERE id_usuario = $id";

    $mysqli->query($sqlUpdate);

    header("Location: perfil.php");
    exit();
}

// BUSCAR DADOS
$sql = "SELECT * FROM usuario WHERE id_usuario = $id";
$result = $mysqli->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil - VagaLivre</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    margin:0;
    font-family: 'Montserrat', sans-serif;
    background: linear-gradient(135deg,#2b5876,#4e4376);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.card {
    background:#fff;
    padding:30px;
    border-radius:20px;
    width:340px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    position: relative;
}

.back {
    position:absolute;
    left:15px;
    top:15px;
    font-size:18px;
    color:#555;
    cursor:pointer;
}

.avatar {
    width:90px;
    height:90px;
    background:#2ecc71;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:35px;
    color:#fff;
    margin:10px auto 15px;
}

h2 { margin:10px 0 5px; }

.info {
    text-align:left;
    margin-top:20px;
}

.info p {
    margin:10px 0;
    font-size:14px;
    display:flex;
    align-items:center;
    gap:10px;
}

input {
    width:100%;
    padding:8px;
    border-radius:8px;
    border:1px solid #ccc;
}

button {
    margin-top:10px;
    padding:12px;
    width:100%;
    border:none;
    border-radius:10px;
    background:#4e4376;
    color:#fff;
    font-weight:bold;
    cursor:pointer;
}

.botoes{
    display:flex;
    gap:10px;
}

.botoes button{
    width:50%;
}

.btn-excluir{
    background:#e74c3c;
}
</style>
</head>

<body>

<div class="card">

    <div class="back" onclick="voltar()">
        <i class="fas fa-arrow-left"></i>
    </div>

    <div class="avatar">
        <i class="fas fa-user"></i>
    </div>

    <h2><?php echo $user['nome']; ?></h2>

    <form method="POST">

        <div class="info">

            <!-- EMAIL -->
            <p>
                <i class="fas fa-envelope"></i>

                <span id="emailText"><?php echo $user['email']; ?></span>

                <input 
                    type="text" 
                    name="email"
                    id="emailInput" 
                    value="<?php echo $user['email']; ?>" 
                    style="display:none;"
                >
            </p>

            <!-- TELEFONE -->
            <p>
                <i class="fas fa-phone"></i>

                <span id="telText"><?php echo $user['telefone']; ?></span>

                <input 
                    type="text" 
                    name="telefone"
                    id="telInput" 
                    value="<?php echo $user['telefone']; ?>" 
                    style="display:none;"
                >
            </p>

            <p>
                <i class="fas fa-id-card"></i> ID: <?php echo $user['id_usuario']; ?>
            </p>

        </div>

       <!-- BOTÕES -->
    <button type="button" id="btnEditar" onclick="editar()">
    Editar Perfil
    </button>

    <div class="botoes" id="grupoBotoes" style="display:none;">

        <button type="submit" id="btnSalvar">
            Salvar
        </button>

        <button 
            type="submit"
            name="excluir"
            class="btn-excluir"
            onclick="return confirm('Deseja realmente excluir sua conta?')">
            Excluir
        </button>

</div>

<script>

function editar(){

    // esconder textos
    document.getElementById("emailText").style.display = "none";
    document.getElementById("telText").style.display = "none";

    // mostrar inputs
    document.getElementById("emailInput").style.display = "block";
    document.getElementById("telInput").style.display = "block";

    // trocar botões
    document.getElementById("btnEditar").style.display = "none";
    document.getElementById("btnSalvar").style.display = "inline-block";
    document.getElementById("btnExcluir").style.display = "inline-block";
}

</script>

</body>
</html>