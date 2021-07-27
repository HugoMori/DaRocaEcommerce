<?php
session_start();
include '../controller/controlRequest.php';
$conn = new controlRequest();

//Se já tiver alguma sessão iniciada 
//e for passado o produto via URL
if ((session_status() !== PHP_SESSION_NONE) && isset($_SESSION['log_id'])) {
  //carrinho dropdow
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
  $carrinhoDropDow = $conn->requestDadosCarrinho($_SESSION['log_id']);
  if (count($_POST) > 0) {

    //tentar realizar o cadastro
    if ($conn->tratamentoDadosProduto($_POST['nome_Produto'], $_POST['preco_Produto'], $_POST['qntd_Disponivel'], 
    $_POST['categorias_produto'], $_POST['opcaoVenda_Produto'], $_POST['qntd_Minima'], $_POST['descricao_Produto'], 
    $_POST['data_prod'], $_POST['data_validade'], $_FILES['produtos'], 1 ) == 1) {
      //redirecionar para a paginá do produto
      header("Location: ../views/minha_conta.php");
    }
    //se não der certo, informa
    else {
      unset($_POST);
      header("Location: ../views/minha_conta.php");
    }
  }
} else {
  header("Location: ../controller/logout.php");
}
?>
<?php
$sideBarOption = '<a class="mySidenav-link" href="../index.php"><i class="fas fa-home"> Home</i></a>';
//operações
//Se estiver logado
if (isset($_SESSION['log_id'])) {

  //menu option (SIDE BAR MENU)
  $sideBarOption1 = '<a class="mySidenav-link" href="../views/minhas_compras.php"><i class="far fa-list-alt"> Meus pedidos</i></a>';
  $sideBarOption2 = '<a class="mySidenav-link" href="../views/minha_conta.php"><i class="fas fa-user"> Minha conta</i></a>';

  // NavBar Itens
  $NavBarOption1 = '<a class="nav-link" href="../views/minhas_compras.php"><i class="far fa-list-alt">&nbsp&nbspMeus pedidos</i></a>';
  $NavBarOption2 = '<a class="nav-link" href="../views/minha_conta.php"><i class="fas fa-user">&nbsp&nbspMinha conta</i></a>';

  //NamePage
  //$NamePage = "<h3 class='breadcrumb-header'>Alterar Cadastro</h3>";
}
//Se não estiver logado
else {

  //menu option (SIDE BAR MENU)
  $sideBarOption1 = '<a class="mySidenav-link" href="../views/cadastro.php"><i class="far fa-edit"> Cadastrar-se</i></a>';
  $sideBarOption2 = '<a class="mySidenav-link" href="../views/login.php"><i class="fas fa-sign-in-alt"> Entrar</i></a>';

  // NavBar Itens
  $NavBarOption1 = '<a class="nav-link" href="../views/cadastro.php"><i class="far fa-edit">&nbsp&nbspCadastrar-se</i></a>';
  $NavBarOption2 = '<a class="nav-link" href="../views/login.php"><i class="fas fa-sign-in-alt">&nbsp&nbspEntrar</i></a>';

  //NamePage
  //$NamePage = "<h3 class='breadcrumb-header'>Cadastro</h3>";
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta charset="UTF-8">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <!-- FontAwesome-->
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
  <!-- css -->
  <link rel="stylesheet" type="text/css" href="../css/cadastro_produto.css">
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
      <div class="container top-container">

        <!-- Logo -->
        <a href="../index.php" class="navbar-brand">
          <img src="../img/logo/DaRoca.svg" alt="logo Da Roça" class="img-fluid d-none d-md-block">
        </a>
        <!-- /Logo -->

        <!-- Menu Toogle -->
        <button id="toggleButton" class="navbar-toggler d-md-block d-lg-none" data-toggle="collapse" onclick="openNav()">
          <i class="fas fa-bars text-white"></i>
        </button>
        <!-- /Menu Toogle -->

        <!-- SideBar menu-->
        <div id="mySidenav" class="sidenav">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
          <!-- Menu option -->
          <div class="row">
            <?php echo $sideBarOption; ?>
          </div>
          <!-- Menu option -->
          <div class="row">
            <?php echo $sideBarOption1; ?>
          </div>
          <!-- Menu option -->
          <div class="row">
            <?php echo $sideBarOption2; ?>
          </div>
        </div>
        <!-- /SideBar menu-->

        <!-- SearchBar -->
        <form id="searchBarTop" method="get" action="../views/produtos.php" class="form-inline" enctype="multipart/form-data">
          <input name="prod" type="text" class="form-control" placeholder="Buscar no Da Roça">
          <button type="submit" class="btn btn-outline-success"><i class="fas fa-search"></i></button>
        </form>
        <!-- /SearchBar -->

        <?php
        if (!isset($_SESSION['log_id'])) {
          echo $conn->unsetCarrinho();
        }
        ?>

        <?php if (isset($_SESSION['log_id'])) { ?>

          <!-- carrinho -->
          <div class="dropdown">
            <a href="#" class="car_button" data-toggle="dropdown">
              <i id="carrinho_icon" class="fa fa-shopping-cart"></i>
              <span class="badge badge-success"><?php echo $carrinho_QntdProdutos_Valor['qntd_produtos']; ?></span><br>
            </a>
            <div class="dropdown-menu">

              <table class="table table-light tableCarrinho">
                <tbody>
                  <th>Produto</th>
                  <th>Quantidade</th>
                  <th>Custo</th>
                  <?php while ($carrinhoDados = mysqli_fetch_assoc($carrinhoDropDow)) { ?>
                    <tr>
                      <td>
                        <?php if (strstr($carrinhoDados['produto'], ' ', true)) {
                          echo strstr($carrinhoDados['produto'], ' ', true) . " ";
                        } else {
                          echo $carrinhoDados['produto'];
                        } ?>
                      </td>
                      <td>
                        <?php echo $carrinhoDados['qntd'] . " " . $conn->tipoVendaProduto($carrinhoDados['tipo_venda']); ?>
                      </td>
                      <td class="tdCarrinhoPrecoProd">
                        <?php echo "R$ " . round(($carrinhoDados['qntd'] * $carrinhoDados['preco']), 2); ?>
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
                    <td  class="tdCarrinhoPrecoTotal">
                      R$ <?php echo $carrinho_QntdProdutos_Valor['total']; ?>
                    </td>
                  </tr>
                </tbody>
              </table>

              <a id="checkout" class="dropdown-item" href="../views/carrinho.php">Pagar</a>

            </div>
          </div>
          <!-- /carrinho -->

        <?php } ?>

        <!-- Nav-principal -->
        <div id="nav-principal" class="collapse navbar-collapse">
          <ul class="navbar-nav ml-auto">

            <li class="nav-item divisor"></li>
            <li class="nav-item">
              <!-- NavBar Itens -->
              <?php echo $NavBarOption1;?>
            </li>

            <!-- NavBar Itens -->
            <li class="nav-item">
              <?php echo $NavBarOption2;?>
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
              <h3 class="breadcrumb-header">Cadastro do produto</h3>
              <ul class="breadcrumb-tree">
                <li><a href="javascript:history.back()">Voltar</a></li>
              </ul>
            </div>
            <?php
            if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
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
        <div class="cadastro-section">
          <!-- container -->
          <div class="container cadastro-container">
            <!-- Formulário -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
              <!--Campos de cadastro -->
              <div class="row">

                <!-- Coluna 1 -->
                <div class="col-md-6 coluna">

                  <!-- Produto - Nome -->
                  <div class="form-group">
                    <label for="nomeProduto">Produto</label>
                    <input id="nomeProduto" class="form-control" type="text" name="nome_Produto" placeholder="Digite o nome do produto" required>
                  </div>
                  <!-- /Produto - Nome -->

                  <!-- Row -->
                  <div class="row">
                    <!-- Coluna 1-->
                    <div class="col-md-6 coluna">

                      <!-- Produto - Categoria -->
                      <div class="form-group">
                        <label for="categoriaProduto">Categoria</label>
                        <select id="categoriaProduto" class="form-control" name="categorias_produto" required>
                          <option value="Frutas">Frutas</option>
                          <option value="Verduras">Verduras</option>
                          <option value="Legumes">Legumes</option>
                          <option value="Bebidas">Bebidas</option>
                          <option value="Frios">Frios</option>
                          <option value="Especiarias">Especiarias</option>
                        </select>
                      </div>
                      <!-- /Produto - Categoria -->

                      <!-- Produto - Preço -->
                      <div class="input-group">
                        <label for="precoProduto" id="precoProdutoId">Preço do produto:</label>
                        <div class="row row-precoProduto">
                          <span class="spanTag">R$&nbsp;</span>
                          <input id="precoProduto" class="form-control precoFreteProduto" type="number" min="0" step="0.05" name="preco_Produto" placeholder="0.0" required />
                          <span id='preco'></span>
                        </div>
                      </div>
                      <br>
                      <!-- /Produto - Preço -->

                      <!-- Produto - quantidade Min -->
                      <div class="form-group">
                        <label for="qntdMinima">Quantidade mínima a ser comprada p/usuário:</label>
                        <input id="qntdMinima" class="form-control" type="number" min="0" step="0.05" name="qntd_Minima" placeholder="0.0" required />
                        <span id='qntdMin'></span>
                      </div>
                      <!-- /Produto - quantidade Min -->

                    </div>
                    <!-- /Coluna 1-->

                    <!-- Coluna 2-->
                    <div class="col-md-6 coluna">

                      <!-- Produto - Tipo venda -->
                      <div class="form-group">
                        <label for="opcaoProduto">Venda por:</label>
                        <select id="opcaoProduto" class="form-control" name="opcaoVenda_Produto" required onChange="tpVendaProd(this)">
                          <option value="">Selecione uma opção</option>
                          <option value="1">Quilo</option>
                          <option value="2">Caixa</option>
                          <option value="3">Litro</option>
                          <option value="4">Dúzia</option>
                          <option value="5">Unidade</option>
                        </select>
                      </div>
                      <!-- /Produto - Tipo venda -->

                      <!-- Produto - quantidade disponivel -->
                      <div class="form-group" id="qntdDisponivelDiv">
                        <label for="qntdDisponivel">Quantidade ofertada:</label>
                        <input id="qntdDisponivel" class="form-control" type="number" min="0" step="0.05" name="qntd_Disponivel" placeholder="0.0" required />
                        <span id='qntdDisp'></span>
                      </div>
                      <!-- /Produto - quantidade disponivel -->

                    </div>
                    <!-- /Coluna 2-->


                    <div class="form-group">
                      <label for="dataNasc">Data de produção</label>
                      <input id="dataProd" class="form-control" type="date" name="data_prod" min="01-01-1900" required>
                    </div>

                    <div id="dataVenc" class="form-group">
                      <label for="dataNasc">Data de validade</label>
                      <input class="form-control" type="date" name="data_validade" min="01-01-1900">
                    </div>

                  </div>
                  <!-- /Row -->

                </div>
                <!-- /Coluna 1 -->

                <div class="col-md-6 coluna">

                  <div class="form-group">
                    <label for="descricaoProduto">Descrição do produto:</label>
                    <textarea class="form-control" name="descricao_Produto" id="descricaoProduto" cols="15" rows="8" placeholder="Faça uma descrição do seu produto"></textarea>
                  </div>

                  <div class="form-group" id="fotosProdutoDiv">
                    <label for="fotosProduto">Fotos do produto:</label>
                    <p id='qntdImagens'>Máximo: 5 fotos</p>
                    <input class="form-control-file" id="fotos" type="file" name="produtos[]" oninput="checkQntdFotosProduto(this)" onChange="contarArquivos()" max-uploads="5" accept="image/png, image/jpeg, image/jpg, image/bmp, image/gif" multiple>
                  </div>

                  <input class="btn btn-primary" type="submit" value="Cadastrar" />

                  </button>

                </div>
                <!-- /Col -->

              </div>
              <!-- /Campos de cadastro -->

            </form>
            <!-- /Formulário -->

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

        <div class="col-md-2 rodapeCol">
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

        <div class="col-md-2 rodapeCol">
          <h4>Mais buscados</h4>
          <div class="col-md-2 colBuscados" id="colBuscadosE">
            <ul class="navbar-nav">
              <li>
                <a href="../views/produtos.php?prod=Frutas">Frutas</a>
              </li>
              <li>
                <a href="../views/produtos.php?prod=Verduras">Verduras</a>
              </li>
              <li>
                <a href="../views/produtos.php?prod=Bebidas">Bebidas</a>
              </li>
              <li>
                <a href="../views/produtos.php?prod=Legumes">Legumes</a>
              </li>
            </ul>
          </div>
          <div class="col-md-2 colBuscados" id="colBuscadosD">
            <ul class="navbar-nav">
              <li>
                <a href="../views/produtos.php?prod=Frios">Frios</a>
              </li>
              <li>
                <a href="../views/produtos.php?prod=Especiarias">Especiarias</a>
              </li>
            </ul>
          </div>
        </div>

        <div class="col-md-6 redesSociaisCol">
          <ul>
            <li>
              <a href="https://www.facebook.com/hugo.mori.9" target='_blank' class="m-2">
                <img src="../img/midias/facebook.png" alt="">
              </a>
            </li>
            <li>
              <a href="https://github.com/HugoMori/" target='_blank' class="m-2">
                <img src="../img/midias/github.png" alt="">
              </a>
            </li>
            <li>
              <a href="https://www.linkedin.com/in/hugo-mori-a43a87132/" target='_blank' class="m-2">
                <img src="../img/midias/linkedin.png" alt="">
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