<?php
/* esse bloco de código em php verifica se existe a sessão aberta, se sim, o
 * usuário continua na pagina, se não, se ele  tentar burlar o acesso, 
 * redireciona para o index*/
session_start();
//verifica se há alguma sessão vazia, se sim, apaga ela e vai para o index, 
//se estiver com algum dado, ou seja, se o usuário estiver logado, puxará os 
//dados do usuário através do request
if ((!isset($_SESSION['log_id']) == true)) {
  header("Location: ../controller/logout.php");
} else {
  //inclui o php que contém a conexão com o banco e o php de request/send dados
  include '../controller/controlRequest.php';
  $conn = new controlRequest();

  $carrinho = $conn->requestDadosCarrinho($_SESSION['log_id']);

  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <!-- FontAwesome-->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <!-- css -->
  <link rel="stylesheet" type="text/css" href="../css/stylesCommons.css">
  <link rel="stylesheet" type="text/css" href="../css/meus_anuncios.css">
  <link rel="stylesheet" type="text/css" href="../css/carrinho.css">
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
              echo '<a class="mySidenav-link" href="href="../views/compras.php"><i class="far fa-list-alt"> Meus pedidos</i></a>';
            } else {
              echo '<a class="mySidenav-link" href="href="../views/cadastro.php"><i class="far fa-edit"> Cadastrar-se</i></a>';
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

        <!-- Formulário -->
        <form class="form-inline">
          <input type="text" class="form-control" placeholder="Buscar no Da Roça">
          <button class="btn btn-outline-success"><i class="fas fa-search"></i></button>
        </form>
        <!-- /Formulário -->

        <!-- carrinho -->
        <div class="dropdown">
          <a href="#" class="car_button" data-toggle="dropdown">
            <i id="carrinho_icon" class="fa fa-shopping-cart"></i>
            <span class="badge badge-success"><?php echo $carrinho_QntdProdutos_Valor['qntd_produtos']; ?></span><br>
          </a>
          <div class="dropdown-menu">
            <a id="total" class="dropdown-item" href="#">R$ <?php echo $carrinho_QntdProdutos_Valor['total']; ?></a>
            <a id="checkout" class="dropdown-item" href="../views/carrinho.php">Checkout</a>
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
              <h3 class="breadcrumb-header">Carrinho</h3>
              <ul class="breadcrumb-tree">
                <li><a href="javascript:history.back()">Voltar</a></li>
              </ul>
            </div>
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
          <div class="container basic-container">

            <?php if ($carrinho_QntdProdutos_Valor['qntd_produtos'] > 0) { ?>
              <!-- row -->
              <div class="d-flex flex-row">

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" id="form_carrinho">

                  <table class="table table-bordered table-hover">
                    <!-- corpo da tabela -->
                    <tbody>
                      <?php while ($carrinhoDados = mysqli_fetch_assoc($carrinho)) { ?>

                        <!-- table row -->
                        <tr>

                          <!-- checkbox produto -->
                          <td class="checkBox-td">
                            <div class="form-check">
                              <!-- input checkBOX com o codigo do carrinho -->
                              <input class="form-check-input" id="carrinho<?php echo $carrinhoDados['carrinho'];?>" 
                              type="checkbox" name="carrinho_codigo[]" value="<?php echo $carrinhoDados['carrinho']; ?>"
                              onchange="onCheckCarrinho(this)">

                            </div>
                          </td>
                          <!-- /checkbox produto -->

                          <!-- divisão foto -->
                          <td class="tb_foto">
                            <!-- foto do anuncio -->
                            <img id="foto_anuncio" class="img-fluid" 
                            src="<?php if ($carrinhoDados['foto'] == "" || $carrinhoDados['foto'] == null) {
                              echo '../img/veg_fruits/brocolisCartoonSurprised.svg';
                            } else {
                              echo $carrinhoDados['foto'];
                            } ?>" alt="Foto do produto anúnciado">

                          </td>
                          <!-- /divisão foto -->

                          <!-- divisão infos prod -->
                          <td class="infosAnuncio">
                             <!-- Nome do produto -->
                            <p><strong>Produto:</strong> <?php echo $carrinhoDados['produto']; ?></p>
                             <!-- categoria do produto -->
                            <p><strong>Categoria:</strong> <?php echo $conn->categoriaProduto($carrinhoDados['categoria']); ?></p>
                             <!-- Subtotal do valor desse produto (qntd * preco) -->
                            <p><strong>Sub-total: </strong><span id="subTotal">R$  
                            <span id="precoTotalProd<?php echo $carrinhoDados['carrinho'];?>">
                              <?php echo round(($carrinhoDados['preco'] * $carrinhoDados['qntd']), 2); ?>
                            </span></span></p>
                             <!-- input hidden com o valor do produto -->
                            <input type="hidden" id="precoProd<?php echo $carrinhoDados['carrinho'];?>" name="precoProd" value="<?php echo $carrinhoDados['preco']; ?>" />
                             <!-- Frete do produto -->
                            <p><strong>Frete:</strong> A combinar</p>
                          </td>
                          <!-- /divisão infos prod -->

                          <!-- informações do vendedor -->
                          <td>

                            <!-- Nome do vendedor -->
                            <p><strong>Vendedor:</strong></p>
                            <p><?php echo $carrinhoDados['vendedor']; ?></p>
                            <!-- Cidade do vendedor -->
                            <p><?php echo $carrinhoDados['cidade'].", ".$carrinhoDados['estado']; ?></p>
                          
                          </td>
                          <!-- /informações do vendedor -->

                          <!-- Quantidade solicitada do produto -->
                          <td>
                            <div class="form-group">

                              <p><strong>Quantidade solicidata:</strong></p>
                              <!-- Input c/ a quantidade solicitada do produto -->
                              <input id="qntdDesejada<?php echo $carrinhoDados['carrinho'];?>" class="form-control qntdDesejada" type="number" min="0" 
                              step="<?php echo $conn->stepVendaProduto($carrinhoDados['tipo_venda']); ?>" 
                              name="qntd_desejada" placeholder="0.0" value="<?php echo $carrinhoDados['qntd']; ?>" 
                              required onchange="corrigeValor(this)" />
                              <!-- informação do tipo de venda (KG/Un/Dúzia/...) -->
                              <span><?php echo " ".$conn->tipoVendaProduto($carrinhoDados['tipo_venda']); ?></span>
                            
                            </div>
                          </td>
                          <!-- /Quantidade solicitada do produto -->

                          <!-- botão de alterar remover produto do carrinho -->
                          <td class="buttonsTd">

                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                            enctype="multipart/form-data">
                              <a class="nav-link" href="">
                                <input type="hidden" name="carrinhoCod" value="<?php echo $carrinhoDados['carrinho']; ?>" />
                                <button type="submit" value="Remover" class="btn btn-danger btn-anuncio">Remover produto<br>do carrinho</button>
                                <!-- dropdown menu pedindo senha -->
                              </a>
                            </form>

                          </td>
                          <!-- /botão de alterar remover produto do carrinho -->

                        </tr>

                      <?php } ?>
                      <!-- /While de dados do carrinho -->
                    </tbody>
                  </table>
                  <!-- /Table do carrinho -->

                  <!-- Table pagamento -->
                  <table class="col-md-4" id="pagamento">
                    <tbody>
                      <tr>
                        <td>
                          <!-- Valor total dos produtos selecionados -->
                          <h2>Total:</h2><h3>R$ <span id="valorTotal">0</span></h3>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <!-- Botão de submeter a compra -->
                          <button type="submit" value="finalizarCompra" class="btn btn-success btn-anuncio">Finalizar compra</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- /Table pagamento -->

                </form>
                <!-- /form -->
              </div>
              <!-- /row -->
            <?php } ?>
            <!-- /if ($numRows > 0) -->

            <!-- Pagina se não houver produtos no carrinho -->
            <?php if ($carrinho_QntdProdutos_Valor['qntd_produtos'] <= 0) { ?>

              <div class="col-md-12 capa">
                <!-- todo espaço da tela -->
                <h1>Ops!!</h1>
                <h3>
                  Você ainda não realizou nenhum anúncio.<br>
                </h3>
                <img class="img-fluid" src="../img/logo/brocolisCartoonSurprised.svg" alt="Logo Da Roça">
                <h4>
                  Clique no botão para realizar seu anúncio.<br>
                </h4>
                <a href="../views/cadastro_produto2.php">
                  <button type="button" class="btn btn-success btn-lg">Anunciar produto</button>
                </a>
                <!--capa -->
              </div>
              <!-- /row -->
            <?php } ?>
            <!-- if ($numRows <= 0) -->

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
          <div class="col-md-2 colBuscados1">
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
          <div class="col-md-2 colBuscados2">
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