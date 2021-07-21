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
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
  if ($resultDadosProduto != 0) {
    //Puxo os dados requisitados do produto p/uma row
    $DadosProduto = mysqli_fetch_assoc($resultDadosProduto);
    // requisição das fotos do produto
    $resultFotosProduto = $conn->requestFotosProduto($codigo);
    if ($resultFotosProduto == 0) {
      header("Location: ../views/meusAnuncios.php");
    }

    if ((session_status() !== PHP_SESSION_NONE) && isset($_SESSION['log_id'])) {
      if ($_POST['action'] == 'carrinho') {
        $result = $conn->insertUpdateFromPage($codigo, $_POST['qntd_compra']);
        if($result == 0){
          header("Location: ../views/meusAnuncios.php");
        }
      } 
      if($_POST['action'] == 'compra') {
        //action for delete
      }
    }
    else{
      header("Location: ../controller/logout.php");
    }  

  } else {
    header("Location: ../views/meusAnuncios.php");
  }
} else {
  header("Location: ../views/meusAnuncios.php");
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
            <i id="carrinho_icon" class="fa fa-shopping-cart"></i> 
            <span class="badge badge-success"><?php echo $carrinho_QntdProdutos_Valor['qntd_produtos'];?></span><br>
          </a>
          <div class="dropdown-menu">
            <a id="total" class="dropdown-item" href="#">R$ <?php echo $carrinho_QntdProdutos_Valor['total'];?></a>
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

              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="javascript:history.back()">Voltar</a>
                </li>
                <li class="breadcrumb-item">
                  <a href="#"><?php echo $conn->categoriaProduto($DadosProduto['categoria']); ?></a>
                </li>
                <li class="breadcrumb-item">
                  <a href="#">
                  <?php if(strstr($DadosProduto['produto'], ' ', true)){
                    echo strstr($DadosProduto['produto'], ' ', true);
                    }else{ echo $DadosProduto['produto'];} ?>
                  </a>
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
              <div class="col-md-7">

                <!-- ROW imagens -->
                <div class="content">

                  <div class="cover">
                    <img id="imgCover" class="img-fluid" src="<?php echo $DadosProduto['foto']; ?>" alt="">
                  </div>
                  <!-- /cover -->

                  <ul class="thumbs">
                    <?php while ($fotosProduto = mysqli_fetch_assoc($resultFotosProduto)) { ?>
                      <li><img class="img-fluid" src="<?php echo $fotosProduto['caminho_foto']; ?>" alt="" onclick="changePhoto(this)"></li>
                    <?php } ?>
                  </ul>
                  <!-- /thumbs -->

                </div>
                <!-- /ROW imagens -->

                <table class="table table-light">
                  <tbody>
                    <th>Características do produto</th>
                    <tr>
                      <td>
                        <span class="caracteristica-span">
                          <img class="img-fluid" src="../img/logo/categoria.svg" alt="categoria imagem">
                        </span>
                        <span>
                          Categoria:&nbsp;<strong><?php echo $conn->categoriaProduto($DadosProduto['categoria']); ?></strong>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
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

                <table class="table table-light">
                  <tbody>
                    <th>Descrição</th>
                    <tr>
                      <td><?php echo $DadosProduto['descricao']; ?></td>
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

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?produto=".$codigo; ?>" enctype="multipart/form-data">

                  <table class="table table-light infos-compra" style="border: 1px solid rgba(0,0,0,.2);">

                    <tbody>
                      <tr>
                        <td class="smallInfos"><?php echo $DadosProduto['visualizacoes']; ?> visualizações</td>
                      </tr>
                      <tr>
                        <td>
                          <h2><strong><?php echo $DadosProduto['produto']; ?></strong></h2>
                        </td>
                      </tr>
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
                      <tr>
                        <td class="smallInfos">
                          <?php echo $DadosProduto['num_vendas_produto'] . " compras"; ?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <h3>R$
                            <strong id="preco" data-value="<?php echo $DadosProduto['preco']; ?>">
                              <?php echo $DadosProduto['preco']; ?>
                            </strong>&nbsp;/<?php echo $conn->tipoVendaProduto($DadosProduto['tipo_venda']); ?>
                          </h3>
                        </td>
                      </tr>
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
                      <tr>
                        <td>
                          <h4 id="ValorTotal">Total: R$
                            <?php echo round($DadosProduto['qntd_min_vendida'] * $DadosProduto['preco'], 2); ?>
                          </h4>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-group">
                            <button type="submit" name="action" value="carrinho" class="btn btn-secondary">Adicionar ao carrinho</button>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="form-group">
                            <button type="submit" name="action" value="compra" class="btn btn-success">Realizar compra</button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                </form>

                <table class="table table-light">
                  <tbody>
                    <th>Informações do vendedor</th>
                    <tr>
                      <td><?php echo $DadosProduto['vendedor']; ?></td>
                    </tr>
                    <tr>
                      <td class="smallInfos">
                        <?php echo $DadosProduto['vendas_vendedor']; ?> vendas realizadas
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <p>Avaliação do vendedor:</p>
                        <span class="aval-span">
                          <?php echo $conn->avaliacaoImg($DadosProduto['vendedor_avaliacao']); ?>
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td class="smallInfos">
                        <?php echo ($DadosProduto['cidade'].", ".$DadosProduto['estado']); ?>
                      </td>
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