<?php
$database = "u574397572_vagalivre";
$username = "u574397572_vagalivre";
$password = "fff";

error_reporting(1);
ob_start();
$mysqli = new mysqli("localhost",$username,$password,$database);

// Check connection
if ($mysqli -> connect_errno) {
  // echo "Erro na conexao com BD: " . $mysqli -> connect_error;
  echo "Erro na conexao com BD.";
//   exit();
}

$empresacliente="VagaLivre";

?>
