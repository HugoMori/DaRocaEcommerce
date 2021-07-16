<?php 
session_start();
// include 'db_conect.php';
//     $name_prod = filter_input(INPUT_POST, 'nome_Produto', FILTER_SANITIZE_STRING);
//     $preco_prod = filter_input(INPUT_POST, 'preco_Produto', FILTER_SANITIZE_NUMBER_FLOAT);
//     $qntd_prod = filter_input(INPUT_POST, 'qntd_Disponivel', FILTER_SANITIZE_NUMBER_FLOAT);
//     $categoria_prod = filter_input(INPUT_POST, 'categorias_produto', FILTER_SANITIZE_NUMBER_INT);
//     $tipo_venda_prod = filter_input(INPUT_POST, 'opcaoVenda_Produto', FILTER_SANITIZE_NUMBER_INT);
//     $qntd_min_prod = filter_input(INPUT_POST, 'qntd_Minima', FILTER_SANITIZE_NUMBER_FLOAT);
//     $descricao = filter_input(INPUT_POST, 'descricao_Produto', FILTER_SANITIZE_STRING);

//     // limpar o nome do produto para não correr risco de SQLI ou partes html
//     $name_prod = filter_var($name_prod, FILTER_SANITIZE_STRING);
//     // limpar o valor do produto p/conter apenas números
//     $preco_prod = filter_var($preco_prod, FILTER_SANITIZE_NUMBER_FLOAT);
//     $preco_prod = $preco_prod/100.0;
//     // limpar a qntd disponivel do produto p/conter apenas números
//     $qntd_prod = filter_var($qntd_prod, FILTER_SANITIZE_NUMBER_FLOAT);
//     // limpar a categoria do produto p/conter apenas números
//     $categoria_prod = filter_var($categoria_prod, FILTER_SANITIZE_NUMBER_INT);
//     // limpar o tipo de venda do produto p/conter apenas números
//     $tipo_venda_prod = filter_var($tipo_venda_prod, FILTER_SANITIZE_NUMBER_INT);
//     // limpar a qntd minima de venda do produto p/conter apenas números
//     $qntd_min_prod = filter_var($qntd_min_prod, FILTER_SANITIZE_NUMBER_FLOAT);
//     // limpar a descrição do produto para não correr risco de SQLI ou partes html
//     $descricao = filter_var($descricao, FILTER_SANITIZE_STRING);

//     $preco_prod = $preco_prod/100.0;
// echo $name_prod."<br>".$preco_prod."<br>".$qntd_prod."<br>".$categoria_prod."<br>".$tipo_venda_prod."<br>".$qntd_min_prod."<br>".$descricao;
    
// $conn = new db_conect();
// $produtor = $_SESSION['log_id'];
// $query = "SELECT codigo FROM produto 
//             WHERE produto.produtor_fk = '".$produtor."' AND produto.nome = '".$name_prod."';";

//             //CPF check
//             $result = $conn->selectCustom($query);
//             $row = mysqli_fetch_assoc($result);
//             echo "<br>".$row['codigo'];

//             $conn->insertImg_produto('teste', $row['codigo']);

echo "ERRO!";
?>