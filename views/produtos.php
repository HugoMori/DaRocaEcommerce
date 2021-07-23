<?php

session_start();

//inclui o php que contém a conexão com o banco e o php de request/send dados
include '../controller/controlRequest.php';
$conn = new controlRequest();
//carrinho dropdow
if (isset($_SESSION['log_id'])) {
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
  $carrinhoDropDow = $conn->requestDadosCarrinho($_SESSION['log_id']);
}

if (isset($_GET['prod']) && ($_GET['prod'] != null || $_GET['prod'] != '' || $_GET['prod'] != ' ')) {

  //realizar pesquisa
  $result = $conn->pesquisar($_GET['prod']);

  //se a pesquisar tiver dado certo
  $numRowsProd = mysqli_num_rows($result);
  if ($numRowsProd > 0) {
    //filtros
    echo "<script>console.log(Posts: " . count($_POST) . "' );</script>";
    if (count($_POST) > 0) {
      //POST filtros
      if(isset($_POST['filtros'])){
        $result = $conn->pesquisarFiltros($_POST['checkCategoria'], $_POST['precoMax'], $_POST['precoMin'], $_POST['checkCidade']);
        $numRowsProd = mysqli_num_rows($result);
        unset($_POST['filtros']);
      }
      //POST ordenar
      // if(){
        
      // }
    }

    //selecionar as categorias
    $categorias = $conn->pesquisarCategorias();
    $numRowsCategoria = mysqli_num_rows($categorias);
    //selecionar as cidades
    $cidades = $conn->pesquisarCidade();
    $numRowsCidades = mysqli_num_rows($cidades);
    //selecionar os precos
    $precos = $conn->pesquisarPrecos();
    $numRowsPrecos = mysqli_num_rows($precos);
    //retornar qntd de produtos ao todo
    $qntdProdutos = $conn->pesquisarQntdProdutos();
    $qntdResultados = mysqli_fetch_assoc($qntdProdutos);
    $numRowsQtdProdutos = mysqli_num_rows($qntdProdutos);

    unset($_SESSION['consulta']);
    unset($_SESSION['subConsulta']);
  }
  //se não, pagina de erro 
  else {
  }
}
//pagina de erro
else {
}

?>
<?php

//operações
//Se estiver logado
if (isset($_SESSION['log_id'])) {

  //menu option (SIDE BAR MENU)
  $sideBarOption1 = '<a class="mySidenav-link" href="href="../views/compras.php"><i class="far fa-list-alt"> Meus pedidos</i></a>';
  $sideBarOption2 = '<a class="mySidenav-link" href="../views/minha_conta.php"><i class="fas fa-user"> Minha conta</i></a>';

  // NavBar Itens
  $NavBarOption1 = '<a class="nav-link" href="#"><i class="far fa-list-alt">&nbsp&nbspMeus pedidos</i></a>';
  $NavBarOption2 = '<a class="nav-link" href="../views/minha_conta.php"><i class="fas fa-user">&nbsp&nbspMinha conta</i></a>';

  //NamePage
  $NamePage = "<h3 class='breadcrumb-header'>Alterar Cadastro</h3>";
}
//Se não estiver logado
else {

  //menu option (SIDE BAR MENU)
  $sideBarOption1 = '<a class="mySidenav-link" href="href="../views/cadastro.php"><i class="far fa-edit"> Cadastrar-se</i></a>';
  $sideBarOption2 = '<a class="mySidenav-link" href="../views/login.php"><i class="fas fa-sign-in-alt"> Entrar</i></a>';

  // NavBar Itens
  $NavBarOption1 = '<a class="nav-link" href="../views/cadastro.php"><i class="far fa-edit">&nbsp&nbspCadastrar-se</i></a>';
  $NavBarOption2 = '<a class="nav-link" href="../views/login.php"><i class="fas fa-sign-in-alt">&nbsp&nbspEntrar</i></a>';

}

if(isset($_GET['prod']) && $_GET['prod'] != ''){
  $nomeProduto = ucfirst(mb_strtolower($_GET['prod'], 'UTF-8'));
}
else{
  $nomeProduto = "Todos os produtos";
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
  <!-- <link rel="stylesheet" type="text/css" href="../css/"> -->
  <link rel="stylesheet" type="text/css" href="../css/stylesCommons.css">
  <link rel="stylesheet" type="text/css" href="../css/produtos.css">
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
        <button id="toggleButton" class="navbar-toggler" data-toggle="collapse" onclick="openNav()">
          <i class="fas fa-bars text-white"></i>
        </button>
        <!-- /Menu Toogle -->

        <!-- SideBar menu-->
        <div id="mySidenav" class="sidenav">
          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
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
          <input name="prod" type="text" class="form-control" placeholder="Buscar no Da Roça" value="<?php echo $_GET['prod']; ?>">
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

      <section>
        <!-- com o container ai consigo usar row e col -->
        <div class="container">
          <div class="row">

            <!-- filtros -->
            <aside class="col-md-3">

              <!-- Nome do que está sendo pesquisado -->
              <h1 id="h1NomeProd">
                <?php echo $nomeProduto;?>
              </h1>
              <!-- Qntd de produtos -->
              <span id="spanQntdResultados">
                <?php echo $qntdResultados['qntd_resultados']; ?> resultados
              </span>

              <!-- Tabela de filtros (Preço/localização/categoria) -->
              <table class="table table-light">
                <tbody>

                  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?prod=".$_GET['prod']; ?>" enctype="multipart/form-data">

                    <th>Filtros</th>

                    <tr>

                      <!-- Filtro categoria -->
                      <td>
                        <p><strong>Categoria</strong></p>
                        <!-- se tiver produtos -->
                        <?php if ($numRowsProd) { ?>

                          <!-- if se tiver categorias -->
                          <?php if ($numRowsCategoria) { ?>

                            <!-- while com checkbox -->
                            <?php while ($rows = mysqli_fetch_assoc($categorias)) { 
                              
                              //categoria
                              if(isset($_POST['checkCategoria'])){
                                  $i = 0;
                                  while($i < count($_POST['checkCategoria'])){
                                      if($rows['categoria'] == $_POST['checkCategoria'][$i]){
                                        $check = "checked";
                                      }
                                      $i++; 
                                  }
                              }
                              else{
                                $check = "";
                              }
                            
                            ?>

                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                name="checkCategoria[]" value="<?php echo $rows['categoria']; ?>" id="checkCategoria"
                                <?php echo $check; ?>>
                                <label class="form-check-label" for="checkCategoria">
                                  <?php echo $conn->categoriaProduto($rows['categoria']); ?>
                                </label>
                              </div>

                            <?php } ?>
                            <!-- /while com checkbox -->

                          <?php } ?>
                          <!-- /if se tiver categorias -->

                        <?php } ?>
                        <!-- /se tiver produtos -->

                      </td>

                    </tr>

                    <tr>

                      <!-- Filtro localização -->
                      <td>
                        <p><strong>Localização</strong></p>
                        <!-- se tiver produtos -->
                        <?php if ($numRowsProd) { ?>

                          <!-- if se tiver categorias -->
                          <?php if ($numRowsCidades) { ?>

                            <!-- while com checkbox -->
                            <?php while ($rows = mysqli_fetch_assoc($cidades)) { 
                              
                              //localizacao
                              if(isset($_POST['checkCidade'])){
                                $i = 0;
                                while($i < count($_POST['checkCidade'])){
                                    if($rows['cidade'] == $_POST['checkCidade'][$i]){
                                      $check = "checked";
                                    }
                                    $i++; 
                                }
                              }
                              else{
                                $check = "";
                              }
                              
                            ?>

                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                name="checkCidade[]" value="<?php echo $rows['cidade']; ?>" id="checkCidade"
                                <?php echo $check; ?>>
                                <label class="form-check-label" for="checkCidade">
                                  <?php echo $rows['cidade']; ?>
                                </label>
                              </div>

                            <?php } ?>
                            <!-- /while com checkbox -->


                          <?php } ?>

                        <?php } ?>

                      </td>

                    </tr>

                    <tr>

                      <!-- Filtro preco -->
                      <td>
                        <p><strong>Preço</strong></p>
                        <!-- se tiver produtos -->
                        <?php if ($numRowsProd) { ?>

                          <!-- if se tiver categorias -->
                          <?php if ($numRowsPrecos) { ?>

                            <!-- while com checkbox -->
                            <?php $rows = mysqli_fetch_assoc($precos) ?>


                            <table class="table table-light">
                              <tbody>
                                <tr>

                                  <td class="tbPrecos" id="tbPrecoMenor">
                                    <label for="precoMin">
                                      Mínimo:
                                    </label>
                                    <span class="spanPreco">
                                      R$ <input class="inputPreco" type="number" name="precoMin" 
                                      value="<?php echo $rows['menor_preco']; ?>" id="precoMin"
                                      step="0.1">
                                    </span>

                                  </td>

                                  <td class="tbPrecos">
                                    <label for="precoMax">
                                      Máximo:
                                    </label>
                                    <span class="spanPreco">
                                      R$ <input class="inputPreco" type="number" name="precoMax" 
                                      value="<?php echo $rows['maior_preco']; ?>" id="precoMax"
                                      step="0.1">
                                    </span>
                                  </td>

                                </tr>
                              </tbody>
                            </table>
                            <!-- /while com checkbox -->

                          <?php } ?>

                        <?php } ?>

                      </td>
                      <!-- Filtro preco -->

                    </tr>

                    <tr>

                      <td id="tdInput">
                        <button type="submit" name="filtros" 
                        class="btn btn-secondary btn-sm">Pesquisar</button>
                      </td>

                    </tr>

                  </form>
                </tbody>
              </table>

            </aside>
            <!-- /filtros -->

            <!-- Produtos -->
            <article class="col-md-9">

              <!-- Se tiver produtos -->
              <?php ?>

              <table class="table table-light">
                <tbody>

                  <!-- <tr>

                    <form action="" class="form-inline">
                      <div class="form-group mb-2" style="float: right;">
                        <label for="staticEmail2">Ordenar por:</label>
                          <select id="staticEmail2" class="form-control" name="ordenarPor" required onchange="this.form.submit()" style="inline-size: fit-content; display: table-row;">
                          <option value="1">Preço crescente</option>
                          <option value="2">Preço decrescente</option>
                          <option value="3">Data do anúncio</option>
                        </select>
                      </div>
                    </form>

                  </tr> -->

                  <!-- if se tiver produtos -->
                  <?php if ($numRowsProd) { ?>
                    <!-- Loop de produtos -->
                    <?php while ($rows = mysqli_fetch_assoc($result)) {
                      //setar foto padrão caso não tenha
                      if ($rows['foto'] == "" || $rows['foto'] == null) {
                        $img = "../img/produto/2/dadcacccac88c5a9f279e126e624690c.jpeg";
                      } else {
                        $img = $rows['foto'];
                      }
                    ?>

                      <tr class="trProdutos">

                        <!-- col-md -->
                        <!-- id_produto -->
                        <!-- Foto do produto -->
                        <td class="tb_foto">
                          <a href="../views/produtoStore.php?produto=<?php echo $rows['produto_id']; ?>">
                            <img id="foto_produto" class="img-fluid" src="<?php echo $img; ?>" alt="Foto do produto anúnciado">
                          </a>
                        </td>

                        <!-- Informações -->
                        <td>

                          <ul>

                            <!-- id_produto -->
                            <!-- Nome do produto -->
                            <li>
                              <a class="linkProd" href="../views/produtoStore.php?produto=<?php echo $rows['produto_id']; ?>">
                                <p>
                                  <?php echo $rows['produto']; ?>
                                </p>
                              </a>
                            </li>

                            <!-- categoria -->
                            <li>
                              <span class="spanImgCategoria"><img src="../img/logo/categoria.svg" alt=""></span>
                              <?php echo $conn->categoriaProduto($rows['categoria']); ?>
                            </li>

                            <!-- tipo venda -->
                            <!-- Preço -->
                            <li>
                              <p class="pPreco">
                                <strong>R$ <?php echo $rows['preco']; ?></strong> /<?php echo $conn->tipoVendaProduto($rows['tipo_venda']); ?>
                              </p>
                            </li>

                            <!-- tipo venda -->
                            <!-- Qntd disponivel -->
                            <li>
                              Disponivel: <?php echo $rows['qntd_disponivel'] . " " . $conn->tipoVendaProduto($rows['tipo_venda']); ?>
                            </li>

                            <li class="localizacaoLi">
                              <?php echo $rows['cidade'] . ", " . $rows['estado']; ?>
                            </li>

                          </ul>

                        </td>

                      </tr>


                    <?php } ?>
                    <!-- /Loop de produtos -->
                  <?php } ?>

                </tbody>
              </table>

              <?php ?>
              <!-- /if(numRows != 0) -->

              <!-- Se não tiver produtos-->
              <?php ?>

              <?php ?>
              <!-- /else numRows -->

            </article>
            <!-- /Produtos -->

          </div>
        </div>
      </section>

    </div>
  </div>

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

        <div class="col-md-2 rodapeCol">
          <h4>Mais buscados</h4>
          <div class="col-md-2 colBuscados" id="colBuscadosE">
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
          <div class="col-md-2 colBuscados" id="colBuscadosD">
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

        <div class="col-md-6 redesSociaisCol">
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