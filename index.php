<?php
session_start();
if (!isset($_SESSION['log_id'])) {
  unset($_SESSION['log_id']);
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
  <link rel="stylesheet" type="text/css" href="css/indexStyle.css">
  <link rel="stylesheet" type="text/css" href="css/stylesCommons.css">
  <!-- SiderBar -->
  <link rel="stylesheet" type="text/css" href="css/siderBar.css">
  <!-- normalize -->
  <link rel="stylesheet" type="text/css" href="css/normalize.css">

  <!-- title -->
  <title>Da Roça </title>
  <link rel="icon" href="img/logo/DaRocaBlack.png">
</head>

<body>

  <header>
    <!-- fixed bar -->
    <nav class="navbar navbar-expand-md navbar-light fixed-top navbar-style">
      <!-- Container -->
      <div class="container">

        <!-- Logo -->
        <a href="#" class="navbar-brand">
          <img src="img/logo/DaRoca.svg" alt="logo Da Roça" class="img-fluid d-none d-md-block">
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
                if(isset($_SESSION['log_id'])){
                  echo '<a class="mySidenav-link" href="href="views/compras.php"><i class="far fa-list-alt"> Meus pedidos</i></a>';
                }
                else{
                  echo '<a class="mySidenav-link" href="href="views/cadastro.php"><i class="far fa-edit"> Cadastrar-se</i></a>';
                }
              ?>
          </div>
          <div class="row">
            <?php 
              if(isset($_SESSION['log_id'])){
                echo '<a class="mySidenav-link" href="views/minha_conta.php"><i class="fas fa-user"> Minha conta</i></a>';
              }
              else{
                echo '<a class="mySidenav-link" href="views/login.php"><i class="fas fa-sign-in-alt"> Entrar</i></a>';
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
                if(isset($_SESSION['log_id'])){
                  echo '<a class="nav-link" href="#"><i class="far fa-list-alt">&nbsp&nbspMeus pedidos</i></a>';
                }
                else{
                  echo '<a class="nav-link" href="views/cadastro.php"><i class="far fa-edit">&nbsp&nbspCadastrar-se</i></a>';
                }
              ?>
            </li>

            <li class="nav-item">
                <?php 
                  if(isset($_SESSION['log_id'])){
                    echo '<a class="nav-link" href="views/minha_conta.php"><i class="fas fa-user">&nbsp&nbspMinha conta</i></a>';
                  }
                  else{
                    echo '<a class="nav-link" href="views/login.php"><i class="fas fa-sign-in-alt">&nbsp&nbspEntrar</i></a>';
                  }
                ?>
            </li>

          </ul>
        </div>
        <!-- Nav-principal -->

      </div><!-- /container -->
    </nav><!-- /Nav -->
  </header>

  <!-- Section intro -->
  <!-- Flex item pode usar justify para alinhar-->
  <section id="home" class="d-flex">
    <div class="container align-self-center">
      <div class="row">

        <!-- todo espaço da tela -->
        <div class="col-md-12 capa">

          <div id="carousel-home" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <h1>
                  No <strong>Da Roça</strong> você encontra produtos orgânicos<br>
                  e fresquinhos diretos da roça pra sua casa!<br>
                </h1>
                <img class="img-fluid" src="img/logo/DaRoca.svg" alt="Logo Da Roça">
              </div>

              <div class="carousel-item">
                <h1 id="cadastreText">
                  É produtor e gostaria de fornecer seus produtos?
                </h1>
                <p>Cadastre-se, é <strong>GRÁTIS!!!</strong></p>
                <a href="views/cadastro.php">
                  <img id="imgBtnVeg" class="img-fluid d-none d-md-block" src="img/logo/brocolisCartoon.svg" alt="Brocolis desenho">
                  <button class="btn btn-lg btn-custom btn-veg">Cadastre-se</button>
                </a>
              </div>

            </div>
            <a href="#carousel-home" class="carousel-control-prev" data-slide="prev">
              <i class="fas fa-angle-left fa-3x"></i>
            </a>
            <a href="#carousel-home" class="carousel-control-next" data-slide="next">
              <i class="fas fa-angle-right fa-3x"></i>
            </a>
          </div>

        </div>

      </div><!-- /row -->
    </div><!-- container -->
  </section><!-- /home -->


  <section id="categories" class="caixa">
    <div class="container">
      <div class="row">

        <!-- colunas fotos -->
        <div class="col-md-6">
          <!-- Frase -->

          <div class="row mb-4 mt-4">
            <!-- fotos produtos -->
            <div class="col-md-6">
              <img src="img/veg_fruits/alface.jpg" alt="" class="img-fluid d-none d-md-block veg-img">
            </div>
            <div class="col-md-6">
              <img src="img/veg_fruits/brocolis.jpg" alt="" class="img-fluid d-none d-md-block veg-img">
            </div>
          </div>

          <div class="row">
            <!-- fotos produtos -->
            <div class="col-md-6">
              <img src="img/veg_fruits/cenoura.jpg" alt="" class="img-fluid d-none d-md-block veg-img">
            </div>
            <!-- fotos produtos -->
            <div class="col-md-6">
              <img src="img/veg_fruits/maca.jpg" alt="" class="img-fluid d-none d-md-block veg-img">
            </div>
          </div>

        </div><!-- /colunas fotos -->

        <!-- Search bar e categorias -->
        <div id="searchCategories" class="col-md-6">
          <h2>Encontre fresquinho o que deseja!</h2><br>
          <!-- Formulário -->
          <form class="form-inline searchBarForm">
            <input type="text" class="form-control searchBarForm-input" placeholder="Buscar no Da Roça">
            <button class="btn btn-outline-success searchBarForm-btn"><i class="fas fa-search"></i></button>
          </form>
          <!-- /Formulário -->

          <h5>Categorias mais procuradas</h5>

          <div class="row mb-4 mt-4">

            <!-- fotos produtos -->
            <div class="col-md-6 colCategories">
              <ul class="navbar-nav navCategories">

                <li class="nav-item navCategories-item">
                  <a class="nav-link navCategories-link" href="#">Frutas</a>
                </li>

                <li class="nav-item navCategories-item">
                  <a class="nav-link navCategories-link" href="#">Verduras</a>
                </li>

                <li class="nav-item navCategories-item">
                  <a class="nav-link navCategories-link" href="#">Bebidas</a>
                </li>
              </ul>
            </div>

            <div class="col-md-6 colCategories">
              <ul class="navbar-nav navCategories">

                <li class="nav-item navCategories-item">
                  <a class="nav-link navCategories-link" href="#">Legumes</a>
                </li>

                <li class="nav-item navCategories-item">
                  <a class="nav-link navCategories-link" href="#">Frios</a>
                </li>

                <li class="nav-item navCategories-item">
                  <a class="nav-link navCategories-link" href="#">Especiarias</a>
                </li>
              </ul>
            </div>

          </div>

        </div><!-- /Search bar e categorias -->

      </div><!-- /row -->
    </div><!-- /container -->
  </section><!-- /servicos -->

  <footer>
    <div class="container">
      <div class="row">

        <div id="logoFooter" class="col-md-2">
          <img class="img-fluid" src="img/logo/DaRoca.svg" alt="">
        </div>

        <div class="col-md-2">
          <h4>Company</h4>
          <ul class="navbar-nav">
            <li>
              <a href="">Entrar</a>
            </li>
            <li>
              <a href="views/cadastro.php">Cadastre-se</a>
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
                <img src="img/midias/facebook.png" alt="">
              </a>
            </li>
            <li>
              <a href="" class="m-2">
                <img src="img/midias/twitter.png" alt="">
              </a>
            </li>
            <li>
              <a href="" class="m-2">
                <img src="img/midias/instagram.png" alt="">
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </footer>


  <script>
    function openNav() {
      document.getElementById("mySidenav").style.width = "45%";
    }

    function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

</body>

</html>