
function openNav() {
    document.getElementById("mySidenav").style.width = "45%";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function changePhoto(smallImg){
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

  function contarArquivos(){
    // vamos obter uma referência ao elemento file
    var arquivo = document.getElementById("fotos");
    // agora vamos obter a quantidade de arquivos que o usuário selecionou
    var quantArquivos = arquivo.files.length;
    // mostramos o resultado
    document.getElementById('qntdImagens').innerHTML = 'Imagens selecionadas: '+ quantArquivos; 
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

  function tpVendaProd(selectObject){
    // vamos obter uma referência ao elemento file
    var tpVenda = selectObject.value;
    var flag = 0;
    if(tpVenda == 1){ tpVenda = 'Kg'; flag = 1;}
    else{
      if(tpVenda == 2){ tpVenda = 'Cx'; flag = 1;}
      else{
        if(tpVenda == 3){ tpVenda = 'L'; flag = 1;}
        else{
          if(tpVenda == 4){ tpVenda = 'Dúzia'; flag = 1;}
          else{
            if(tpVenda == 5){ tpVenda = 'Kg'; flag = 1;}
            else{
              if(tpVenda == 6){ tpVenda = 'un'; flag = 1;}
            }
          }
        }
      }
    }
    if(flag){
      document.getElementById('qntdDisp').innerHTML = ' '+ tpVenda; 
      document.getElementById('qntdMin').innerHTML = ' '+ tpVenda;
      document.getElementById('preco').innerHTML = '/'+ tpVenda; 
    }
    else{
      document.getElementById('qntdDisp').innerHTML = ''; 
      document.getElementById('qntdMin').innerHTML = '';
      document.getElementById('preco').innerHTML = ''; 
    }
    // mostramos o resultado
    console.log(tpVenda);
  }