<?php
session_start();
class db_conect
{

    private static $conn;
    private $host;
    private $dbname;
    private $user;
    private $password;
    private $view = 'CREATE VIEW produtos_cadastrados AS
    SELECT DISTINCT * FROM (SELECT
        produto.nome AS produto,
        produto.codigo AS produto_id,
        produto.categoria,
        produto.tipo_venda,
        produto.qntd_disponivel,
        produto.preco,
        produto.data_cadastro AS data_anuncio,
        cliente.cidade,
        cliente.estado,
        img_produto.caminho_foto AS foto
    FROM
        produto
    INNER JOIN cliente ON produto.produtor_fk = cliente.email
    INNER JOIN img_produto ON produto.codigo = img_produto.produto_fk
    WHERE caminho_foto = (SELECT caminho_foto FROM img_produto WHERE produto.codigo = img_produto.produto_fk LIMIT 1)) AS produtos_cadastrados;';

    public function __construct(
        $host = 'localhost',
        $dbname = 'daroca',
        $user = 'root',
        $password = ''
    ) {
        // Fazer selecao de tipo de usuario

        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->conectDB();
    }

    public function conectDB()
    {
        self::$conn = mysqli_connect($this->host, $this->user, $this->password, $this->dbname);
        mysqli_set_charset(self::$conn, 'utf8');
        // Check connection
        if (mysqli_connect_errno()) {
            echo "<script>console.log('Não foi possível conectar. Erro: " . mysqli_connect_error() . "' );</script>";
            exit();
        } else {
            echo "<script>console.log('Conexão bem sucedida' );</script>";
            //CRIAR VIEW
            if(!isset($_SESSION['db_conn'])){
                $this->createView();
                unset($_SESSION['db_conn']);
                $_SESSION['db_conn'] = 1;
            }
        }
    }

    private function createView(){

            $resultSttmt = mysqli_prepare(self::$conn, $this->view);
            mysqli_stmt_execute($resultSttmt);
            // Close statement
            mysqli_stmt_close($resultSttmt);
    }

    public function __destruct()
    {
        echo "<script>console.log('Conexão encerrada' );</script>";
        // closing connection
        mysqli_close(self::$conn);
    }

    /********* CUSTOM SELECT *****/

    public function selectCustom($query)
    {
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            mysqli_stmt_execute($resultSttmt);
            $result = mysqli_stmt_get_result($resultSttmt);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $result;
        } else {
            // Close statement
            mysqli_stmt_close($resultSttmt);
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";
            //verificar se foi cadastrado
            return 0;
        }
    }

    // ************************************** CLIENTE ****************************************************************************

    // cadastro
    public function insertCliente(
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
        $caminho_foto_perfil
    ) {
        //query insert
        $query = 'INSERT INTO cliente( cpf, nome, data_nasc, telefone, email, senha, 
    endereco, cep, cidade, estado, caminho_foto_perfil) VALUES (?, ?, ?, ?, ?, ?,
    ?, ?, ?, ?, ?)';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
            Character	Description
            i	corresponding variable has type integer
            d	corresponding variable has type double
            s	corresponding variable has type string
            b	corresponding variable is a binary (such as image, PDF file, etc.)
        */
            //bind param
            mysqli_stmt_bind_param(
                $resultSttmt,
                'sssssssssss',
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
                $caminho_foto_perfil
            );

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return 0;
        }
    }

    // alterar cadastro
    public function updateCliente(
        $nome,
        $telefone,
        $email,
        $senha,
        $endereco,
        $cep,
        $cidade,
        $estado,
        $caminho_foto_perfil,
        $contemFoto
    ) {
        if ($contemFoto) {
            //query insert
            $query = 'UPDATE cliente SET nome = ?, telefone = ?, senha = ?, 
        endereco = ?, cep = ?, cidade = ?, estado = ?, caminho_foto_perfil = ?, update_reg = ? 
        WHERE email = ?';
            // Prepare a query for execution
            if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
                /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
                //bind param
                mysqli_stmt_bind_param(
                    $resultSttmt,
                    'ssssssssss',
                    $nome,
                    $telefone,
                    $senha,
                    $endereco,
                    $cep,
                    $cidade,
                    $estado,
                    $caminho_foto_perfil,
                    date("d-m-Y h:i:sa"),
                    $email
                );

                mysqli_stmt_execute($resultSttmt);

                $success = mysqli_affected_rows(self::$conn);
                // Close statement
                mysqli_stmt_close($resultSttmt);
                //verificar se foi cadastrado
                return $success;
            } else {
                echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                //verificar se foi cadastrado
                return 0;
            }
        } else {
            //query insert
            $query = 'UPDATE cliente SET nome = ?, telefone = ?, senha = ?, 
        endereco = ?, cep = ?, cidade = ?, estado = ?, update_reg = ? 
        WHERE email = ?';
            // Prepare a query for execution
            if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
                /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
                //bind param
                mysqli_stmt_bind_param(
                    $resultSttmt,
                    'sssssssss',
                    $nome,
                    $telefone,
                    $senha,
                    $endereco,
                    $cep,
                    $cidade,
                    $estado,
                    date("d-m-Y h:i:sa"),
                    $email
                );

                mysqli_stmt_execute($resultSttmt);

                $success = mysqli_affected_rows(self::$conn);
                // Close statement
                mysqli_stmt_close($resultSttmt);
                //verificar se foi cadastrado
                return $success;
            } else {
                echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                //verificar se foi cadastrado
                return 0;
            }
        }
    }

    // login
    public function loginCliente($email, $pass)
    {
        //query insert
        $query = 'UPDATE `cliente` SET last_login = ? WHERE email = ? AND senha = ?';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt,'sss', date("d-m-Y h:i:sa"), $email, $pass);

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (Login).\n Erro: " . mysqli_connect_error() . "' );</script>";
            // Close statement
            mysqli_stmt_close($resultSttmt);
            return 0;
        }
    }

    function requestDadosUser($email)
    {

        //query insert
        $query = 'SELECT cpf, nome, data_nasc, telefone, endereco, 
    cep, cidade, estado, caminho_foto_perfil, avaliacao  
    FROM cliente WHERE email = ?';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
            Character	Description
            i	corresponding variable has type integer
            d	corresponding variable has type double
            s	corresponding variable has type string
            b	corresponding variable is a binary (such as image, PDF file, etc.)
        */
            //bind param
            mysqli_stmt_bind_param($resultSttmt, 's', $email);

            mysqli_stmt_execute($resultSttmt);

            $result = mysqli_stmt_get_result($resultSttmt);

            $rows = mysqli_fetch_assoc($result);

            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $rows;
        } else {
            echo "<script>console.log('Não foi possível realizar a requsição de dados' );</script>";
            mysqli_stmt_close($resultSttmt);
            return -1;
        }
    }

    // ************************************** PRODUTO ****************************************************************************

    // cadastro
    public function insertProduto(
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
    ) {
        //query insert
        $query = 'INSERT INTO produto(nome , preco, qntd_disponivel, categoria, tipo_venda, 
        qntd_min_vendida, produtor_fk, data_producao, data_validade, descricao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param(
                $resultSttmt,
                'sddiidssss',
                $name_prod,
                $preco_prod,
                $qntd_prod,
                $categoria_prod,
                $tipo_venda_prod,
                $qntd_min_prod,
                $produtor,
                $producao,
                $validade,
                $descricao
            );

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            return 0;
        }
    }

    //remover anuncio
    public function deleteProduto($codigoProduto, $produtorFk)
    {
        //query insert
        $query = 'DELETE FROM produto WHERE (codigo = ? AND produtor_fk = (SELECT email FROM cliente WHERE cpf = ?));';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt, 'is', $codigoProduto, $produtorFk);

            mysqli_stmt_execute($resultSttmt);

            // Close statement
            mysqli_stmt_close($resultSttmt);

            $query = 'SELECT caminho_foto FROM img_produto WHERE produto_fk = ' . $codigoProduto . ';';
            $caminhos = $this->selectCustom($query);
            $diretorio = "../img/produto/" . $codigoProduto;

            while ($rows = mysqli_fetch_assoc($caminhos)) {
                unlink($rows['caminho_foto']);
            }
            rmdir($diretorio);

            //query insert
            $query = 'DELETE FROM img_produto WHERE produto_fk = ? ;';
            $resultSttmt = mysqli_prepare(self::$conn, $query);
            //bind param
            mysqli_stmt_bind_param($resultSttmt, 'i', $codigoProduto);

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            return 0;
        }
    }

    // cadastro
    public function insertImg_produto($caminho_foto, $produto_fk)
    {
        //query insert
        $query = 'INSERT INTO img_produto (caminho_foto , produto_fk) VALUES (?, ?)';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt, 'si', $caminho_foto, $produto_fk);

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return 0;
        }
    }

    // alterar produto
    public function updateProduto(
        $name_prod,
        $preco_prod,
        $qntd_prod,
        $categoria_prod,
        $tipo_venda_prod,
        $qntd_min_prod,
        $descricao,
        $produtor,
        $codigo
    ) {
        //query insert
        $query = 'UPDATE produto SET nome = ?, preco = ?, qntd_disponivel = ?, 
        categoria = ?, tipo_venda = ?, qntd_min_vendida = ?, descricao = ? 
        WHERE (produtor_fk  = ? AND codigo = ?)';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param(
                $resultSttmt,
                'sddiidssi',
                $name_prod,
                $preco_prod,
                $qntd_prod,
                $categoria_prod,
                $tipo_venda_prod,
                $qntd_min_prod,
                $descricao,
                $produtor,
                $codigo
            );

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";
            // Close statement
            mysqli_stmt_close($resultSttmt);
            return 0;
        }
    }


    // ************************************** CARRINHO ****************************************************************************
    // public function delete()
    // {
    // }

    // public function update()
    // {
    // }

    // public function requestDadosCarrinho($email)
    // {
    // }

    public function insertDadosCarrinho($email_cliente, $produto_codigo, $qntd)
    {
         //query insert
         $query = 'INSERT INTO carrinho (cliente_fk , produto_fk, qntd_produto) VALUES (?, ?, ?)';
         // Prepare a query for execution
         if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
             /*
                 Character	Description
                 i	corresponding variable has type integer
                 d	corresponding variable has type double
                 s	corresponding variable has type string
                 b	corresponding variable is a binary (such as image, PDF file, etc.)
             */
             //bind param
             mysqli_stmt_bind_param($resultSttmt, 'sid', $email_cliente, $produto_codigo, $qntd);
 
             mysqli_stmt_execute($resultSttmt);
 
            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
         } else {
             echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";
             // Close statement
             mysqli_stmt_close($resultSttmt);
             //verificar se foi cadastrado
             return 0;
         }
    }

    public function updateDadosCarrinho($email_cliente, $produto_codigo, $qntd, $from)
    {
        //vindo da pagina do produto
        if($from){
            //query insert
            $query = 'UPDATE carrinho SET qntd_produto = qntd_produto + ? WHERE  cliente_fk = ? AND produto_fk = ?';
        }
        //vindo do carrinho
        else{
            $query = 'UPDATE carrinho SET qntd_produto = ? WHERE  cliente_fk = ? AND produto_fk = ?';
        }
           // Prepare a query for execution
           if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
               /*
               Character	Description
               i	corresponding variable has type integer
               d	corresponding variable has type double
               s	corresponding variable has type string
               b	corresponding variable is a binary (such as image, PDF file, etc.)
           */
               //bind param
               mysqli_stmt_bind_param($resultSttmt, 'dsi', $qntd, $email_cliente, $produto_codigo);

               mysqli_stmt_execute($resultSttmt);
               $success = mysqli_affected_rows(self::$conn);
               // Close statement
               mysqli_stmt_close($resultSttmt);
               //verificar se foi cadastrado
               return $success;
           } else {
               echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";

               // Close statement
               mysqli_stmt_close($resultSttmt);
               //verificar se foi cadastrado
               return 0;
           }
    }


    function removerCarrinho($id_carrinho){
        //query insert
        $query = 'DELETE FROM carrinho WHERE id_carrinho = ?';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt,'i',$id_carrinho);

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";
            // Close statement
            mysqli_stmt_close($resultSttmt);
            return 0;
        }
    }








    // ************************************** COMPRA ****************************************************************************

    function realizarCompra($codProd, $qntd_comprada, $email_cliente){
        //query insert
        $query = 'INSERT INTO compras (cliente_fk, produto_fk, qntd_comprada)
        VALUES (?, ?, ?)';
        // Prepare a query for execution
        if ($resultSttmt = mysqli_prepare(self::$conn, $query)) {
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt,'sid',$email_cliente, $codProd, $qntd_comprada);

            mysqli_stmt_execute($resultSttmt);

            $success = mysqli_affected_rows(self::$conn);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            //verificar se foi cadastrado
            return $success;
        } else {
            echo "<script>console.log('Não foi possível realizar a operação (" . $query . ").\n Erro: " . mysqli_connect_error() . "' );</script>";
            // Close statement
            mysqli_stmt_close($resultSttmt);
            return 0;
        }
    }











}
