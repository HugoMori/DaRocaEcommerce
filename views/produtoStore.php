<?php
/* esse bloco de código em php verifica se existe a sessão aberta, se sim, o
 * usuário continua na pagina, se não, se ele  tentar burlar o acesso, 
 * redireciona para o index*/
session_start();

//verifica se há alguma sessão vazia, se sim, apaga ela e vai para o index, 
//se estiver com algum dado, ou seja, se o usuário estiver logado, puxará os 
//dados do usuário através do request
if ((!isset($_SESSION['log_id']) == true)) {
  unset($_SESSION['log_id']);
  header("Location: ../views/login.php");
}

//inclui o php que contém a conexão com o banco
include '../controller/controlRequest.php';
$conn = new controlRequest();
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

        <!-- Formulário -->
        <form class="form-inline">
          <input type="text" class="form-control" placeholder="Buscar no Da Roça">
          <button class="btn btn-outline-success"><i class="fas fa-search"></i></button>
        </form>
        <!-- /Formulário -->

        <!-- carrinho -->
        <div class="dropdown">
          <a href="#" class="car_button" data-toggle="dropdown">
            <i id="carrinho_icon" class="fa fa-shopping-cart"></i><br>
          </a>
          <div class="dropdown-menu">
            <a id="total" class="dropdown-item" href="#">R$ 0</a>
            <a id="checkout" class="dropdown-item" href="#">Checkout</a>
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
              <ul id="backTree" class="breadcrumb-tree">
                <li><a href="javascript:history.back()">Voltar</a></li>
              </ul>
              <ul id="backTree" class="breadcrumb-tree">
                <li>&nbsp;|&nbsp;</li>
              </ul>
              <ul class="breadcrumb-tree">
                <li><a href="#">Categoria</a></li>
              </ul>
              <ul class="breadcrumb-tree">
                <li>></li>
              </ul>
              <ul class="breadcrumb-tree">
                <li><a href="#">Nome chave do produto</a></li>
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
          <div class="container basic-container" style="max-width: none;">
            <!-- row -->
            <div class="d-flex flex-row">
              <div class="col-md-7">

                <!-- ROW imagens -->
                <div class="content">

                  <div class="cover">
                    <img id="imgCover" class="img-fluid" src="../img/veg_fruits/brocolis.jpg" alt="">
                  </div>
                  <!-- /cover -->

                  <ul class="thumbs">
                    <li><img class="img-fluid" src="../img/veg_fruits/brocolis.jpg" alt="" onclick="changePhoto(this)"></li>
                    <li><img class="img-fluid" src="../img/veg_fruits/maca.jpg" alt="" onclick="changePhoto(this)"></li>
                    <li><img class="img-fluid" src="../img/veg_fruits/cenoura.jpg" alt="" onclick="changePhoto(this)"></li>
                    <li><img class="img-fluid" src="../img/veg_fruits/alface.jpg" alt="" onclick="changePhoto(this)"></li>
                  </ul>
                  <!-- /thumbs -->

                </div>
                <!-- /ROW imagens -->

                <table class="table table-light">
                  <tbody>
                    <th>Mais anúncios do vendedor</th>
                    <tr>
                      <td>produto1</td>
                      <td>produto2</td>
                    </tr>
                  </tbody>
                </table>

                <table class="table table-light">
                  <tbody>
                    <th>Características do produto</th>
                    <tr>
                      <td>Categoria</td>
                    </tr>
                    <tr>
                      <td>Compra mínima</td>
                    </tr>
                    <tr>
                      <td>Compra por ?</td>
                    </tr>
                    <tr>
                      <td>data validade</td>
                    </tr>
                  </tbody>
                </table>

                <table class="table table-light">
                  <tbody>
                    <th>Descrição</th>
                    <tr>
                      <td></td>
                    </tr>
                  </tbody>
                </table>

                <table class="table table-light">
                  <tbody>
                    <th>Dúvidas e perguntas</th>
                    <tr>
                      <td>Botão entrar em contato com o vendedor</td>
                    </tr>
                  </tbody>
                </table>

              </div>

              <div class="col-md-5">

                <table class="table table-light" style="border: 1px solid rgba(0,0,0,.2);">

                  <tbody>
                    <tr>
                      <td class="smallInfos">Visualizações</td>
                    </tr>
                    <tr>
                      <td>Nome do produto</td>
                    </tr>
                    <tr>
                      <td>Avaliação</td>
                    </tr>
                    <tr>
                      <td class="smallInfos">Num compras</td>
                    </tr>
                    <tr>
                      <td>Preço</td>
                    </tr>
                    <tr>
                      <td>input qntd</td>
                      <td class="smallInfos">disponivel</td>
                    </tr>
                    <tr>
                      <td>Adicionar ao carrinho</td>
                    </tr>
                    <tr>
                      <td>Comprar</td>
                    </tr>
                  </tbody>
                </table>

                <table class="table table-light">
                  <tbody>
                    <th>Informações do vendedor</th>
                    <tr>
                      <td>Nome do vendedor</td>
                    </tr>
                    <tr>
                      <td class="smallInfos">número de vendas totais</td>
                    </tr>
                    <tr>
                      <td>Avaliação</td>
                    </tr>
                    <tr>
                      <td class="smallInfos">Cidade, estado</td>
                    </tr>
                  </tbody>
                </table>
              </div>

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