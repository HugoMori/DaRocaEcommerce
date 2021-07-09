<?php

class db_conect
{

    private static $conn;
    private $host;
    private $dbname;
    private $user;
    private $password;


    // Elephant
    public function __construct($host = 'localhost', $dbname = 'daroca', 
    $user = 'root', $password = '')
    {
        // Fazer selecao de tipo de usuario

        $this->host = $host;
        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->conectDB();
    }

    // // Heroku
    // public function __construct($host = 'ec2-52-5-1-20.compute-1.amazonaws.com', $port = '5432', 
    // $dbname = 'dcppdl8vp99mqe', $user = 'yjigivmvumknrk', 
    // $password = '5cbc61461b5afd0c761a384e5255db0494e36a7cc3fbffeedeaa839e1e322bb2')
    // {
    //     // Fazer selecao de tipo de usuario

    //     $this->host = $host;
    //     $this->port = $port;
    //     $this->dbname = $dbname;
    //     $this->user = $user;
    //     $this->password = $password;
    //     $this->conectDB();
    // }

    // // 'localhost', '5432', 'daroca', 'postgres', '140566'
    // public function __construct($host = 'localhost', $port = '5432', $dbname = 'daroca',
    //  $user = 'postgres', $password = '140566')
    // {
    //     // Fazer selecao de tipo de usuario

    //     $this->host = $host;
    //     $this->port = $port;
    //     $this->dbname = $dbname;
    //     $this->user = $user;
    //     $this->password = $password;
    //     $this->conectDB();
    // }

    public function conectDB()
    {
        self::$conn = mysqli_connect($this->host, $this->user, $this->password, $this->dbname);
        // Check connection
        if (mysqli_connect_errno()) {
            echo "<script>console.log('Não foi possível conectar. Erro: ".mysqli_connect_error()."' );</script>";
            exit();
        }
        else {
            echo "<script>console.log('Conexão bem sucedida' );</script>";
        }
    }

    public function __destruct()
    {
        // closing connection
        mysqli_close(self::$conn);
    }

    /********* CUSTOM SELECT *****/

    public function selectCustom($query){
        if($resultSttmt = mysqli_prepare(self::$conn, $query)){
            mysqli_stmt_execute($resultSttmt);
            $result = mysqli_stmt_get_result($resultSttmt);
            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            //verificar se foi cadastrado
            return $result;
        }
        else{
            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            //verificar se foi cadastrado
            return 0;
        }
        
    }

    /********* CLIENTE ********* */ 

    // cadastro
    public function insertCliente($cpf, $nome, $data_nasc, $telefone, $email, 
    $senha, $endereco, $cep, $cidade, $estado, $caminho_foto_perfil)
    {
        //query insert
        $query = 'INSERT INTO cliente( cpf, nome, data_nasc, telefone, email, senha, 
        endereco, cep, cidade, estado, caminho_foto_perfil) VALUES (?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?)';
        // Prepare a query for execution
        if($resultSttmt = mysqli_prepare(self::$conn, $query)){
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt,'sssssssssss', $cpf, $nome, $data_nasc, $telefone, $email, 
            $senha, $endereco, $cep, $cidade, $estado, $caminho_foto_perfil);

            mysqli_stmt_execute($resultSttmt);

            echo "Records inserted successfully.";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            //verificar se foi cadastrado
            return 1;
        }
        else{
            echo "Records not inserted.";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            //verificar se foi cadastrado
            return 0;
        }
        
    }

    // alterar cadastro
    public function updateCliente($nome, $telefone, $email, 
    $senha, $endereco, $cep, $cidade, $estado, $caminho_foto_perfil, $contemFoto)
    {
        if($contemFoto){
            //query insert
            $query = 'UPDATE cliente SET nome = ?, telefone = ?, senha = ?, 
            endereco = ?, cep = ?, cidade = ?, estado = ?, caminho_foto_perfil = ?, update_reg = ? 
            WHERE email = ?';
            // Prepare a query for execution
            if($resultSttmt = mysqli_prepare(self::$conn, $query)){
                /*
                    Character	Description
                    i	corresponding variable has type integer
                    d	corresponding variable has type double
                    s	corresponding variable has type string
                    b	corresponding variable is a binary (such as image, PDF file, etc.)
                */
                //bind param
                mysqli_stmt_bind_param($resultSttmt,'ssssssssss', $nome, $telefone, $senha, $endereco, 
                $cep, $cidade, $estado, $caminho_foto_perfil, date("d-m-Y h:i:sa"), $email);

                mysqli_stmt_execute($resultSttmt);

                echo "Records updated successfully.";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                // Close connection
                mysqli_close(self::$conn);
                //verificar se foi cadastrado
                return 1;
            }
            else{
                echo "Records not inserted.";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                // Close connection
                mysqli_close(self::$conn);
                //verificar se foi cadastrado
                return 0;
            }
        }
        else{
            //query insert
            $query = 'UPDATE cliente SET nome = ?, telefone = ?, senha = ?, 
            endereco = ?, cep = ?, cidade = ?, estado = ?, update_reg = ? 
            WHERE email = ?';
            // Prepare a query for execution
            if($resultSttmt = mysqli_prepare(self::$conn, $query)){
                /*
                    Character	Description
                    i	corresponding variable has type integer
                    d	corresponding variable has type double
                    s	corresponding variable has type string
                    b	corresponding variable is a binary (such as image, PDF file, etc.)
                */
                //bind param
                mysqli_stmt_bind_param($resultSttmt,'sssssssss', $nome, $telefone, $senha, $endereco, 
                $cep, $cidade, $estado, date("d-m-Y h:i:sa"), $email);

                mysqli_stmt_execute($resultSttmt);

                echo "Records updated successfully.";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                // Close connection
                mysqli_close(self::$conn);
                //verificar se foi cadastrado
                return 1;
            }
            else{
                echo "Records not inserted.";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                // Close connection
                mysqli_close(self::$conn);
                //verificar se foi cadastrado
                return 0;
            }
        }
    }

    // login
    public function loginCliente($email, $pass)
    {
        //query insert
        $query = 'SELECT COUNT(cpf) AS valida FROM cliente WHERE email = ? AND senha = ?';
        if($resultSttmt = mysqli_prepare(self::$conn, $query)){
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */

            //bind param
            mysqli_stmt_bind_param($resultSttmt,'ss', $email, $pass);
            mysqli_stmt_execute($resultSttmt);
            $result = mysqli_stmt_get_result($resultSttmt);
            $rows = mysqli_fetch_assoc($result);

            if($rows['valida'] == 1)
            {
                 //query insert
                $query = 'UPDATE cliente SET last_login = ? WHERE email = ?';
                // Prepare a query for execution
                if($resultSttmt = mysqli_prepare(self::$conn, $query))
                {
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

                    echo "<script>console.log('Conexão bem sucedida' );</script>";

                    // Close statement
                    mysqli_stmt_close($resultSttmt);
                    // Close connection
                    mysqli_close(self::$conn);
                    //verificar se foi cadastrado
                    return 1;
                }
            }
            else{
                //echo "Error! Login not successfully.";

                // Close statement
                mysqli_stmt_close($resultSttmt);
                // Close connection
                mysqli_close(self::$conn);
                //verificar se foi cadastrado
                echo "<script>console.log('Não foi possível realizar o  login. Erro: ".mysqli_connect_error()."' );</script>";
                return 0;
            }
        }
        else{
            //echo "Error! Login not successfully.";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            //verificar se foi cadastrado
            echo "<script>console.log('Não foi possível realizar o  login. Erro: ".mysqli_connect_error()."' );</script>";
            return 0;
        }
    }

    function requestDadosUser($email){

        //query insert
        $query = 'SELECT cpf, nome, data_nasc, telefone, endereco, 
        cep, cidade, estado, caminho_foto_perfil, avaliacao  
        FROM cliente WHERE email = ?';
        // Prepare a query for execution
        if($resultSttmt = mysqli_prepare(self::$conn, $query)){
            /*
                Character	Description
                i	corresponding variable has type integer
                d	corresponding variable has type double
                s	corresponding variable has type string
                b	corresponding variable is a binary (such as image, PDF file, etc.)
            */
            //bind param
            mysqli_stmt_bind_param($resultSttmt,'s', $email);

            mysqli_stmt_execute($resultSttmt);

            echo "request successfully.";

            $result = mysqli_stmt_get_result($resultSttmt);
 
            $rows = mysqli_fetch_assoc($result);

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            //verificar se foi cadastrado
            return $rows;
        }
        else{
            echo "<script>console.log('Não foi possível realizar a requsição de dados' );</script>";

            // Close statement
            mysqli_stmt_close($resultSttmt);
            // Close connection
            mysqli_close(self::$conn);
            return -1;
        }
    }

    /********* PRODUTO ********* */ 


    // public function delete()
    // {
    // }

    // public function update()
    // {
    // }

    // public function select()
    // {
    // }
}
