<?php
error_reporting(E_ERROR | E_PARSE);
/* esse bloco de código em php verifica se existe a sessão aberta, se sim, o
 * usuário continua na pagina, se não, se ele  tentar burlar o acesso, 
 * redireciona para o index*/
session_start();

//verifica se há alguma sessão vazia, se sim, apaga ela e vai para o index, 
//se estiver com algum dado, ou seja, se o usuário estiver logado, puxará os 
//dados do usuário através do request
if (count($_GET) > 0) {
  //inclui o php que contém a conexão com o banco
  include '../controller/controlRequest.php';
  $conn = new controlRequest();

  $codigo = $_GET['produto'];
  //Requisição dos dados do produto 
  $resultDadosProduto = $conn->requestDadosProduto($codigo);
  //Puxar os dados do carrinho DropDown
  $carrinhoDropDow = $conn->requestDadosCarrinho($_SESSION['log_id']);
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();

  if ($resultDadosProduto != 0) {

    //Puxo os dados requisitados do produto p/uma row
    $DadosProduto = mysqli_fetch_assoc($resultDadosProduto);
    // requisição das fotos do produto
    $resultFotosProduto = $conn->requestFotosProduto($codigo);

    //passo a img padrão para a variavel imagem
    if ($resultFotosProduto == 0) {
      header("Location: ../views/meusAnuncios.php");
    }  

    //verificar se foi realizar algum $_POST
    if(count($_POST) > 0){

      //Só é possível realizar compra logado
      //verifica se está logado
      if ((session_status() !== PHP_SESSION_NONE) && isset($_SESSION['log_id'])) {

        //verifica se o submit foi do carrinho
        if (isset($_POST['carrinho'])) {
          $result = $conn->insertUpdateFromPage($codigo, $_POST['qntd_compra']);
          if($result != 0){
            header("Location: ../views/carrinho.php");
          }
        }
        //se não, verifica se foi da compra
        else{
          if(isset($_POST['compra'])){
            $result = $conn->realizarCompra($codigo, $_POST['qntd_compra'], -1);
            if($result != 0){
              header("Location: ../views/minha_conta.php");
            }
          }
        }

      }
      //fazer login
      else{
        header("Location: ../controller/logout.php");
      }
    }
    // /count($_POST) > 0

  }
  //Se não tiver puxado nenhum dado do produto 
  else {
    //esse produto não existe mais, pagina de erro
    header("Location: ../views/meusAnuncios.php");
  }
}
//pagina de erro 
else {
  //redireciona para a página de erro
  header("Location: ../views/meusAnuncios.php");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <!-- FontAwesome-->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <!-- css -->
  <link rel="stylesheet" type="text/css" href="../css/stylesCommons.css">
  <link rel="stylesheet" type="text/css" href="../css/produtoStore.css">
  <!-- SiderBar -->
  <link rel="stylesheet" type="text/css" href="../css/siderBar.css">
  <!-- normalize -->
  <link rel="stylesheet" type="text/css" href="../css/normalize.css">

  <!-- title -->
  <title>Da Roça </title>
  <link rel="icon" href="../img/logo/DaRocaBlack.png">
</head>

<body>

  <header>
    <!-- fixed bar -->
    <nav class="navbar navbar-expand-md navbar-light fixed-top navbar-style">
      <!-- Container -->
      <div class="container">

        <!-- Logo -->
        <a href="../index.php" class="navbar-brand">
          <img src="../img/logo/DaRoca.svg" alt="logo Da Roça" class="img-fluid d-none d-md-block">
        </a>
        <!-- /Logo -->

        <!-- Menu Toogle -->
        <button class="navbar-toggler" data-toggle="collapse" onclick="openNav()">
          <i class="fas fa-bars text-white"></i>
        </button>
        <!-- /Menu Toogle -->

        <!-- SideBar menu-->
        <div id="mySidenav" class="sidenav">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
          <div class="row">
            <?php
            if (isset($_SESSION['log_id'])) {
              echo '<a class="mySidenav-link" href="../views/compras.php"><i class="far fa-list-alt"> Meus pedidos</i></a>';
            } else {
              echo '<a class="mySidenav-link" href="../views/cadastro.php"><i class="far fa-edit"> Cadastrar-se</i></a>';
            }
            ?>
          </div>
          <div class="row">
            <?php
            if (isset($_SESSION['log_id'])) {
              echo '<a class="mySidenav-link" href="../views/minha_conta.php"><i class="fas fa-user"> Minha conta</i></a>';
            } else {
              echo '<a class="mySidenav-link" href="../views/login.php"><i class="fas fa-sign-in-alt"> Entrar</i></a>';
            }
            ?>
          </div>
        </div>
        <!-- /SideBar menu-->

        <!-- SearchBar -->
        <form method="get" action="../views/produtos.php" class="form-inline" enctype="multipart/form-data">
          <input name="prod" type="text" class="form-control" placeholder="Buscar no Da Roça">
          <button type="submit" class="btn btn-outline-success"><i class="fas fa-search"></i></button>
        </form>
        <!-- /SearchBar -->

        <!-- carrinho -->
        <div class="dropdown">
          <a href="#" class="car_button" data-toggle="dropdown">
            <i id="carrinho_icon" class="fa fa-shopping-cart"></i>
            <span class="badge badge-success"><?php echo $carrinho_QntdProdutos_Valor['qntd_produtos']; ?></span><br>
          </a>
          <div class="dropdown-menu">
            
            <table class="table table-light" style="border-bottom: 1px dashed black;">
                <tbody>
                  <th>Produto</th>
                  <th>Quantidade</th>
                  <th>Custo</th>
                  <?php while ($carrinhoDados = mysqli_fetch_assoc($carrinhoDropDow)) { ?>
                  <tr>
                    <td>
                      <?php if(strstr($carrinhoDados['produto'], ' ', true)){
                      echo strstr($carrinhoDados['produto'], ' ', true)." ";
                      }else{ echo $carrinhoDados['produto'];}?>
                    </td>
                    <td>
                      <?php echo $carrinhoDados['qntd']." ".$conn->tipoVendaProduto($carrinhoDados['tipo_venda']); ?>
                    </td>
                    <td style="padding-left: 2px; padding-right: 2px;">
                      <?php echo "R$ ".round(($carrinhoDados['qntd']*$carrinhoDados['preco']),2); ?>
                    </td>
                  </tr>
                  <?php } ?> 
                </tbody>
              </table>
            
            <table class="table table-light">
              <tbody>
                <tr>
                  <td>
                    <strong>Total:</strong>
                  </td>
                  <td style="text-align-last: right;">
                    R$ <?php echo $carrinho_QntdProdutos_Valor['total']; ?>
                  </td>
                </tr>
              </tbody>
            </table>

            <a id="checkout" class="dropdown-item" href="../views/carrinho.php">Pagar</a>

          </div>
        </div>
        <!-- /carrinho -->

        <!-- Nav-principal -->
        <div id="nav-principal" class="collapse navbar-collapse">
          <ul class="navbar-nav ml-auto">

            <li class="nav-item divisor"></li>
            <li class="nav-item">
              <?php
              if (isset($_SESSION['log_id'])) {
                echo '<a class="nav-link" href="#"><i class="far fa-list-alt">&nbsp&nbspMeus pedidos</i></a>';
              } else {
                echo '<a class="nav-link" href="../views/cadastro.php"><i class="far fa-edit">&nbsp&nbspCadastrar-se</i></a>';
              }
              ?>
            </li>

            <li class="nav-item">
              <?php
              if (isset($_SESSION['log_id'])) {
                echo '<a class="nav-link" href="../views/minha_conta.php"><i class="fas fa-user">&nbsp&nbspMinha conta</i></a>';
              } else {
                echo '<a class="nav-link" href="../views/login.php"><i class="fas fa-sign-in-alt">&nbsp&nbspEntrar</i></a>';
              }
              ?>
            </li>

          </ul>
        </div>
        <!-- Nav-principal -->

      </div><!-- /container -->
    </nav><!-- /Nav -->
  </header>

  <!-- CORPO DO SITE -->

  <!-- Flex item pode usar justify para alinhar-->
  <div class="corpo d-flex">
    <div class="container-fluid main">

      <!-- BREADCRUMB -->
      <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
          <!-- row -->
          <div class="row">
            <div class="col-md-12">

              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="javascript:history.back()">Voltar</a>
                </li>
                <li class="breadcrumb-item isDisabled">
                  <a href="#"><?php echo $conn->categoriaProduto($DadosProduto['categoria']); ?></a>
                </li>
                <li class="breadcrumb-item">
                  <?php if(strstr($DadosProduto['produto'], ' ', true)){ $nomeProd = strstr($DadosProduto['produto'], ' ', true);}
                    else{ $nomeProd = $DadosProduto['produto'];} ?>
                  <a href="../views/produtos.php?prod=<?php echo $nomeProd;?>"><?php echo $nomeProd;?></a>
                </li>
              </ol>

            </div>
            <!-- col -->
          </div>
          <!-- /row -->
        </div>
        <!-- /container -->
      </div>
      <!-- /BREADCRUMB -->

      <section>
        <!-- SECTION -->
        <div class="basic-section">
          <!-- container -->
          <div class="container basic-container" style="max-width: none;">
            <!-- row -->
            <div class="d-flex flex-row">

              <!-- coluna com as fotos/informações do produto/descrição -->
              <div class="col-md-7">

                <!-- ROW imagens -->
                <div class="content">

                  <!-- cover -->
                  <div class="cover">
                    <!-- Foto principal (grande)-->
                    <img id="imgCover" class="img-fluid" src="<?php echo $DadosProduto['foto']; ?>" alt="">
                  </div>
                  <!-- /cover -->

                  <!-- thumbs -->
                  <ul class="thumbs">
                    <!-- While com as fotos dos produtos em miniatura -->
                    <?php while ($fotosProduto = mysqli_fetch_assoc($resultFotosProduto)) { ?>
                      <li><img class="img-fluid" src="<?php echo $fotosProduto['caminho_foto']; ?>" alt="" onclick="changePhoto(this)"></li>
                    <?php } ?>
                  </ul>
                  <!-- /thumbs -->

                </div>
                <!-- /ROW imagens -->

                <!-- Tabela com as informações do produto -->
                <table class="table table-light">
                  <tbody>
                    <th>Características do produto</th>
                    
                    <!-- Categoria do produto (1:Frutas 2:Verduras 3:Legumes 4:Bebidas 5:Frios 6:Especiarias) -->
                    <tr>
                      <td>
                        <!-- Foto categoria -->
                        <span class="caracteristica-span">
                          <img class="img-fluid" src="../img/logo/categoria.svg" alt="categoria imagem">
                        </span>
                        <span>
                          Categoria:&nbsp;<strong><?php echo $conn->categoriaProduto($DadosProduto['categoria']); ?></strong>
                        </span>
                      </td>
                    </tr>

                    <!-- Quantidade mínima que o produtor optou por vender por cliente -->
                    <tr>
                      <td>
                        <!-- Foto qntd minima -->
                        <span class="caracteristica-span">
                          <img class="img-fluid" src="../img/logo/qntd_min.svg" alt="categoria imagem">
                        </span>
                        <span>
                          Mínima quantidade comprada:&nbsp;
                          <strong>
                            <?php echo $DadosProduto['qntd_min_vendida'] . " " . $conn->tipoVendaProduto($DadosProduto['tipo_venda']); ?>
                          </strong>
                        </span>
                      </td>
                    </tr>

                    <!-- Datas de fabricação e validade -->
                    <tr id="tr-datas">
                      <td class="td-left">
                        <span class="caracteristica-span">
                          <img class="img-fluid" src="../img/logo/calendar.svg" alt="categoria imagem">
                        </span>
                        <span>Data de <?php echo $conn->tipoFabricacao($DadosProduto['categoria']); ?> : 
                          <strong><time><?php echo $conn->convertDate($DadosProduto['data_producao']); ?></time></strong>
                        </span>
                      </td>
                      <td class="td-right">
                        <span class="caracteristica-span">
                          <img class="img-fluid" src="../img/logo/calendar.svg" alt="categoria imagem">
                        </span>
                        <span>Data de validade: <strong><time><?php echo $conn->convertDate($DadosProduto['data_validade']); ?></time></strong>
                        </span>
                      </td>
                    </tr>

                  </tbody>
                </table>
                <!-- /Tabela com as informações do produto -->

                <!-- Tabela com descriçaõ -->
                <table class="table table-light">
                  <tbody>
                    <th>Descrição</th>
                    <tr>
                      <td><?php echo $DadosProduto['descricao']; ?></td>
                    </tr>
                  </tbody>
                </table>
                <!-- /Tabela com descriçaõ -->

                <!-- Tabela de perguntas -->
                <table class="table table-light">
                  <tbody>
                    <th>Dúvidas e perguntas</th>
                    <tr>
                      <td>Botão entrar em contato com o vendedor</td>
                    </tr>
                  </tbody>
                </table>
                <!-- /Tabela de perguntas -->

              </div>
              <!-- col md 7 -->

              <!-- Coluna com formulário p/compra ou add carrinho e quantidade do produto-->
              <div class="col-md-5">

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?produto=".$codigo; ?>" enctype="multipart/form-data">

                  <!-- Tabela com os dados do produto (Preço, visualizações, compras, qntd disponivel, opções de compra)-->
                  <table class="table table-light infos-compra" style="border: 1px solid rgba(0,0,0,.2);">

                    <tbody>

                      <!-- Visualizações -->
                      <tr>
                        <td class="smallInfos"><?php echo $DadosProduto['visualizacoes']; ?> visualizações</td>
                      </tr>

                      <!-- Nome do produto -->
                      <tr>
                        <td>
                          <h2><strong><?php echo $DadosProduto['produto']; ?></strong></h2>
                        </td>
                      </tr>

                      <!-- Avaliação do produto e numero de opiniões (número de vendas) -->
                      <tr>
                        <td>
                          <span class="aval-span">
                            <?php echo $conn->avaliacaoImg($DadosProduto['avaliacao_produto']); ?>
                          </span>
                          <span class="smallInfos">
                            &nbsp;<?php echo " " . $DadosProduto['num_vendas_produto'] . " opiniões"; ?>
                          </span>
                        </td>
                      </tr>

                      <!-- número de vendas do produto -->
                      <tr>
                        <td class="smallInfos">
                          <?php echo $DadosProduto['num_vendas_produto'] . " compras"; ?>
                        </td>
                      </tr>

                      <!-- Preço do produto -->
                      <tr>
                        <td>
                          <h3>R$
                            <strong id="preco" data-value="<?php echo $DadosProduto['preco']; ?>">
                              <?php echo $DadosProduto['preco']; ?>
                            </strong>&nbsp;/<?php echo $conn->tipoVendaProduto($DadosProduto['tipo_venda']); ?>
                          </h3>
                        </td>
                      </tr>

                      <!-- Frete do produto -->
                      <tr>
                        <td>
                          <span>
                            <img id="frete" class="img-fluid" src="../img/logo/frete.svg" alt="Frete imagem">
                          </span>
                          <span>
                            Frete:&nbsp;<strong>A combinar</strong>
                          </span>
                        </td>
                      </tr>

                      <!-- Seleção de quantidade desejada do produto -->
                      <tr>
                        <td class="td-left">
                          <div class="form-group">
                            <label for="qntdCompra">Quantidade:&nbsp;</label>
                            <input id="qntdCompra" class="form-control" type="number" 
                            step="<?php echo $conn->stepVendaProduto($DadosProduto['tipo_venda']); ?>" min="<?php echo $DadosProduto['qntd_min_vendida']; ?>" 
                            max="<?php echo $DadosProduto['qntd_disponivel']; ?>" name="qntd_compra" 
                            onchange="calcValorCompra()" value="<?php echo $DadosProduto['qntd_min_vendida']; ?>">
                            <span style="align-self: center;"><?php echo $conn->tipoVendaProduto($DadosProduto['tipo_venda']); ?></span>
                          </div>
                        </td>
                        <td class="smallInfos td-right" id="qntd-td">
                          <?php echo $DadosProduto['qntd_disponivel'] . " " . $conn->tipoVendaProduto($DadosProduto['tipo_venda']); ?> disponivel
                        </td>
                      </tr>

                      <!-- Valor total do produto (Atualizado por JS) -->
                      <tr>
                        <td>
                          <h4 id="ValorTotal">Total: R$
                            <?php echo round($DadosProduto['qntd_min_vendida'] * $DadosProduto['preco'], 2); ?>
                          </h4>
                        </td>
                      </tr>

                      <!-- Botão de add carrinho -->
                      <tr>
                        <td>
                          <div class="form-group">
                            <button type="submit" name="carrinho" class="btn btn-secondary">Adicionar ao carrinho</button>
                          </div>
                        </td>
                      </tr>

                      <!-- Botão de realizar compra -->
                      <tr>
                        <td>
                          <div class="form-group">
                            <button type="submit" name="compra" class="btn btn-success">Realizar compra</button>
                          </div>
                        </td>
                      </tr>

                    </tbody>
                  </table>
                  <!-- /Tabela com os dados do produto (Preço, visualizações, compras, qntd disponivel, opções de compra)-->

                </form>
                
                <!-- Tabela de informações do vendedor (Nome/avaliação/cidade) -->
                <table class="table table-light">
                  <tbody>
                    
                    <th>Informações do vendedor</th>

                    <!-- Nome do vendedor -->
                    <tr>
                      <td><?php echo $DadosProduto['vendedor']; ?></td>
                    </tr>

                    <!-- Vendas realizadas -->
                    <tr>
                      <td class="smallInfos">
                        <?php echo $DadosProduto['vendas_vendedor']; ?> vendas realizadas
                      </td>
                    </tr>

                    <!-- Avaliação do vendedor -->
                    <tr>
                      <td>
                        <p>Avaliação do vendedor:</p>
                        <span class="aval-span">
                          <?php echo $conn->avaliacaoImg($DadosProduto['vendedor_avaliacao']); ?>
                        </span>
                      </td>
                    </tr>

                    <!-- Cidade/Estado -->
                    <tr>
                      <td class="smallInfos">
                        <?php echo ($DadosProduto['cidade'].", ".$DadosProduto['estado']); ?>
                      </td>
                    </tr>

                  </tbody>
                </table>
                <!-- /Tabela de informações do vendedor (Nome/avaliação/cidade) -->

              </div>
              <!-- /col-md-5 -->

            </div>
            <!-- /row -->

          </div>
          <!-- /container -->
        </div>
        <!-- /SECTION -->
      </section>


    </div>
    <!-- /main -->
  </div>
  <!-- /corpo -->

  <!-- /CORPO DO SITE -->


  <footer>
    <div class="container">
      <div class="row">

        <div id="logoFooter" class="col-md-2">
          <img class="img-fluid" src="../img/logo/DaRoca.svg" alt="">
        </div>

        <div class="col-md-2">
          <h4>Company</h4>
          <ul class="navbar-nav">
            <li>
              <a href="../views/login.php">Entrar</a>
            </li>
            <li>
              <a href="../views/cadastro.php">Cadastre-se</a>
            </li>
            <li>
              <a href="">Sobre</a>
            </li>
            <li>
              <a href="">Contato</a>
            </li>
            <li>
              <a href="">Ajuda</a>
            </li>
          </ul>
        </div>
        <div class="col-md-2">
          <h4>Mais buscados</h4>
          <div class="col-md-2 colBuscados" style="float: left;">
            <ul class="navbar-nav">
              <li>
                <a href="">Frutas</a>
              </li>
              <li>
                <a href="">Verduras</a>
              </li>
              <li>
                <a href="">Bebidas</a>
              </li>
              <li>
                <a href="">Legumes</a>
              </li>
            </ul>
          </div>
          <div class="col-md-2 colBuscados" style="float: right;">
            <ul class="navbar-nav">
              <li>
                <a href="">Frios</a>
              </li>
              <li>
                <a href="">Especiarias</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-md-6">
          <ul>
            <li>
              <a href="" class="m-2">
                <img src="../img/midias/facebook.png" alt="">
              </a>
            </li>
            <li>
              <a href="" class="m-2">
                <img src="../img/midias/twitter.png" alt="">
              </a>
            </li>
            <li>
              <a href="" class="m-2">
                <img src="../img/midias/instagram.png" alt="">
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
  <script src="../js/functions.js"></script>
</body>

</html>