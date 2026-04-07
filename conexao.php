<?php
$database = "u574397572_vagalivre";
$username = "u574397572_vagalivre";
$password = "5>YoFssoy>O";

error_reporting(1);
ob_start();
$mysqli = new mysqli("193.203.175.53",$username,$password,$database);

// Check connection
if ($mysqli -> connect_errno) {
  // echo "Erro na conexao com BD: " . $mysqli -> connect_error;
  echo "Erro na conexao com BD.";
//   exit();
}

$empresacliente="VagaLivre";

?>