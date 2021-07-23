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

  //carrinho dropdow
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
  $carrinhoDropDow = $conn->requestDadosCarrinho($_SESSION['log_id']);
  
  //numero de itens puxados do bd p/pagina
  $itensPorPagina = 10;

  //pegar a pagina passada via GET
  if (count($_GET) > 0) {
    $page = intval($_GET['pagina']);
  } 
  //Se não houver pagina passada via GET
  else {
    $page = 0;
  }
  //Se a página for 0, disable ativo, para o link
  //p/pagina anterior estar desativado
  if ($page == 0) {
    $disable = 'disabled';
  } 
  //se a pagina não for 0
  else {
    $disable = '';
  }

  //chamar função de request de dados
  $result = $conn->requestAllVendas($page, $itensPorPagina);
  //Verificar quantidade de itens retornados
  $numRows = mysqli_num_rows($result);

  //num de produtos no bd
  $pagesDB = $conn->qntdVendasRealizadas();
  //num de paginas
  $paginas = ceil($pagesDB / $itensPorPagina);
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
  <link rel="stylesheet" type="text/css" href="../css/minhas_transacoes.css">
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
              <h3 class="breadcrumb-header">Minhas vendas</h3>
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
            <!-- row -->
            <div class="d-flex flex-row">

              <?php if ($numRows > 0) { ?>

                <table class="table table-bordered table-hover">
                  <!-- corpo da tabela -->
                  <tbody>

                    <?php while ($rows = mysqli_fetch_assoc($result)) {
                      
                      //setar foto padrão caso não tenha
                      if ($rows['foto_produto'] == "" || $rows['foto_produto'] == null) {
                        $img = "../img/produto/2/dadcacccac88c5a9f279e126e624690c.jpeg";
                      } else {
                        $img = $rows['foto_produto'];
                      }  
                      
                    ?>
                      
                      <!-- table row -->
                      <tr style="display: table-row;">
                        
                        <!-- divisão foto -->
                        <td class="tb_foto">
                          <a class="nav-link" href="../views/produtoStore.php?produto=<?php echo $rows['produto_id']; ?>">
                            <img id="foto_produto" class="img-fluid" 
                            src="<?php echo $img; ?>" alt="Foto do produto anúnciado">
                          </a>
                        </td>
                        <!-- divisão infos prod -->

                        <td class="infosCompra">
                          <p><strong>Produto:</strong> <?php echo $rows['produto']; ?></p>
                          <p><strong>Categoria:</strong> <?php echo $conn->categoriaProduto($rows['categoria']); ?></p>
                          <p><strong>Quantidade comprada:</strong> <?php echo $rows['qntd_comprada'] . " " . $conn->tipoVendaProduto($rows['tipo_venda']); ?></p>
                          <p><strong>Valor total:</strong> R$ <?php echo round(($rows['preco'] * $rows['qntd_comprada']),2); ?></p>
                        </td>

                        <!-- divisão infos vendedor/data anuncio/visualizacao/vendas/avalizacao -->
                        <td class="infosCompra">
                          <p><strong>Comprador:</strong> <?php echo $rows['comprador']; ?></p>
                          <p><strong>Cidade:</strong> <?php echo $rows['cidade'].", ".$rows['estado']; ?></p>
                          <p><strong>Data da compra:</strong> <?php echo $conn->convertDate($rows['data_compra']); ?></p>
                        </td>

                        <!-- botão de alterar anuncio/remover/visualizar anuncio -->
                        <td class="infosCompra">

                          <ul class="navbar-nav">

                            <li class="nav-item">
                              <a class="nav-link" href="../views/produtoStore.php?produto=<?php echo $rows['produto_id']; ?>">
                                <button type="button" class="btn btn-secondary btn-anuncio">Mais informações<br>sobre a venda</button>
                              </a>
                            </li>

                          </ul>

                        </td>
                        
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>

            </div>
            <!-- /row -->

            <div class="d-flex flex-row">

              <!-- paginação -->
              <nav aria-label="...">
                <ul class="pagination">
                  <li class="page-item <?php echo $disable; ?>">
                    <a href="../views/meusAnuncios.php?pagina=0">
                      <span class="page-link">Primeira</span>
                    </a>
                  </li>
                  <?php for ($i = 0; $i < $paginas; $i++) {
                    $activePage = "";
                    if ($paginas == $i) {
                      $activePage = 'active';
                    }

                    if ($paginas > $i + 1) {
                      $disable = '';
                      echo '<li class="page-item ' . $activePage . '">
                            <a class="page-link" href="../views/meusAnuncios.php?pagina=' . $i . '">' . ($i + 1) . '</a>
                            </li>';
                    } else {
                      $disable = 'disabled';
                    }
                  ?>
                  <?php } ?>
                  <li class="page-item <?php echo $disable; ?>">
                    <a class="page-link" href="../views/meusAnuncios.php?pagina=<?php echo $paginas - 1; ?>">Última</a>
                  </li>
                </ul>
              </nav>
              <!-- /paginação -->

            <?php } ?>
            <!-- /if ($numRows > 0) -->
            <?php if ($numRows <= 0) { ?>
              <!-- todo espaço da tela -->
              <div class="col-md-12 capa">

                <h1 style="font-size: xxx-large;">Ops!!</h1>
                <h3>
                  Você ainda não realizou nenhum anúncio.<br>
                </h3>
                <img class="img-fluid" src="../img/logo/brocolisCartoonSurprised.svg" style="width: 20%;" alt="Logo Da Roça">
                <h4>
                  Clique no botão para realizar seu anúncio.<br>
                </h4>
                <a href="../views/cadastro_produto2.php">
                  <button type="button" class="btn btn-success btn-lg" style="margin-bottom: 60px; margin-top: 20px;">Anunciar produto</button>
                </a>

              </div>
            <?php } ?>

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