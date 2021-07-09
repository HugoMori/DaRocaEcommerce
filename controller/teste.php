<?php 

    $nome_user = filter_input(INPUT_POST, 'name_user', FILTER_SANITIZE_STRING);
    $endereco_user = filter_input(INPUT_POST, 'endereco_user', FILTER_SANITIZE_STRING);
    $cidade_User = filter_input(INPUT_POST, 'cidade_User', FILTER_SANITIZE_STRING);
    $estadoUser = filter_input(INPUT_POST, 'estadoUser', FILTER_SANITIZE_STRING);
    $cep_User = filter_input(INPUT_POST, 'cep_User', FILTER_SANITIZE_STRING);
    $tel_user = filter_input(INPUT_POST, 'tel_user', FILTER_SANITIZE_STRING);
    $email_user = filter_input(INPUT_POST, 'email_user', FILTER_SANITIZE_EMAIL);
    $senha_user = filter_input(INPUT_POST, 'senha_user', FILTER_SANITIZE_STRING);
    $cpf_user = filter_input(INPUT_POST, 'cpf_User', FILTER_SANITIZE_STRING);
    $file = $_FILES['foto_user'];

 // limpar e verificar se o nome não tem números ou elementos de query ou html
 $nome_user = filter_var($nome_user, FILTER_SANITIZE_STRING);
 $nome_user = preg_replace("/[^a-zA-Z]+/", " ", $nome_user);
 // limpar e verificar se a data contém apenas números
 $data_nasc = preg_replace("([^0-9/-])", "", $_POST['data_nasc']);
 // limpar e verificar cidade
 $cidade_User = filter_var($cidade_User, FILTER_SANITIZE_STRING);
 $cidade_User = preg_replace("/[^a-zA-Z]+/", " ", $cidade_User);
 // limpar e verificar estado
 $estadoUser = filter_var($estadoUser, FILTER_SANITIZE_STRING);
 $estadoUser = preg_replace("/[^a-zA-Z]+/", " ", $estadoUser);
 // limpar e verificar endereco
 $endereco_user = filter_var($endereco_user, FILTER_SANITIZE_STRING);
 $endereco_user = preg_replace("/[^a-zA-Z0-9,.]+/", " ", $endereco_user);
 // limpar e verificar CEP
 $cep_User = filter_var($cep_User, FILTER_SANITIZE_STRING);
 $cep_User = preg_replace("/[^0-9]+/", "", $cep_User);
 $cep_User = substr($cep_User, 0, 5)."-".substr($cep_User, 5);
 // limpar e verificar telefone
 $tel_user = filter_var($tel_user, FILTER_SANITIZE_STRING);
 $tel_user = preg_replace("/[^0-9]+/", "", $tel_user);
 $tel_user = "(".substr($tel_user, 0, 2).")".substr($tel_user, 2, 5)."-".substr($tel_user, 6);
 // limpar e verificar email
 $email_user = filter_var($email_user, FILTER_SANITIZE_EMAIL);
 if (filter_var($email_user, FILTER_VALIDATE_EMAIL) === false) {
     $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar, erro na imagem!</div>";
     //header("Location: ../views/cadastro.php");
 }
 // limpar e verificar a senha
 $senha_user = filter_var($senha_user, FILTER_SANITIZE_STRING);
 // limpar e verificar CPF
 echo "cpf:".$cpf_user."<br>";
 $cpf_user = filter_var($cpf_user, FILTER_SANITIZE_STRING);
 $cpf_user = preg_replace("/[^0-9]+/", "", $cpf_user);
 $cpf_user = substr($cpf_user, 0, 3).".".substr($cpf_user, 3, 3).".".substr($cpf_user, 6, 3)."-".substr($cpf_user, 9);


echo $cpf_user."/". $data_nasc."/".$nome_user."/".$tel_user."/".$email_user."/".$senha_user."/".$endereco_user."/".
    $cep_User."/".$cidade_User."/".$estadoUser."/".$file;

    include 'db_conect.php';
    $conn = new db_conect();

        $result = $conn->updateCliente($nome_user, $tel_user, $email_user, $senha_user,
        $endereco_user, $cep_User, $cidade_User, $estadoUser, null, 0);

    if (!$result) {
        echo "<br>Erro";
    }
    
?>