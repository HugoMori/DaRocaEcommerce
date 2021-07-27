<?php
session_start();
include '../controller/controlRequest.php';
$conn = new controlRequest();

//Se estiver logado
if ((session_status() !== PHP_SESSION_NONE) && isset($_SESSION['log_id'])) {
  // fazer requisição de dados
  $result = $conn->requestDadosUser($_SESSION['log_id']);
  //carrinho dropdow
  $carrinho_QntdProdutos_Valor = $conn->valorTotalEQntdProdutosCarrinho();
  $carrinhoDropDow = $conn->requestDadosCarrinho($_SESSION['log_id']);

  //Alterar Cadastro
  if (isset($_POST['alterarCadastro'])) {
    
    //verificar telefone(custom query)
    if ($conn->verificarTelefone($tel_user, $result['telefone'])) {
      //tentar realizar o update
      if ($conn->tratamentoDadosUsuario( $_POST['name_user'], $_POST['data_nasc'], $_POST['cidade_User'],
        $_POST['estadoUser'], $_POST['endereco_user'], $_POST['cep_User'], $_POST['tel_user'], 
        $_POST['email_user'], $_POST['senha_user'], $_POST['cpf_User'], $_FILES['foto_user'], 2 ) == 1) 
        {
        //redirecionar
        header("Location: ../views/minha_conta.php");
      }
      //se não der certo, informa
      else {
        unset($_POST);
      }
    }
    //nao foi possivel atualizar (telefone já utilizado)
    else {
      unset($_POST);
    }

  }
}
//Cadastrar
if (isset($_POST['cadastrar'])) {
  if ($conn->verificarCadastro($_POST['tel_user'], $_POST['cpf_User'], $_POST['email_user'])) {
    if ($conn->tratamentoDadosUsuario($_POST['name_user'], $_POST['data_nasc'], $_POST['cidade_User'],
    $_POST['estadoUser'], $_POST['endereco_user'], $_POST['cep_User'], $_POST['tel_user'], 
    $_POST['email_user'], $_POST['senha_user'], $_POST['cpf_User'], $_FILES['foto_user'], 1 ) == 1) 
    {
      header("Location: ../views/minha_conta.php");
    } else {
      session_destroy();
    }
  } else {
    session_destroy();
  }
}
unset($_POST);
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
  $NamePage = "<h3 class='breadcrumb-header'>Alterar Cadastro</h3>";

  //Nome Usuário
  $nomeCliente = "value = '" . $result['nome'] . "'";

  //Data Nasc
  $dataNascCliente = "value = '" . $result['data_nasc'] . "' readonly";

  //Cidade
  $cidadeCliente = "value = '" . $result['cidade'] . "'";
  
  //Endereço
  $enderecoCliente = "value = '" . $result['endereco'] . "'";

  //Cep
  $cepCliente = "value = '" . $result['cep'] . "'";

  //Telefone
  $telefoneCliente = "value = '" . $result['telefone'] . "'";

  //Email Cliente
  $emailCliente = "value = '" . $result['email'] . "' readonly";

  //CPF cliente
  $cpfCliente = "value = '" . $result['cpf'] . "' readonly";

  //Botão submit
  $botaoSubmit = 'name="alterarCadastro" value="Alterar Cadastro"';
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
  $NamePage = "<h3 class='breadcrumb-header'>Cadastro</h3>";

  //Nome Usuário
  $nomeCliente = "value = ''";

  //Data Nasc
  $dataNasc = "value = ''";

  //Cidade
  $cidadeCliente = "value = ''";

  //Endereço
  $enderecoCliente = "value = ''";

  //Cep
  $cepCliente = "value = ''";

  //Telefone
  $telefoneCliente = "value = ''";

  //Email Cliente
  $emailCliente = "value = ''";

  //CPF cliente
  $cpfCliente = "value = ''";

  //Botão submit
  $botaoSubmit = 'name="cadastrar" value="Cadastrar"';
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
  <link rel="stylesheet" type="text/css" href="../css/cadastro.css">
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
                    <td class="tdCarrinhoPrecoTotal">
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
            <!-- Name page -->
            <div class="col-md-12">
              <?php echo $NamePage;?>
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
            <!-- row -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
              <!--Campos de cadastro -->
              <div class="row">

                <!-- coluna dados Nome/Data de nascimento/Cidade/Estado/Endereço/Cep/Telefone -->
                <div class="col-md-6">

                  <div class="form-group">
                    <label for="nomeUser">Nome</label>
                    <input id="nomeUser" class="form-control" type="text" <?php echo $nomeCliente;?> name="name_user" placeholder="Digite seu nome completo" required>
                  </div>

                  <div class="form-group">
                    <label for="dataNasc">Data de nascimento</label>
                    <input id="dataNasc" class="form-control" type="date" <?php echo $dataNascCliente;?> name="data_nasc" min="01-01-1900" required>
                  </div>

                  <div class="row">
                    <div id="colEndereco" class="col-md-6">

                      <div class="form-group">
                        <label for="cidadeUser">Cidade</label>
                        <input id="cidadeUser" class="form-control" type="text" <?php echo $cidadeCliente; ?> name="cidade_User" placeholder="Nome da sua cidade" required>
                      </div>

                      <div class="form-group">
                        <label for="estados">Estado</label>
                        <select id="estados" class="form-control" name="estadoUser" required>
                          <option value="AC">Acre</option>
                          <option value="AL">Alagoas</option>
                          <option value="AP">Amapá</option>
                          <option value="AM">Amazonas</option>
                          <option value="BA">Bahia</option>
                          <option value="CE">Ceará</option>
                          <option value="DF">Distrito Federal</option>
                          <option value="ES">Espírito Santo</option>
                          <option value="GO">Goiás</option>
                          <option value="MA">Maranhão</option>
                          <option value="MT">Mato Grosso</option>
                          <option value="MS">Mato Grosso do Sul</option>
                          <option value="MG">Minas Gerais</option>
                          <option value="PA">Pará</option>
                          <option value="PB">Paraíba</option>
                          <option value="PR">Paraná</option>
                          <option value="PE">Pernambuco</option>
                          <option value="PI">Piauí</option>
                          <option value="RJ">Rio de Janeiro</option>
                          <option value="RN">Rio Grande do Norte</option>
                          <option value="RS">Rio Grande do Sul</option>
                          <option value="RO">Rondônia</option>
                          <option value="RR">Roraima</option>
                          <option value="SC">Santa Catarina</option>
                          <option value="SP">São Paulo</option>
                          <option value="SE">Sergipe</option>
                          <option value="TO">Tocantins</option>
                        </select>
                      </div>

                    </div>

                    <div class="col-md-6" id="colEndereco">

                      <div class="form-group">
                        <label for="endUser">Endereço</label>
                        <input id="endUser" class="form-control" type="text" <?php echo $enderecoCliente; ?> name="endereco_user" placeholder="Nome da rua, bairro" required>
                      </div>

                      <div class="form-group">
                        <label for="cepUser">CEP</label>
                        <input id="cepUser" class="form-control" type="text" <?php echo $cepCliente; ?> name="cep_User" placeholder="Digite seu CEP" required>
                      </div>

                    </div>

                  </div>

                  <div class="form-group">
                    <label for="telUser">Telefone</label>
                    <input id="telUser" class="form-control" type="tel" <?php echo $telefoneCliente; ?> name="tel_user" placeholder="Seu telefone, ex: (DD)XXXXX-XXXX" maxlength="15" required>
                  </div>

                </div>
                <!-- /coluna dados Nome/Data de nascimento/Cidade/Estado/Endereõ/Cep/Telefone -->

                <div class="col-md-6">

                  <div class="form-group">
                    <label for="emailUser">E-mail</label>
                    <input id="emailUser" class="form-control" type="email" <?php echo $emailCliente; ?> name="email_user" placeholder="Digite seu e-mail" maxlength="100" minlength="4" required>
                  </div>

                  <div class="form-group">
                    <label for="confirmEmailUser">Confirme seu e-mail</label>
                    <input id="confirmEmailUser" class="form-control" type="email" <?php echo $emailCliente; ?> name="confirm_email_user" placeholder="Digite seu e-mail novamente" required oninput="check(this)">
                  </div>

                  <div class="form-group">
                    <label for="senhaUser">Senha</label>
                    <input id="senhaUser" class="form-control" type="password" name="senha_user" placeholder="Digite sua senha" required>
                  </div>

                  <div class="form-group">
                    <label for="confirmSenhaUser">Confirme sua senha</label>
                    <input id="confirmSenhaUser" class="form-control" type="password" name="confirm_senha_user" placeholder="Digite sua senha novamente" required oninput="check(this)">
                  </div>

                  <div class="form-group">
                    <label for="cpfUser">Digite o seu CPF</label>
                    <input id="cpfUser" class="form-control" type="text" <?php echo $cpfCliente; ?> name="cpf_User" placeholder="Digite o seu CPF, ex: 999.999.999-99" required>
                  </div>

                  <label for="foto_user" id="labelFoto">Foto do perfil</label>
                  <div class="custom-file">
                    <input class="custom-file-input" type="file" id="fotos" name="foto_user" oninput="checkQntdFotosUser(this)" onChange="contarArquivos()" max-uploads="1" accept="image/png, image/jpeg, image/jpg, image/bmp, image/gif">
                    <label for="foto_user" class="custom-file-label">Selecione arquivo</label>
                    <p id="qntdImagens">Máximo envio: 1 foto.</p>
                  </div>

                  <input id="btnSubmit" class="btn btn-primary" type="submit" <?php echo $botaoSubmit; ?> />

                  </button>

                </div>
                <!-- /Col -->

              </div>
              <!-- /Row -->

            </form>
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