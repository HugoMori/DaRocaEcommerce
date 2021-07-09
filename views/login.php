<?php
session_start();
//Se já tiver alguma sessão iniciada e algum cookie, sinal que o usuário já está cadastrado
if ((session_status() !== PHP_SESSION_NONE) && isset($_SESSION['log_id'])) {
  header("Location: ../views/minha_conta.php");
} 
else {
  if (count($_POST) > 0) {
    //echo "enviado";
    include '../controller/controlRequest.php';
    $conn = new controlRequest();

    $email_user = filter_input(INPUT_POST, 'email_user', FILTER_SANITIZE_EMAIL);
    $senha_user = filter_input(INPUT_POST, 'senha_user', FILTER_SANITIZE_STRING);

    if($conn->login($email_user, $senha_user)){
      $_SESSION['log_id'] = $email_user;
      header("Location: ../views/minha_conta.php");
    }
    else{
      unset($_SESSION['log_id']);
      session_destroy();
      unset($_POST);
    }
  }
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
  <link rel="stylesheet" type="text/css" href="../css/login.css">
  <link rel="stylesheet" type="text/css" href="../css/stylesCommons.css">
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
            <a class="mySidenav-link" href="../views/cadastro.php">
              <i class="far fa-user"> Cadastrar-se</i>
            </a>
          </div>
          <div class="row">
            <a class="mySidenav-link" href="#">
              <i class="fal fa-home"> Entrar</i>
            </a>
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
              <a class="nav-link" href="../views/cadastro.php">Cadastrar-se</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="../views/login.php">Entrar</a>
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
              <h3 class="breadcrumb-header">Login</h3>
              <ul class="breadcrumb-tree">
                <li><a href="javascript:history.back()">Voltar</a></li>
              </ul>
            </div>
            <?php 
                  if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){
                    echo $_SESSION['msg'];
                    $_SESSION['msg'] = "";
                  }
            ?>
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
            <!-- row -->
            <div class="d-flex flex-row">
              <div class="col-md-8 loginCol">

                <div id="loginBox">
                  <h3>Já é cadastrado? Entre com seu e-mail e senha.</h3>
                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

                    <div class="form-group">
                      <input id="emailUser" class="form-control" type="email" name="email_user" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="form-group">
                      <input id="senhaUser" class="form-control" type="password" name="senha_user" placeholder="Digite sua senha" required>
                    </div>

                    <input class="btn btn-primary" type="submit" name="commit" value="Login">

                  </form>
                </div>

                <div id="cadastreBox" class="row">
                  <h5>Ainda não é cadastrado? Clique aqui e se cadastre.</h5>
                  <button class="btn btn-secondary btn-sm" type="button" onclick="location.href = 'cadastro.php'">Cadastrar</button><br>
                </div>

              </div>

              <div class="col-md-4 loginCol">
                <img src="../img/perfil/standart/produtor_icon.png" alt="produtor_icon" id="img_produtor" class="img-fluid d-none d-md-block">
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
              <a href="">Entrar</a>
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