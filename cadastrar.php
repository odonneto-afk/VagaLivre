<?php header("Content-Type: text/html; charset=UTF-8",true);?><?php
include("config.php");
$errado = false;
$info = false;
$criarSenha = false;
$login = "";
$senha = "";
ob_start();
@session_start();


if (!empty($_POST['email']))
    $login=stripslashes(trim($_POST['email']));
if (empty($_POST['senha']))
    $senha = "vazio";
else
    $senha=sha1(md5(trim($_POST['senha'])));

$aux = 0;
$admin = false;
$aluno = false;
$contcursos = 0;
$achou = false;
$senha_atual = "vazio";
$erro = false;
$senhaConfere = false;
$manutencao = false;
$periodo = false;


ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);



if (!empty($_POST['email']))
{
    $achou=false;
    $sql="SELECT * FROM usuario WHERE email='".$login."'";
    $campos = $mysqli->query($sql);
    while($obj = $campos->fetch_object())
        $achou = true;
    if (!$achou)
    {
        $nome=$_POST['nome'];
        $email=$_POST['email'];
        $senha=sha1(md5($_POST['senha']));
        $confsenha=sha1(md5($_POST['confsenha']));
        $whatsapp=$_POST['whatsapp'];

        if ($senha!=$confsenha)
            die();

        $sql="INSERT INTO usuario (nome,email,senha,status,whatsapp) VALUES ('".$nome."','".$email."','".$senha."','ATIVO','".$whatsapp."')";
        $result=$mysqli->query($sql);
        if ($result === TRUE)
            $last_id = $conn->insert_id;


        echo mysql_error();

        $autorizado = true;
        $lifetime_in_seconds = 10; // 3 horas
        ini_set('session.gc_maxlifetime', 10); 
        // session_set_cookie_params($lifetime_in_seconds);
        // setcookie(session_name(), session_id(), time() + $lifetime_in_seconds);

        ob_start();
        session_start();
        $_SESSION['start_time'] = time();
        $_SESSION['expiry_time'] = time()+$lifetime_in_seconds;

        $_SESSION['login'] = $login;
        $_SESSION['senha'] = $senha;
        $_SESSION['id'] = $last_id;
        $_SESSION['idcliente_login'] = $last_id;



    }
}
header('Location: ./');
?>