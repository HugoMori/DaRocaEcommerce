<?php
// Campo que fez requisição
$campo = $_GET['campo'];
// Valor do campo que fez requisição
$valor = $_GET['valor'];
// Verificando o campo login
if ($campo == "login") {
    if (strlen($valor) < 4) {
        echo "O login deve ter no minímo 4 caracteres";
    } elseif (!preg_match('/^[a-z\d_]{4,100}$/i', $valor)) {
        echo "O login deve conter somente letras e numeros.";
    }
}
// Verificando o campo email
if ($campo == "email") {
    if (!eregi("^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$", $valor)) {
        echo "Preencha com um email válido"; //
    }
}
// Verificando o campo CPF
if ($campo == "cpf") {
    if (!eregi("^([0-9]){3}.([0-9]){3}.([0-9]){3}-([0-9]){2}$", $valor)) {
        echo "Digite um CPF válido";
    }
}
// Acentuação
header("Content-Type: text/html; charset=ISO-8859-1",true);
?>