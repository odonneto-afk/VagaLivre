<?php
$database = "u574397572_vagalivre";
$username = "u574397572_vagalivre";
$password = "5>YoFssoy>O";
$host = "193.203.175.53";

error_reporting(1);
ob_start();
$mysqli = new mysqli($host,$username,$password,$database);

// Check connection
if ($mysqli -> connect_errno) {
  // echo "Erro na conexao com BD: " . $mysqli -> connect_error;
  echo "Erro na conexao com BD.";
//   exit();
}

$empresacliente="VagaLivre";

?>