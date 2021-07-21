<?php
error_reporting(E_ERROR | E_PARSE);
//session_start();
include 'db_conect.php';
// All request user and product (CRUD)
class controlRequest
{

    // ************************************** USUÁRIO ****************************************************************************

    // ----------- LOGIN (retorna 0/1)

    function login($email, $pass)
    {
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
        if ($result == 0) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível realizar o login!<br>Login ou senha inválida.</div>";
        }

        return $result;
    }

    // ----------- VERIFICAÇÃO DE CADASTROS

    //Alteração cadastral
    //Verificar se o telefone já está em uso (0/1)
    function verificarTelefone($tel)
    {

        // limpar e verificar telefone
        $tel_user = filter_var($tel, FILTER_SANITIZE_STRING);
        $tel_user = preg_replace("/[^0-9]+/", "", $tel_user);
        $tel_user = "(" . substr($tel_user, 0, 2) . ")" . substr($tel_user, 2, 5) . "-" . substr($tel_user, 6);

        $conn = new db_conect();
        $queryTel = "SELECT telefone FROM cliente WHERE email = '" . $_SESSION['log_id'] . "'";
        $resultTel = $conn->selectCustom($queryTel);
        $row = mysqli_fetch_assoc($resultTel);

        //Se o novo telefone for igual ao antigo, tudo bem
        if ($tel == $row['telefone']) {
            return 1;
        }
        //se não, verifica se o novo já está em uso
        else {
            $conn = new db_conect();

            $queryTel = "SELECT COUNT(telefone) AS valida FROM cliente WHERE telefone = '" . $tel_user . "'";
            $resultTel = $conn->selectCustom($queryTel);
            $row = mysqli_fetch_assoc($resultTel);
            //Se o novo telefone for válido
            if ($row['valida'] == 0) {
                return 1;
            } else {
                $mensagemErro = "Erro! Telefone ínválido ou já cadastrado<br>";
                $_SESSION['msg'] = "<div class='alert alert-danger'>" . $mensagemErro . "</div>";

                return 0;
            }
        }
    }

    //Novos cadastros
    //Verificar se o email/telefone/cpf já não está em uso(0/1)
    function verificarCadastro($tel, $cpf, $email)
    {
        // limpar e verificar telefone
        $tel_user = filter_var($tel, FILTER_SANITIZE_STRING);
        $tel_user = preg_replace("/[^0-9]+/", "", $tel_user);
        $tel_user = "(" . substr($tel_user, 0, 2) . ")" . substr($tel_user, 2, 5) . "-" . substr($tel_user, 6);
        // limpar e verificar CPF
        $cpf_user = filter_var($cpf, FILTER_SANITIZE_STRING);
        $cpf_user = preg_replace("/[^0-9]+/", "", $cpf_user);
        $cpf_user = substr($cpf_user, 0, 3) . "." . substr($cpf_user, 3, 3) . "." . substr($cpf_user, 6, 3) . "-" . substr($cpf_user, 9);
        // limpar e verificar email
        $email_user = filter_var($email, FILTER_SANITIZE_EMAIL);


        $conn = new db_conect();

        $queryCPF = "SELECT COUNT(cpf) AS valida FROM cliente WHERE cpf = '" . $cpf_user . "'";
        $queryTel = "SELECT COUNT(telefone) AS valida FROM cliente WHERE telefone = '" . $tel_user . "'";
        $queryEmail = "SELECT COUNT(email) AS valida FROM cliente WHERE email = '" . $email_user . "'";

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

        if (($cpfCheck == 0) && ($telCheck == 0) && ($emailCheck == 0)) {
            return 1;
        } else {
            $mensagemErro = "";
            if ($cpfCheck) {
                $mensagemErro = $mensagemErro . "Erro! CPF ínválido ou já cadastrado<br>";
            }
            if ($resultTel) {
                $mensagemErro = $mensagemErro . "Erro! Telefone ínválido ou já cadastrado<br>";
            }
            if ($resultEmail) {
                $mensagemErro = $mensagemErro . "Erro! E-mail ínválido ou já cadastrado<br>";
            }
            $_SESSION['msg'] = "<div class='alert alert-danger'>" . $mensagemErro . "</div>";
            return 0;
        }
    }

    // ----------- TRATAMENTO DE DADOS

    //Tratamento dos dados recebidos do POST para limpeza contra SQLI(0/1)
    //Chama a função de inserção ou update(1/2)
    function tratamentoDadosUsuario(
        $nome_user,
        $data_nasc,
        $cidade_User,
        $estadoUser,
        $endereco_user,
        $cep_User,
        $tel_user,
        $email_user,
        $senha_user,
        $cpf_user,
        $foto_user,
        $tipo_function
    ) {
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
        $cep_User = substr($cep_User, 0, 5) . "-" . substr($cep_User, 5);
        // limpar e verificar telefone
        $tel_user = filter_var($tel_user, FILTER_SANITIZE_STRING);
        $tel_user = preg_replace("/[^0-9]+/", "", $tel_user);
        $tel_user = "(" . substr($tel_user, 0, 2) . ")" . substr($tel_user, 2, 5) . "-" . substr($tel_user, 6);
        // limpar e verificar email
        $email_user = filter_var($email_user, FILTER_SANITIZE_EMAIL);
        if (filter_var($email_user, FILTER_VALIDATE_EMAIL) === false) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar, erro no e-mail inscrito.</div>";
            header("Location: ../views/cadastro.php");
        }
        // limpar e verificar a senha
        $senha_user = filter_var($senha_user, FILTER_SANITIZE_STRING);
        // limpar e verificar CPF
        $cpf_user = filter_var($cpf_user, FILTER_SANITIZE_STRING);
        $cpf_user = preg_replace("/[^0-9]+/", "", $cpf_user);
        $cpf_user = substr($cpf_user, 0, 3) . "." . substr($cpf_user, 3, 3) . "." . substr($cpf_user, 6, 3) . "-" . substr($cpf_user, 9);

        //1 -> INSERT / 2 -> UPDATE
        if ($tipo_function == 1) {
            return $this->cadastrarUsuario(
                $cpf_user,
                $nome_user,
                $data_nasc,
                $tel_user,
                $email_user,
                $senha_user,
                $endereco_user,
                $cep_User,
                $cidade_User,
                $estadoUser,
                $foto_user
            );
        } elseif ($tipo_function == 2) {
            return $this->updateUsuario(
                $cpf_user,
                $nome_user,
                $tel_user,
                $email_user,
                $senha_user,
                $endereco_user,
                $cep_User,
                $cidade_User,
                $estadoUser,
                $foto_user
            );
        }
    }

    // ----------- CADASTROS

    //Dados repassados da função TRATAMENDO DE DADOS
    //RETORNA 0/1
    function cadastrarUsuario(
        $cpf,
        $nome,
        $data_nasc,
        $telefone,
        $email,
        $senha,
        $endereco,
        $cep,
        $cidade,
        $estado,
        $foto_perfil
    ) {
        $conn = new db_conect();
        //Se tiver foto a ser salva
        if (!empty($foto_perfil["name"])) {
            //Se a foto não for salva, cancela o cadastramento
            $diretorio = $this->guardarFoto($foto_perfil, $cpf, 0);
            $result = $conn->insertCliente(
                $cpf,
                $nome,
                $data_nasc,
                $telefone,
                $email,
                $senha,
                $endereco,
                $cep,
                $cidade,
                $estado,
                $diretorio
            );
            if (!$result) {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
                return 0;
            }
        }
        //Se não tiver foto
        else {
            $result = $conn->insertCliente(
                $cpf,
                $nome,
                $data_nasc,
                $telefone,
                $email,
                $senha,
                $endereco,
                $cep,
                $cidade,
                $estado,
                $foto_perfil
            );
            if (!$result) {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
                return 0;
            }
        }
        $_SESSION['log_id'] = $email;
        return 1;
    }

    // ----------- UPDATE

    function updateUsuario(
        $cpf,
        $nome,
        $telefone,
        $email,
        $senha,
        $endereco,
        $cep,
        $cidade,
        $estado,
        $foto_perfil
    ) {
        $conn = new db_conect();
        if (!empty($foto_perfil["name"])) {
            $diretorio = $this->guardarFoto($foto_perfil, $cpf, 1);
            $result = $conn->updateCliente(
                $nome,
                $telefone,
                $email,
                $senha,
                $endereco,
                $cep,
                $cidade,
                $estado,
                $diretorio,
                1
            );
        } else {
            $result = $conn->updateCliente(
                $nome,
                $telefone,
                $email,
                $senha,
                $endereco,
                $cep,
                $cidade,
                $estado,
                null,
                0
            );
        }

        if (!$result) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
            return 0;
        }
        return 1;
    }

    // ----------- GUARDAR FOTO

    //Arquivo, cliente_PK
    function guardarFoto($foto, $id_table, $update)
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
        list($width, $height, $type, $attr) = getimagesize($foto['tmp_name']);

        if ($width > $maxDimW || $height > $maxDimH) {
            // =============== RENOMEACAO E CAMINHO ========================
            //Local a ser salvo
            $diretorio = "../img/perfil/perfis/" . $id_table;
            // Cria a pasta se não existir
            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0777, true);
            }
            // Nome da foto
            $nomeFoto = md5(uniqid(time())) . "." . "jpeg";
            //Local a ser salvo
            $diretorio = "../img/perfil/perfis/" . $id_table . "/" . $nomeFoto;


            // ================ CONVERTER/SALVAR/RESIZE ===================
            // local temporario onde a foto está
            $path_tmp = $foto['tmp_name'];
            // salva + uma vez o local temporario onde a foto está
            $foto_tmp = $foto['tmp_name'];
            //Pega as dimensões da imagem 
            $size = getimagesize($foto_tmp);
            // Faz a relação width/height p/achar a escala
            $ratio = $size[0] / $size[1];

            if ($ratio > 1) {
                $width = $maxDimW;
                $height = $maxDimH / $ratio;
            } else {
                $width = $maxDimW * $ratio;
                $height = $maxDimH;
            }

            $src = imagecreatefromstring(file_get_contents($foto_tmp));
            $dst = imagecreatetruecolor($width, $height);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

            if (imagejpeg($dst, $diretorio, 70) === true) {
                //Recuperar caminho da antiga foto e deletar
                if ($update) {
                    $conn = new db_conect();
                    $query = 'SELECT caminho_foto_perfil FROM cliente WHERE email = "' . $_SESSION['log_id'] . '"';
                    $result = $conn->selectCustom($query);
                    $row = mysqli_fetch_assoc($result);
                    if ($row['caminho_foto_perfil'] == null || $row['caminho_foto_perfil'] == "") {
                        unlink($row['caminho_foto_perfil']);
                    }
                }
                return $diretorio;
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar, erro na imagem!</div>";
                header("Location: ../views/cadastro.php");
            }
        } else {
            //Local a ser salvo
            $diretorio = "../img/perfil/perfis/" . $id_table;
            // Cria a pasta se não existir
            if (!file_exists($diretorio)) {
                mkdir($diretorio, 0777, true);
            }
            // Nome da foto
            $nomeFoto = md5(uniqid(time())) . "." . "jpeg";
            //Local a ser salvo
            $diretorio = "../img/perfil/perfis/" . $id_table . "/" . $nomeFoto;

            move_uploaded_file($foto['tmp_name'], $diretorio);

            //Recuperar caminho da antiga foto e deletar
            if ($update) {
                $conn = new db_conect();
                $query = 'SELECT caminho_foto_perfil FROM cliente WHERE email = "' . $_SESSION['log_id'] . '"';
                $result = $conn->selectCustom($query);
                $row = mysqli_fetch_assoc($result);
                if ($row['caminho_foto_perfil'] == null || $row['caminho_foto_perfil'] == "") {
                    unlink($row['caminho_foto_perfil']);
                }
            }

            return $diretorio;
        }

        /*------------------ Fim inserir imagem ------------------*/
    }

    // ----------- REQUEST DADOS

    function requestDadosUser($email)
    {

        $conn = new db_conect();
        $result = $conn->requestDadosUser($email);

        return $result;
    }

    function requestDadosCarrinho($email){
        //query
        $query = "CALL requestDadosCarrinho('".$_SESSION['log_id']."');";
        //num de produtos no bd
        return $this->selectCustom($query);
    }

    function insertUpdateFromPage($produto_codigo, $qntd){
        //query paginas
        $query = 'SELECT COUNT(id_carrinho) AS existe FROM carrinho WHERE cliente_fk = "'.$_SESSION['log_id'].'" AND produto_fk = '.$produto_codigo.'';
        //num de produtos no bd
        $verifica = $this->selectCustom($query);
        $verifica = mysqli_fetch_assoc($verifica);
        
        if($verifica['existe']){
            return $this->updateCarrinho($produto_codigo, $qntd, 1);
        }
        else{
            return $this->insertCarrinho($produto_codigo, $qntd);
        }
    }

    function insertCarrinho($produto_codigo, $qntd){
        $conn = new db_conect();
        $qntd_prod = filter_var($qntd, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $result = $conn->insertDadosCarrinho($_SESSION['log_id'], $produto_codigo, $qntd_prod);
        return $result;
    }

    function updateCarrinho($produto_codigo, $qntd, $from){
        $conn = new db_conect();
        $qntd_prod = filter_var($qntd, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $result = $conn->updateDadosCarrinho($_SESSION['log_id'], $produto_codigo, $qntd_prod, $from);
        return $result;
    }

    function qntdProdutosAnunciados(){
        //query paginas
        $query = 'SELECT COUNT(codigo) AS qntd_itens FROM produto WHERE produtor_fk = "'.$_SESSION['log_id'].'"';
        //num de produtos no bd
        $pagesDB = $this->selectCustom($query);
        $pagesDB = mysqli_fetch_assoc($pagesDB);
        
        return $pagesDB['qntd_itens'];
    }

    function valorTotalEQntdProdutosCarrinho(){
        //query 
        $query = "SELECT IFNULL(ROUND(SUM(produto.preco * carrinho.qntd_produto),2),0) AS total, COUNT(id_carrinho) AS qntd_produtos
        FROM carrinho 
        INNER JOIN produto ON produto.codigo = carrinho.produto_fk 
        WHERE carrinho.cliente_fk = '".$_SESSION['log_id']."'";
        $valorTotal = $this->selectCustom($query);
        $valorTotal = mysqli_fetch_assoc($valorTotal);
        
        return $valorTotal;
    }

    // ************************************** PRODUTO ****************************************************************************

    // ----------- TRATAMENTO DE DADOS

    function tratamentoDadosProduto(
        $name_prod,
        $preco_prod,
        $qntd_prod,
        $categoria_prod,
        $tipo_venda_prod,
        $qntd_min_prod,
        $descricao,
        $producao,
        $validade,
        $imgProdutos,
        $tipo_function
    ) {
        // limpar o nome do produto para não correr risco de SQLI ou partes html
        $name_prod = filter_var($name_prod, FILTER_SANITIZE_STRING);
        // limpar o valor do produto p/conter apenas números
        $preco_prod = filter_var($preco_prod, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // limpar a qntd disponivel do produto p/conter apenas números
        $qntd_prod = filter_var($qntd_prod, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // limpar a categoria do produto p/conter apenas números
        $categoria_prod = filter_var($categoria_prod, FILTER_SANITIZE_NUMBER_INT);
        // limpar o tipo de venda do produto p/conter apenas números
        $tipo_venda_prod = filter_var($tipo_venda_prod, FILTER_SANITIZE_NUMBER_INT);
        // limpar a qntd minima de venda do produto p/conter apenas números
        $qntd_min_prod = filter_var($qntd_min_prod, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        // limpar a descrição do produto para não correr risco de SQLI ou partes html
        $descricao = filter_var($descricao, FILTER_SANITIZE_STRING);
        // limpar e verificar se a data contém apenas números
        $producao = preg_replace("([^0-9/-])", "", $producao);
        // limpar e verificar se a data contém apenas números
        $validade = preg_replace("([^0-9/-])", "", $validade);

        if ($tipo_function == 1) {
            return $this->cadastrarProduto(
                $name_prod,
                $preco_prod,
                $qntd_prod,
                $categoria_prod,
                $tipo_venda_prod,
                $qntd_min_prod,
                $descricao,
                $producao,
                $validade,
                $imgProdutos
            );
        } elseif ($tipo_function == 2) {
            return $this->updateProduto(
                $name_prod,
                $preco_prod,
                $qntd_prod,
                $categoria_prod,
                $tipo_venda_prod,
                $qntd_min_prod,
                $descricao,
                $producao,
                $validade,
                $imgProdutos
            );
        }
    }

    // ----------- CADASTRO 

    function cadastrarProduto(
        $name_prod,
        $preco_prod,
        $qntd_prod,
        $categoria_prod,
        $tipo_venda_prod,
        $qntd_min_prod,
        $descricao,
        $producao,
        $validade,
        $imgProdutos
    ) {
        $conn = new db_conect();
        $produtor = $_SESSION['log_id'];

        $result = $conn->insertProduto(
            $name_prod,
            $preco_prod,
            $qntd_prod,
            $categoria_prod,
            $tipo_venda_prod,
            $qntd_min_prod,
            $descricao,
            $producao,
            $validade,
            $produtor
        );
        if (!$result) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
            echo "<script>console.log('Não foi possível realizar a operação.);</script>";
            return 0;
        } else {
            $query = $query = "SELECT codigo FROM produto 
            WHERE produto.produtor_fk = '" . $produtor . "' AND produto.nome = '" . $name_prod . "';";

            //CPF check
            $result = $conn->selectCustom($query);
            $row = mysqli_fetch_assoc($result);
            //Arquivo File e codido do produto p/criar e armazenar fotos na pasta identificada
            //pelo codigo do produto
            $result = $this->guardarFotoProduto($imgProdutos, $row['codigo']);
        }

        return 1;
    }

    // ----------- UPDATE

    function updateProduto(
        $name_prod,
        $preco_prod,
        $qntd_prod,
        $categoria_prod,
        $tipo_venda_prod,
        $qntd_min_prod,
        $descricao,
        $imgProdutos
    ) {
        $conn = new db_conect();
        $produtor = $_SESSION['log_id'];

        $result = $conn->updateProduto(
            $name_prod,
            $preco_prod,
            $qntd_prod,
            $categoria_prod,
            $tipo_venda_prod,
            $qntd_min_prod,
            $descricao,
            $produtor,
            $_SESSION['id_prod']
        );
        if (!$result) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível realizar a alteração dos dados do produto!</div>";
            return 0;
        } else {
            $this->guardarFotoProduto($imgProdutos, $_SESSION['id_prod']);
        }

        return 1;
    }

    // ----------- GUARDAR FOTO

    //Arquivo, produto_fk, numero da foto
    function guardarFotoProduto($foto, $id_table)
    {
        $conn = new db_conect();
        $countfiles = count($foto['name']);

        for ($i = 0; $i < $countfiles; $i++) {
            /*------------------ Inserir imagem ------------------*/

            // Validando a imagem
            // Tamanho máximo do arquivo em bytes 
            $maxDimW = 400;
            $maxDimH = 400;

            // Verifica se o arquivo é uma imagem através do match com os formatos possiveis
            $tipos = "%\.(jpg|jpeg|png|gif|bmp)$%i";

            //Se a imagem não for dos tipos possiveis não é feito o cadastro e é informado o motivo
            if (preg_match($tipos, $foto["type"][$i]) == 1) {
                return -1;
            }

            // Pega as dimensões da imagem 
            //$dimensoes = getimagesize($imagem["tmp_name"]);
            list($width, $height, $type, $attr) = getimagesize($foto['tmp_name'][$i]);

            if ($width > $maxDimW || $height > $maxDimH) {
                // =============== RENOMEACAO E CAMINHO ========================
                //Local a ser salvo
                $diretorio = "../img/produto/" . $id_table;
                // Cria a pasta se não existir
                if (!file_exists($diretorio)) {
                    mkdir($diretorio, 0777, true);
                }
                // Nome da foto
                $nomeFoto = md5(uniqid(time())) . "." . "jpeg";
                //Local a ser salvo
                $diretorio = $diretorio . "/" . $nomeFoto;


                // ================ CONVERTER/SALVAR/RESIZE ===================
                // local temporario onde a foto está
                $path_tmp = $foto['tmp_name'][$i];
                // salva + uma vez o local temporario onde a foto está
                $foto_tmp = $foto['tmp_name'][$i];
                //Pega as dimensões da imagem 
                $size = getimagesize($foto_tmp);
                // Faz a relação width/height p/achar a escala
                $ratio = $size[0] / $size[1];

                if ($ratio > 1) {
                    $width = $maxDimW;
                    $height = $maxDimH / $ratio;
                } else {
                    $width = $maxDimW * $ratio;
                    $height = $maxDimH;
                }

                $src = imagecreatefromstring(file_get_contents($foto_tmp));
                $dst = imagecreatetruecolor($width, $height);
                imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

                if (imagejpeg($dst, $diretorio, 70) === true) {
                    //Realiza a inserçao das imagens no BD, na tabela img_prod
                    if (!$conn->insertImg_produto($diretorio, $id_table)) {
                        header("Location: ../controller/teste.php");
                    }
                } else {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro no envio das imagens!<br>Algumas imagens não foram enviadas, tente novamente.</div>";
                }
            } else {
                //Local a ser salvo
                $diretorio = "../img/produto/" . $id_table;
                // Cria a pasta se não existir
                if (!file_exists($diretorio)) {
                    mkdir($diretorio, 0777, true);
                }
                // Nome da foto
                $nomeFoto = md5(uniqid(time())) . "." . "jpeg";
                //Local a ser salvo
                $diretorio = $diretorio . "/" . $nomeFoto;

                move_uploaded_file($foto['tmp_name'][$i], $diretorio);

                //Realiza a inserçao das imagens no BD, na tabela img_prod
                if (!$conn->insertImg_produto($diretorio, $id_table)) {
                    header("Location: ../controller/teste.php");
                }
            }
        }

        /*------------------ Fim inserir imagem ------------------*/
    }

    // ----------- Remove PRODUTO

    function removerProduto($codigo, $produtor_fk)
    {

        $conn = new db_conect();

        $result = $conn->deleteProduto($codigo, $produtor_fk);

        if (!$result) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Não foi possível cadastrar!</div>";
            echo "<script>console.log('Não foi possível realizar a operação.);</script>";
            return 0;
        }
        return 1;
    }

    // ----------- REQUEST TODOS OS DADOS DE PRODUTOS

    function requestAllDadosProduto($page, $itensPorPagina)
    {

        $conn = new db_conect();
        $query = 'CALL requestAllDadosProduto(' . $page . ', ' . $itensPorPagina . ', "' . $_SESSION['log_id'] . '");';
        $result = $conn->selectCustom($query);
        return $result;
    }

    // ----------- REQUEST DADOS DE UM PRODUTO
    function requestDadosProduto($produto_id)
    {

        $conn = new db_conect();
        $query = 'CALL requestDadosProduto(' . $produto_id . ');';
        $result = $conn->selectCustom($query);
        return $result;
    }

    // ----------- REQUEST FOTOS DE UM PRODUTO
    function requestFotosProduto($produto_id)
    {

        $conn = new db_conect();
        $query = 'SELECT caminho_foto FROM img_produto WHERE produto_fk = ' . $produto_id . ';';
        $result = $conn->selectCustom($query);
        return $result;
    }

    // ----------- RETORNAR CATEGORIA ESCRITA DO PRODUTO
    function categoriaProduto($categoriaProd)
    {
        switch ($categoriaProd) {
            case 1:
                $categoria = 'Frutas';
                break;
            case 2:
                $categoria = 'Verduras';
                break;
            case 3:
                $categoria = 'Legumes';
                break;
            case 4:
                $categoria = 'Bebidas';
                break;
            case 5:
                $categoria = 'Frios';
                break;
            case 6:
                $categoria = 'Especiarias';
                break;
        }
        return $categoria;
    }

    // ----------- RETORNAR O TIPO DE FABRICAÇÂO
    function tipoFabricacao($categoriaProd)
    {
        switch ($categoriaProd) {
            case 1:
                $fraseCategoria = 'colheita';
                break;
            case 2:
                $fraseCategoria = 'colheita';
                break;
            case 3:
                $fraseCategoria = 'colheita';
                break;
            case 4:
                $fraseCategoria = 'envase';
                break;
            case 5:
                $fraseCategoria = 'produção';
                break;
            case 6:
                $fraseCategoria = 'fabricação';
                break;
        }
        return $fraseCategoria;
    }

    // ----------- RETORNAR O TIPO DE VENDA
    function tipoVendaProduto($tpVenda)
    {
        switch ($tpVenda) {
            case 1:
                $tipo_venda = 'Kg';
                break;
            case 2:
                $tipo_venda = 'Caixa';
                break;
            case 3:
                $tipo_venda = 'Litro';
                break;
            case 4:
                $tipo_venda = 'Dúzia';
                break;
            case 5:
                $tipo_venda = 'Unidade';
                break;
        }
        return $tipo_venda;
    }

    // ----------- RETORNAR O STEP DO INPUT NA COMPRA
    function stepVendaProduto($tpVenda)
    {
        switch ($tpVenda) {
            case 1:
                $step = 0.05;
                break;
            case 2:
                $step = 1;
                break;
            case 3:
                $step = 0.1;
                break;
            case 4:
                $step = 0.5;
                break;
            case 5:
                $step = 1;
                break;
        }
        return $step;
    }

    // ----------- QNTD


    // ************************************** ALL ****************************************************************************

    private function selectCustom($query)
    {
        $conn = new db_conect();
        $result = $conn->selectCustom($query);
        return $result;
    }

    function avaliacaoImg($avaliacao)
    {
        if ($avaliacao < 1) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval0.png" alt="Avaliação">';
        } elseif ($avaliacao >= 1 && $avaliacao < 1.5) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval1.png" alt="Avaliação">';
        } elseif ($avaliacao >= 1.5 && $avaliacao < 2) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval15.png" alt="Avaliação">';
        } elseif ($avaliacao >= 2 && $avaliacao < 2.5) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval2.png" alt="Avaliação">';
        } elseif ($avaliacao >= 2.5 && $avaliacao < 3) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval25.png" alt="Avaliação">';
        } elseif ($avaliacao >= 3 && $avaliacao < 3.5) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval3.png" alt="Avaliação">';
        } elseif ($avaliacao >= 3.5 && $avaliacao < 4) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval35.png" alt="Avaliação">';
        } elseif ($avaliacao >= 4 && $avaliacao < 4.5) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval4.png" alt="Avaliação">';
        } elseif ($avaliacao >= 4.5 && $avaliacao < 5) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval45.png" alt="Avaliação">';
        } elseif ($avaliacao == 5) {
            $imgAval = '<img class="img-fluid img_aval" src="../img/perfil/standart/avaliacao/aval5.png" alt="Avaliação">';
        }
        return $imgAval;
    }

    function convertDate($data)
    {
        $dataConvert = new DateTime($data);
        return $dataConvert->format('d/m/Y');
    }
}
