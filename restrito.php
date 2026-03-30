<?php
@session_start();
// 1. Define o tempo máximo de inatividade (ex: 30 minutos = 1800 segundos)
$tempo_limite = 10; 

// 2. Configura o PHP para aceitar esse tempo antes do Garbage Collector agir
ini_set('session.gc_maxlifetime', $tempo_limite);
session_set_cookie_params($tempo_limite);

session_start();

// 3. Lógica de Expiração Manual (Segurança Extra)
if (isset($_SESSION['ultimo_acesso'])) {
    $inatividade = time() - $_SESSION['ultimo_acesso'];
    
    if ($inatividade > $tempo_limite) {
        session_unset();
        session_destroy();
        header("Location: logout.php"); // Redireciona se exceder
        exit;
    }
}

// 4. ATUALIZA O TEMPO: Sempre que carregar a página, o "relógio" reseta aqui
$_SESSION['ultimo_acesso'] = time();




$session_timeout = $tempo_limite;
if (isset($_SESSION["login"]) && isset($_SESSION["senha"]))
{
	$_SESSION['expiry_time'] =  time() + $session_timeout;

	$login_usuario = $_SESSION["login"];
	$senha_usuario = $_SESSION["senha"];
	$login_usuario_id = $_SESSION["id"];
}
else
{
	header("Location:login.php");
	exit();
}

if ($login_usuario!="")
{
	$bdu = $mysqli->query ("SELECT * FROM usuario");
	while($campo = $bdu->fetch_object())
	{
		if (strcasecmp(strtoupper($login_usuario),strtoupper($campo->email))==0)
		{
			$CONFIG_nome=$nome = $campo->nome;
			$CONFIG_primeiro_nome = explode(' ',ucfirst(strtolower($campo->nome)))[0];
			$senha_atual = $campo->senha;

			if ($senha_usuario!=$campo->senha)
				header("Location:logout.php");
		}
	}
}
?>