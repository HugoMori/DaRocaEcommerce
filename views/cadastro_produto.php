<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
              <a class="nav-link" href="#">Cadastrar-se</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="#">Entrar</a>
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
            <form method="post" action="../controler/crud_cadastrar_proc.php" enctype="multipart/form-data">
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
                          <option value="fruta">Frutas</option>
                          <option value="verdura">Verduras</option>
                          <option value="legume">Legumes</option>
                          <option value="bebida">Bebidas</option>
                          <option value="frios">Frios</option>
                          <option value="especiaria">Especiarias</option>
                        </select>
                      </div>
                      <!-- /Produto - Categoria -->

                      <!-- Produto - Preço -->
                      <div class="input-group">
                        <label for="precoProduto" style="font-weight: bolder;">Preço do produto:</label>
                        <div class="row row-precoProduto">
                          <span>R$&nbsp;</span>
                          <input id="precoProduto" class="form-control precoFreteProduto" type="number" min="0.01" step=".01" name="preco_Produto" placeholder="0.00" required />
                        </div>
                      </div>
                      <br>
                      <!-- /Produto - Preço -->

                      <!-- Produto - quantidade Min -->
                      <div class="form-group">
                        <label for="qntdMinima">Mínima quantidade comprada p/usuário:</label>
                        <input id="qntdMinima" class="form-control" type="number" min="1" step="1" name="qntd_Minima" placeholder="0" required />
                      </div>
                      <!-- /Produto - quantidade Min -->

                    </div>
                    <!-- /Coluna 1-->

                    <!-- Coluna 2-->
                    <div class="col-md-6 coluna">

                      <!-- Produto - Tipo venda -->
                      <div class="form-group">
                        <label for="opcaoProduto">Venda por:</label>
                        <select id="opcaoProduto" class="form-control" name="opcaoVenda_Produto" required>
                          <option value="kg">Quilo</option>
                          <option value="cx">Caixa</option>
                          <option value="l">Litro</option>
                          <option value="duzia">Dúzia</option>
                          <option value="un">Unidade</option>
                        </select>
                      </div>
                      <!-- /Produto - Tipo venda -->

                      <!-- Produto - quantidade Max -->
                      <div class="form-group" style="margin-top: 22px;">
                        <label for="qntdDisponivel">Quantidade disponivel:</label>
                        <input id="qntdDisponivel" class="form-control" type="number" min="1" step="1" name="qntd_Disponivel" placeholder="0" required />
                      </div>
                      <!-- /Produto - quantidade Max -->

                      <!-- Produto - quantidade Max -->
                      <div class="form-group" style="margin-top: 22px;">
                        <label for="qntdMaxima">Máxima quantidade comprada p/usuário:</label>
                        <input id="qntdMaxima" class="form-control" type="number" min="1" step="1" name="qntd_Maxima" placeholder="0" required />
                      </div>
                      <!-- /Produto - quantidade Max -->

                    </div>
                    <!-- /Coluna 2-->

                  </div>
                  <!-- /Row -->

                </div>
                <!-- /Coluna 1 -->

                <div class="col-md-6 coluna">

                  <div class="form-group">
                    <label for="descricaoProduto">Descrição do produto:</label>
                    <textarea class="form-control" name="descricao_Produto" id="descricaoProduto" cols="15" rows="8" placeholder="Faça uma descrição do seu produto"></textarea>
                  </div>
                  
                  <div class="form-group" style="margin-top: 30px;">
                    <label for="fotosProduto">Fotos do produto:</label>
                    <p>Máximo: 5 fotos</p>
                    <input class="form-control-file" id="fotosProduto" type="file" name="produtos[]" multiple>
                  </div>
                  
                  <input class="btn btn-primary" type="submit" value="Cadastrar" />

                  </button>

                </div>
                <!-- /Col -->

              </div>
              <!-- /Campos de cadastro -->

            </form>
            <!-- /Formulário -->

            <!--Verifica se a senha e o email são iguais aos de suas confirmações-->
            <script>
              function check(input) {
                if (input.value !== document.getElementById('user_email').value && input.value !== document.getElementById('senha_user').value) {
                  input.setCustomValidity('Os dois e-mail\'s precisam ser iguais.');
                  if (input.value !== document.getElementById('senha_user').value) {
                    input.setCustomValidity('As duas senhas precisam ser iguais.');
                  }
                } else {
                  // input is valid -- reset the error message
                  input.setCustomValidity('');
                }
              }
            </script>
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