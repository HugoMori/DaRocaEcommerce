<?php
/* esse bloco de código em php verifica se existe a sessão aberta, se sim, o
 * usuário continua na pagina, se não, se ele  tentar burlar o acesso, 
 * redireciona para o index*/
session_start();

//inclui o php que contém a conexão com o banco
include '../controller/controlRequest.php';
$conn = new controlRequest();

//verifica se há alguma sessão vazia, se sim, apaga ela e vai para o index, 
//se estiver com algum dado, ou seja, se o usuário estiver logado, puxará os 
//dados do usuário através do request
if ((!isset($_SESSION['log_id']) == true)) {
  header("Location: ../controller/logout.php");
} else {
  $result = $conn->requestDadosUser($_SESSION['log_id']);
  //carrinho dropdow
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
  $carrinhoDropDow = $conn->requestDadosCarrinho($_SESSION['log_id']);
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
  <link rel="stylesheet" type="text/css" href="../css/minha_conta.css">
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
              <h3 class="breadcrumb-header">Minha conta</h3>
              <ul class="breadcrumb-tree">
                <li>
                  <a href="#">

                    <!-- delogar -->
                    <div class="dropdown">
                      <a href="#" class="deslogar" data-toggle="dropdown">
                        deslogar
                      </a>
                      <div class="dropdown-menu" id="dropDelosg">
                        Tem certeza disso?<br><br>
                        <a href="../controller/logout.php" id="deslogButtonYes">
                          <button type="button" class="btn btn-danger">Sim</button>
                        </a>
                        <button id="deslogButtonNo" type="button" class="btn btn-secondary">Não</button>
                      </div>
                    </div>
                    <!-- /deslogar -->

                  </a>
                </li>
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
            <!-- row -->
            <div class="d-flex flex-row">

              <div class="col-md-8" id="DadosConta-col">

                <!-- foto -->
                <?php
                if ($result != -1 && ($result['caminho_foto_perfil'] != null || $result['caminho_foto_perfil'] != '')) {
                  echo '<img class="img-fluid" src=' . $result['caminho_foto_perfil'] . ' id="Foto_perfil" alt="Foto_perfil">';
                } else {
                  echo '<img class="img-fluid" src="../img/perfil/standart/perfil.jpeg" id="Foto_perfil" alt="Foto_perfil">';
                }
                ?>
                <!-- dados -->
                <div id="dadosUser">
                  <?php
                  if ($result != -1) {
                    echo "<p>" . $result['nome'] . "</p>";
                    //echo "<p>".$_SESSION['log_id']."</p>";
                    echo "<p>" . $result['telefone'] . "</p>";
                    $map = $result['endereco'] . ", " . $result['cep'] . ", " . $result['estado'];
                    echo "<p><a id='endUserMap' href = 'https://www.google.com.br/maps/place/" . $map . "' target='_blank'>" . $map . "</a></p>";
                    echo "<p>Avaliação: " . $conn->avaliacaoImg($result['avaliacao']) . " (" . $result['avaliacao'] . ")</p>";
                  } 
                  else {
                    echo "<p>Nome</p>";
                    echo "<p>E-mail</p>";
                    echo "<p>Telefone</p>";
                    echo "<p>Endereço</p>";
                  }
                  ?>

                  <!-- alterar cadastro -->
                  <a href="cadastro.php">
                    <button type="button" class="btn btn-secondary btn-sm">Alterar cadastro</button>
                  </a>
                </div>

              </div>

              <div class="col-md-3" id="historicoCompraVenda-col">

                <!-- histórico de compras -->
                <ul class="navbar-nav">

                  <li class="nav-item">
                    <a class="nav-link" href="../views/carrinho.php">
                      <button type="button" style="width: 127px; height: 62px" class="btn btn-outline-light histBtn">Carrinho</button>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="../views/minhas_compras.php">
                      <button type="button" style="width: 127px;" class="btn btn-outline-light histBtn">Minhas compras</button>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="../views/minhas_vendas.php">
                      <button type="button" style="width: 127px;" class="btn btn-outline-light histBtn">Minhas vendas</button>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="../views/meusAnuncios.php">
                      <button type="button" style="width: 127px;" class="btn btn-outline-light histBtn">Meus anúncios</button>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link link-img" href="cadastro_produto2.php">
                      <img src="../img/perfil/my_personal_menu_anuncie.jpg" class="img-fluid" alt="">
                    </a>
                  </li>

                </ul>

              </div>
              <!-- /historicoCompraVenda-col -->
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