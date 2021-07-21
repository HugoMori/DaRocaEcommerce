
function openNav() {
  document.getElementById("mySidenav").style.width = "45%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

function changePhoto(smallImg) {
  var fullImg = document.getElementById("imgCover");
  fullImg.src = smallImg.src;
}

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

function contarArquivos() {
  // vamos obter uma referência ao elemento file
  var arquivo = document.getElementById("fotos");
  // agora vamos obter a quantidade de arquivos que o usuário selecionou
  var quantArquivos = arquivo.files.length;
  // mostramos o resultado
  document.getElementById('qntdImagens').innerHTML = 'Imagens selecionadas: ' + quantArquivos;
}

function checkQntdFotosUser(input) {
  // vamos obter uma referência ao elemento file
  var arquivo = document.getElementById("fotos");
  // agora vamos obter a quantidade de arquivos que o usuário selecionou
  var quantArquivos = arquivo.files.length;
  if (quantArquivos > 5) {
    input.setCustomValidity('Erro!! Máximo 1 foto!');
  } else {
    // input is valid -- reset the error message
    input.setCustomValidity('');
  }
}

function checkQntdFotosProduto(input) {
  // vamos obter uma referência ao elemento file
  var arquivo = document.getElementById("fotos");
  // agora vamos obter a quantidade de arquivos que o usuário selecionou
  var quantArquivos = arquivo.files.length;
  if (quantArquivos > 5) {
    input.setCustomValidity('Erro!! Máximo de 5 fotos!');
  } else {
    // input is valid -- reset the error message
    input.setCustomValidity('');
  }
}

function tpVendaProd(selectObject) {
  // vamos obter uma referência ao elemento file
  var tpVenda = selectObject.value;
  var flag = 0;
  if (tpVenda == 1) { tpVenda = 'Kg'; flag = 1; }
  else {
    if (tpVenda == 2) { tpVenda = 'Cx'; flag = 1; }
    else {
      if (tpVenda == 3) { tpVenda = 'L'; flag = 1; }
      else {
        if (tpVenda == 4) { tpVenda = 'Dúzia'; flag = 1; }
        else {
          if (tpVenda == 5) { tpVenda = 'un'; flag = 1; }
        }
      }
    }
  }
  if (flag) {
    document.getElementById('qntdDisp').innerHTML = ' ' + tpVenda;
    document.getElementById('qntdMin').innerHTML = ' ' + tpVenda;
    document.getElementById('preco').innerHTML = '/' + tpVenda;
  }
  else {
    document.getElementById('qntdDisp').innerHTML = '';
    document.getElementById('qntdMin').innerHTML = '';
    document.getElementById('preco').innerHTML = '';
  }
  // mostramos o resultado
  console.log(tpVenda);
}

function calcValorCompra() {
  // vamos obter uma referência ao elemento file
  var preco = document.getElementById('preco').getAttribute('data-value');
  var qntd = document.getElementById("qntdCompra").value;

  preco = (preco * qntd);
  
  document.getElementById('ValorTotal').innerHTML = 'Total: R$ ' + preco.toFixed(2);

  // mostramos o resultado
  console.log(preco);
}

function onCheckCarrinho(checkboxElem){
    if (checkboxElem.checked) {
      //pegar o codigo do carrinho
      var codCarrinho = checkboxElem.value;
      //pegar o id do input que contem o valor do produto daquele carrinho
      var idPrecoProd = 'precoProd'+codCarrinho;
      //pegar o value desse input
      var precoProd = document.getElementById(idPrecoProd).value;
      //pegar o value da qntd desejada
      var idqntdDesejadaProd = 'qntdDesejada'+codCarrinho;
      var qntdDesejada = document.getElementById(idqntdDesejadaProd).value;
      //fazer a multiplicacao
      var valorTotal = document.getElementById('valorTotal').innerHTML;
      valorTotal = +(valorTotal.replace(/,/,'.'));
      valorTotal = valorTotal + (precoProd * qntdDesejada);

      document.getElementById('valorTotal').innerHTML = valorTotal.toFixed(2);;
      //pegar o valor que esta no total
      //somar e alterar no valor total
      console.log(precoProd * qntdDesejada);
    } 
    else {
       //pegar o codigo do carrinho
      var codCarrinho = checkboxElem.value;
      //pegar o id do input que contem o valor do produto daquele carrinho
      var idPrecoProd = 'precoProd'+codCarrinho;
      //pegar o value desse input
      var precoProd = document.getElementById(idPrecoProd).value;
      //pegar o value da qntd desejada
      var idqntdDesejadaProd = 'qntdDesejada'+codCarrinho;
      var qntdDesejada = document.getElementById(idqntdDesejadaProd).value;
      //fazer a multiplicacao
      var valorTotal = document.getElementById('valorTotal').innerHTML;
      valorTotal = +(valorTotal.replace(/,/,'.'));

      if((precoProd * qntdDesejada) >= valorTotal){
        valorTotal = 0;
      }
      else{
        valorTotal = valorTotal - (precoProd * qntdDesejada);
      }

      document.getElementById('valorTotal').innerHTML = valorTotal.toFixed(2);;
      //pegar o valor que esta no total
      //somar e alterar no valor total
      console.log(precoProd * qntdDesejada);
 
    }
}

function corrigeValor(qntdDesejada){

//pegar o codigo do carrinho
var codCarrinho = qntdDesejada.getAttribute('id');
codCarrinho = codCarrinho.split('qntdDesejada');
var qntd = qntdDesejada.value;

var idPrecoProd = 'precoProd'+codCarrinho[1];
var precoProd = document.getElementById(idPrecoProd).value;

var precoTotalProd = (qntd * precoProd).toFixed(2);
var precoTotalProId = 'precoTotalProd'+codCarrinho[1];
document.getElementById(precoTotalProId).innerHTML = precoTotalProd;

console.log(codCarrinho[1]);

}
