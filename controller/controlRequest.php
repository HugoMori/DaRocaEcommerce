<?php
error_reporting(E_ALL ^ E_WARNING); 
//session_start();
include 'db_conect.php';
// All request user and product (CRUD)
class controlRequest
{

 // ************************************** LOGIN
 
 // ----------- USUÁRIO (retorna 0/1)

 function login($email, $pass){
    $conn = new db_conect();
     // limpar e verificar email
     $email = filter_var($email, FILTER_SANITIZE_EMAIL);
     if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
         $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível realizar o login!<br>Login ou senha inválida.</div>";
         return 0;
     }
     // limpar e verificar a senha
     $senha_user = filter_var($pass, FILTER_SANITIZE_STRING);

     $result = $conn->loginCliente($email, $senha_user);
     if($result == 0){
        $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível realizar o login!<br>Login ou senha inválida.</div>";
     }
     
     return $result;

 }
// ************************************** VERIFICAÇÃO DE CADASTROS

// ----------- USUÁRIO

    function verificarTelefone($tel, $antigoTel){
        // limpar e verificar telefone
        $tel_user = filter_var($tel, FILTER_SANITIZE_STRING);
        $tel_user = preg_replace("/[^0-9]+/", "", $tel_user);
        $tel_user = "(".substr($tel_user, 0, 2).")".substr($tel_user, 2, 5)."-".substr($tel_user, 6);
        //Se o novo telefone for igual ao antigo
        if($tel == $antigoTel){
            return 1;
        }
        else{
            $conn = new db_conect();

            $queryTel = "SELECT COUNT(telefone) AS valida FROM cliente WHERE telefone = '".$tel_user."'";
            //Tel check
            $resultTel = $conn->selectCustom($queryTel);
            $row = mysqli_fetch_assoc($resultTel);
            //Se o novo telefone for válido
            if($row['valida'] == 0){
                return 1;
            }
            else{
                $mensagemErro = "Erro! Telefone ínválido ou já cadastrado<br>";
                $_SESSION['msg'] = "<div class='alert alert-danger'>".$mensagemErro."</div>";
                
                return 0;
            }
        }
    }

    function verificarCadastro($tel, $cpf, $email){
        // limpar e verificar telefone
        $tel_user = filter_var($tel, FILTER_SANITIZE_STRING);
        $tel_user = preg_replace("/[^0-9]+/", "", $tel_user);
        $tel_user = "(".substr($tel_user, 0, 2).")".substr($tel_user, 2, 5)."-".substr($tel_user, 6);
        // limpar e verificar CPF
        $cpf_user = filter_var($cpf, FILTER_SANITIZE_STRING);
        $cpf_user = preg_replace("/[^0-9]+/", "", $cpf_user);
        $cpf_user = substr($cpf_user, 0, 3).".".substr($cpf_user, 3, 3).".".substr($cpf_user, 6, 3)."-".substr($cpf_user, 9);
        // limpar e verificar email
        $email_user = filter_var($email, FILTER_SANITIZE_EMAIL);


        $conn = new db_conect();

        $queryCPF = "SELECT COUNT(cpf) AS valida FROM cliente WHERE cpf = '".$cpf_user."'";
        $queryTel = "SELECT COUNT(telefone) AS valida FROM cliente WHERE telefone = '".$tel_user."'";
        $queryEmail = "SELECT COUNT(email) AS valida FROM cliente WHERE email = '".$email_user."'";

        //CPF check
        $resultCpf = $conn->selectCustom($queryCPF);
        $row = mysqli_fetch_assoc($resultCpf);
        $cpfCheck = $row['valida'];
        //Tel check
        $resultTel = $conn->selectCustom($queryTel);
        $row = mysqli_fetch_assoc($resultTel);
        $telCheck = $row['valida'];
        //Email check
        $resultEmail = $conn->selectCustom($queryEmail);
        $row = mysqli_fetch_assoc($resultEmail);
        $emailCheck = $row['valida'];

        if(($cpfCheck == 0) && ($telCheck == 0) && ($emailCheck == 0)){
            return 1;
        }
        else{
            $mensagemErro = "";
            if($cpfCheck){
                $mensagemErro = $mensagemErro."Erro! CPF ínválido ou já cadastrado<br>";
            }
            if($resultTel){
                $mensagemErro = $mensagemErro."Erro! Telefone ínválido ou já cadastrado<br>";
            }
            if($resultEmail){
                $mensagemErro = $mensagemErro."Erro! E-mail ínválido ou já cadastrado<br>";
            }
            $_SESSION['msg'] = "<div class='alert alert-danger'>".$mensagemErro."</div>";
            return 0;
        }

    }

// ************************************** TRATAMENTO DE DADOS

// ----------- USUÁRIO

    function tratamentoDadosUsuario( $nome_user, $data_nasc, $cidade_User, $estadoUser, 
    $endereco_user, $cep_User, $tel_user, $email_user, $senha_user, $cpf_user, $foto_user, $tipo_function) 
    {
        // limpar e verificar se o nome não tem números ou elementos de query ou html
        $nome_user = filter_var($nome_user, FILTER_SANITIZE_STRING);
        $nome_user = preg_replace("/[^a-zA-Z]+/", " ", $nome_user);
        // limpar e verificar se a data contém apenas números
        $data_nasc = preg_replace("([^0-9/-])", "", $data_nasc);
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
            header("Location: ../views/cadastro.php");
        }
        // limpar e verificar a senha
        $senha_user = filter_var($senha_user, FILTER_SANITIZE_STRING);
        // limpar e verificar CPF
        $cpf_user = filter_var($cpf_user, FILTER_SANITIZE_STRING);
        $cpf_user = preg_replace("/[^0-9]+/", "", $cpf_user);
        $cpf_user = substr($cpf_user, 0, 3).".".substr($cpf_user, 3, 3).".".substr($cpf_user, 6, 3)."-".substr($cpf_user, 9);

        if ($tipo_function == 1) {
            return $this->cadastrarUsuario( $cpf_user, $nome_user, $data_nasc, $tel_user, 
            $email_user, $senha_user, $endereco_user, $cep_User, $cidade_User, $estadoUser, $foto_user);
        } elseif($tipo_function == 2) {
            return $this->updateUsuario( $cpf_user, $nome_user, $tel_user, $email_user, $senha_user,
            $endereco_user, $cep_User, $cidade_User, $estadoUser, $foto_user);
        } elseif($tipo_function == 3) {
            $this->teste( $cpf_user, $nome_user, $tel_user, $email_user, $senha_user,
            $endereco_user, $cep_User, $cidade_User, $estadoUser, $foto_user);
        }
    }

// ----------- PRODUTO

    // function tratamentoDadosProduto()
    // {
    // }

// ************************************** CADASTROS

// ----------- USUÁRIO

    function cadastrarUsuario($cpf, $nome, $data_nasc, $telefone, $email, $senha,
        $endereco, $cep, $cidade, $estado, $foto_perfil) 
    {
        $conn = new db_conect();
        
        if (!empty($foto_perfil["name"])) {
            $diretorio = $this->guardarFoto($foto_perfil, $cpf);
            $result = $conn->insertCliente($cpf, $nome, $data_nasc, $telefone, $email, $senha,
            $endereco, $cep, $cidade, $estado, $diretorio);
            if (!$result) {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
                return 0;
            }
            
        }
        else{
            $result = $conn->insertCliente($cpf, $nome, $data_nasc, $telefone, $email, $senha,
            $endereco, $cep, $cidade, $estado, $foto_perfil);
            if (!$result) {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
                return 0;
            }
            
        }
        $_SESSION['log_id'] = $email;
        return 1;
    }

// ----------- PRODUTO

 // function cadastrarProduto()
    // {
    // }

// ************************************** UPDATE

// ----------- USUÁRIO

    function updateUsuario($cpf, $nome, $telefone, $email, $senha,
    $endereco, $cep, $cidade, $estado, $foto_perfil) 
    {
        $conn = new db_conect();
        if (!empty($foto_perfil["name"])) {
            $diretorio = $this->guardarFoto($foto_perfil, $cpf);
            $result = $conn->updateCliente($nome, $telefone, $email, $senha,
            $endereco, $cep, $cidade, $estado, $diretorio, 1);
        }
        else{
            $result = $conn->updateCliente($nome, $telefone, $email, $senha,
            $endereco, $cep, $cidade, $estado, null, 0);
        }

        if (!$result) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
            return 0;
        }
        return 1;
    }

// ----------- PRODUTO

    // function updateProduto()
    // {
    // }


// ************************************** GUARDAR FOTO

    function guardarFoto($foto, $id_table)
    {

        /*------------------ Inserir imagem ------------------*/

            // Validando a imagem
            // Tamanho máximo do arquivo em bytes 
            $maxDimW = 400;
            $maxDimH = 400;

            // Verifica se o arquivo é uma imagem através do match com os formatos possiveis
            $tipos = "%\.(jpg|jpeg|png|gif|bmp)$%i";

            //Se a imagem não for dos tipos possiveis não é feito o cadastro e é informado o motivo
            if (preg_match($tipos, $foto["type"]) == 1) {
                return -1;
            }

            // Pega as dimensões da imagem 
            //$dimensoes = getimagesize($imagem["tmp_name"]);
            list($width, $height, $type, $attr) = getimagesize( $foto['tmp_name']);

            if ( $width > $maxDimW || $height > $maxDimH ) {
                // =============== RENOMEACAO E CAMINHO ========================
                //Local a ser salvo
                $diretorio = "../img/perfil/perfis/".$id_table;
                // Cria a pasta se não existir
                if (!file_exists($diretorio)) {
                    mkdir($diretorio, 0777, true);
                }
                // Nome da foto
                $nomeFoto = md5(uniqid(time())) . "." . "jpeg";
                //Local a ser salvo
                $diretorio = "../img/perfil/perfis/".$id_table."/".$nomeFoto;
                
                
                // ================ CONVERTER/SALVAR/RESIZE ===================
                // local temporario onde a foto está
                $path_tmp = $foto['tmp_name'];
                // salva + uma vez o local temporario onde a foto está
                $foto_tmp = $foto['tmp_name'];
                //Pega as dimensões da imagem 
                $size = getimagesize( $foto_tmp );
                // Faz a relação width/height p/achar a escala
                $ratio = $size[0]/$size[1]; 

                if( $ratio > 1) {
                    $width = $maxDimW;
                    $height = $maxDimH/$ratio;
                } else {
                    $width = $maxDimW*$ratio;
                    $height = $maxDimH;
                }

                $src = imagecreatefromstring(file_get_contents($foto_tmp));
                $dst = imagecreatetruecolor( $width, $height );
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            
                if(imagejpeg($dst, $diretorio , 70) === true){
                    return $diretorio;
                    // move_uploaded_file($dst, $diretorio);
                }
                else{
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar, erro na imagem!</div>";
                    header("Location: ../views/cadastro.php");
                }
                
            
            }
            else{
                //Local a ser salvo
                $diretorio = "../img/perfil/perfis/".$id_table;
                // Cria a pasta se não existir
                if (!file_exists($diretorio)) {
                    mkdir($diretorio, 0777, true);
                }
                // Nome da foto
                $nomeFoto = md5(uniqid(time())) . "." . "jpeg";
                //Local a ser salvo
                $diretorio = "../img/perfil/perfis/".$id_table."/".$nomeFoto;

                move_uploaded_file($foto['tmp_name'], $diretorio);
                return $diretorio;
            }

        /*------------------ Fim inserir imagem ------------------*/
    }


// ************************************** REQUEST DADOS

// ----------- USER

function requestDadosUser($email){

    $conn = new db_conect();
    $result = $conn->requestDadosUser($email);

    return $result;
}

// ----------- PRODUTO

// ************************************** TESTE POST

// ----------- USER

function teste( $cpf_user, $nome_user, $tel_user, $email_user, $senha_user,
$endereco_user, $cep_User, $cidade_User, $estadoUser, $foto_user){
    echo $cpf_user."/".$nome_user."/".$tel_user."/".$email_user."/".$senha_user."/".$endereco_user."/".
    $cep_User."/".$cidade_User."/".$estadoUser."/".$foto_user;
}

}
