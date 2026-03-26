<?php header("Content-Type: text/html; charset=UTF-8",true);
include("conexao.php");

date_default_timezone_set('America/Recife');
$hojecomHora = date('d/m/Y H:i:s');
list($hoje, $horarioAtual) = explode(" ", $hojecomHora);
list($diaatual, $mesatual, $anoatual) = explode("/", $hoje);
$hojeBD = "{$anoatual}-{$mesatual}-{$diaatual}";
$hojeBDcomHora = "{$hojeBD} {$horarioAtual}";

// $bl="25ov234fb646vcn51edlf544g3qcu90iat_45tqrhDjB";
// $bd = mysql_query ("SELECT * from configuracoes");
// while($row = mysql_fetch_array ($bd))
// {
//     if ((sha1(md5($row[$bl[18].$bl[32].$bl[33].$bl[32].$bl[34].$bl[19].$bl[31].$bl[8].$bl[17].$bl[39].$bl[32].$bl[13].$bl[32].$bl[2]])) != $row[$bl[13].$bl[40].$bl[32].$bl[12].$bl[17].$bl[41].$bl[19]])||($row[$bl[18].$bl[32].$bl[33].$bl[32].$bl[34].$bl[19].$bl[31].$bl[8].$bl[17].$bl[39].$bl[32].$bl[13].$bl[32].$bl[2]]<=$hojeBD))
//         header("Location: ".$bl[8].$bl[19].$bl[2].$bl[26].$bl[28].$bl[17].$bl[31].$bl[2].'/?'.rand(154334,8766654));
// }


function pegaUsuarioId3($id)
{
    $bd = mysql_query ("SELECT * from usuarios where (idusuarios = '".$id."')");
    while($dados = mysql_fetch_array ($bd))
    {
        return $dados['nome'];
    }
}

function ConverterDataParaBrasileiroTirandoHora($data_brasileiro) {
    date_default_timezone_set('UTC');
    $diaatual = date('d');
    $mesatual = date('m');
    $anoatual = date('Y');
    $partes_da_data = explode(':',$data_brasileiro);

    $partes_da_data1 = explode('/',$partes_da_data[0]);

    $partes_da_data2 = explode(' ',$partes_da_data1[2]);

    $ano = $partes_da_data1[0];
    $mes = $partes_da_data1[1];
    $dia = $partes_da_data2[0];
    $hora = $partes_da_data2[1];
    $minutos = $partes_da_data[1];
    $segundos = $partes_da_data[2];
    $data = $ano.'-'.$mes.'-'.$dia;
    return $data;
}
function ConverterDataParaAmericanoTirandoHora($data_brasileiro) {
    date_default_timezone_set('UTC');
    $diaatual = date('d');
    $mesatual = date('m');
    $anoatual = date('Y');
    $partes_da_data = explode(':',$data_brasileiro);

    $partes_da_data1 = explode('-',$partes_da_data[0]);

    $partes_da_data2 = explode(' ',$partes_da_data1[2]);

    $ano = $partes_da_data1[0];
    $mes = $partes_da_data1[1];
    $dia = $partes_da_data2[0];
    $hora = $partes_da_data2[1];
    $minutos = $partes_da_data[1];
    $segundos = $partes_da_data[2];
    $data = $ano.'-'.$mes.'-'.$dia;
    return $data;
}

function valorParaReal($valor, $cifrao = TRUE) {
    return ($cifrao ? "R$ " : "").number_format(floatval($valor), 2, ',', '.');
}

function ConverterDataParaBrasileiro($data_americano) {
    return date('d/m/Y', strtotime(str_replace("-", "/", $data_americano)));
}

function ConverterDataParaBrasileiroComHora($data_americano) {
    return ConverterDataParaBrasileiro($data_americano)." ".date("H:i:s");
}

function ConverterDataParaAmericano($data) {
    return date('Y-m-d', strtotime(str_replace("/", "-", $data)));
}

function ConverterDataParaAmericanoComHora($data) {
    return ConverterDataParaAmericano($data)." ".date("H:i:s");
}

function converterValorParaReal($valor) {
    $valor = str_replace(
        [ "R$", " ", ".", ",", "-" ],
        [ "", "", "", ".", "" ],
        $valor
    );
    return floatval($valor);
}

function sksort(&$array, $subkey = "id", $sort_ascending = false) {
    if (count($array))
        $temp_array[key($array)] = array_shift($array);

    foreach (@$array as $key => $val) {
        $offset = 0;
        $found = false;
        foreach ($temp_array as $tmp_key => $tmp_val) {
            if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                $temp_array = array_merge(
                    (array)array_slice($temp_array,0,$offset),
                    array($key => $val),
                    array_slice($temp_array,$offset)
                );
                $found = true;
            }
            $offset++;
        }
        if (!$found) $temp_array = array_merge($temp_array, array($key => $val));
    }
    if ($sort_ascending) $array = array_reverse($temp_array);
    else $array = $temp_array;
}

function pegaFaturamentoPorHora($horaref,$dataIni,$dataFim)
{
    $soma=0;
    $bdA = mysql_query ("SELECT * from vendas WHERE (status = 'ENCERRADA')");
    while($dadosVendas = mysql_fetch_array ($bdA))
    {
        $datavenda=trim(substr($dadosVendas['dataEntrada'], 0, 10));
        $horariovenda=trim(substr($dadosVendas['dataEntrada'], 11, 2));
        if (($datavenda>=$dataIni)&&($datavenda<=$dataFim))
        {
            if ($horariovenda==$horaref)
            {
                $soma+=$dadosVendas['total'];
            }
        }
    }
    return $soma;
}

function pegacliente($id)
{
    $result = mysql_query("SELECT * FROM clientes WHERE (id = '".$id."')");
    while($row = mysql_fetch_array($result))
    {
        $nomeok=$row['nome'];
        if($row['nomefantasia']!=""){$nomeok=$row['nomefantasia'];}
        return array ($nomeok,$row['telefone1']);
    }
    return 0;
}

function pegacategoria($id)
{
    $result = mysql_query("SELECT * FROM categoria WHERE (id = '".$id."')");
    while($row = mysql_fetch_array($result))
    {
        $nomeok=$row['nome'];
        return $nomeok;
    }
    return 0;
}

function pegacomplemento($id)
{
    $result = mysql_query("SELECT * FROM complementos WHERE (id = '".$id."')");
    while($row = mysql_fetch_array($result))
    {
        return array($row['descricao'],$row['valor']);
    }
    return 0;
}

function escreveComZeros($valor,$quantidadezeros=4)
{
    return str_pad($valor, $quantidadezeros, "0", STR_PAD_LEFT);
}