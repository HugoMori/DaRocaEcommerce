<?php
error_reporting(E_ALL ^ E_WARNING); 
session_start();
// session_start();
// $_SESSION['mensagem'] = "<div class='alert alert-success'>Versão e Revisão cadastrada    com sucesso!</div>";
// header("location: ../index.php");
// exit();
/*<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">*/
include 'db_conect.php';

$conn = new db_conect();

$cpf = '417.747.088-09';
$nome = '"Lucas de Oliveira Mori 2121 <br> 4"';
$data_nasc = '15-03-1994';
$tel = '17997399037';
$email = 'hugomori@live.com';
$senha = 'Teste10';
$end = 'Av.Nova Granada, 4852, Jrd.Vetorazzo';
$cep = '15040270';
$estado = 'SP';
$foto = 'teste';


echo "/".$_SESSION['log_id'];

// $tel = filter_var( $tel, FILTER_SANITIZE_STRING);
// $tel = preg_replace("/[^0-9]+/", "", $tel);
// $tel = "(".substr($tel, 0, 2).")".substr($tel, 2, 5)."-".substr($tel, 6);
// echo "<br>".$tel;


// $result = $conn->insertCliente($cpf , $nome, $data_nasc, $tel, $email, 
// $senha, $end, $cep, $estado, null);
// if (!$result) {
//     echo "Ocorreu um erro!\n";
//     exit;
// }
// else{
//     echo "Inserido";
// }

// echo $conn->insertCliente($cpf, $nome, $data_nasc, $tel, $email, 
// $senha, $end, $cep, $estado, null);

// $cep = filter_var( $cep, FILTER_SANITIZE_STRING);
// $cep = preg_replace("/[^0-9]+/", "", $cep);
// $cep = substr($cep, 0, 5)."-".substr($cep, 5);
// echo "<br>".$cep;

// $cpf = filter_var( $cpf, FILTER_SANITIZE_STRING);
// $cpf = preg_replace("/[^0-9]+/", "", $cpf);
// $cpfResult = substr($cpf, 0, 3).".".substr($cpf, 3, 3).".".substr($cpf, 6, 3)."-".substr($cpf, 9);
// echo "<br>".$cpfResult;

// $end = filter_var( $end, FILTER_SANITIZE_STRING);
// $end = preg_replace("/[^a-zA-Z0-9,.]+/", " ", $end);
// echo '<br>'.$end;


// $nome = filter_var ( $nome, FILTER_SANITIZE_STRING);

// if($nome == preg_replace("/[^a-zA-Z]+/", " ", $nome)){
//     echo 1;
// }
// $nome = filter_input(INPUT_POST, 'nome_user', FILTER_SANITIZE_STRING);
// $res = preg_replace("/[^a-zA-Z]+/", " ", $res);
// echo '<br>'.$nome;
// echo '<br>'.preg_replace("/[^a-zA-Z]+/", " ", $nome);

// $endereco = filter_input(INPUT_POST, 'endereço_user', FILTER_SANITIZE_STRING);
// $cidade = filter_input(INPUT_POST, 'cidade_user', FILTER_SANITIZE_STRING);
// $cep = filter_input(INPUT_POST, 'cep_user', FILTER_SANITIZE_STRING);
// $tel = filter_input(INPUT_POST, 'tel_user', FILTER_SANITIZE_STRING);
// $email = filter_input(INPUT_POST, 'email_user', FILTER_SANITIZE_EMAIL);
// $password = filter_input(INPUT_POST, 'senha_user', FILTER_SANITIZE_STRING);



// $query = 'SELECT * FROM cliente';
// $result = $conn->selectCustom($query);
// while ($row = pg_fetch_assoc($result)) {
//         echo $row['cpf'];
//         echo $row['nome'];
//         echo $row['email'];
//     }



// $result = $conn->loginCliente($email, $senha);
// var_dump($result['cpf']);

// $conn->insertCliente($cpf, $nome, $data_nasc, $tel, $email, 
// $senha, $end, $cep, $estado, $foto);

// $conn->updateCliente($cpf, $tel, $email, 
// $senha, $end, $cep, $estado, $foto);

// $result = pg_query($conn, "SELECT * FROM cliente");
// if (!$result) {
//     echo "Ocorreu um erro!\n";
//     exit;
// }

// while ($row = pg_fetch_assoc($result)) {
//     echo $row['cpf'];
//     echo $row['nome'];
//     echo $row['email'];
// }


//pg_close($conn);

?>