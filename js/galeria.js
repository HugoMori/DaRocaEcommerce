// $(document).ready(function(){
//     //ao clicar no onj com a classe .thumbs eu capturo a li dela
//     $('.thumbs li').click(function(){
//         //com o obj capturado, eu encontro a img e depois o atributo src dela
//         var thumbs = $(this).find('img').attr('src');
//         //seleciono a div cover e capturo a img dela
//         var cover = $('.cover img');

//         cover.fadeTo('200', '0', function(){
//             cover.attr('src', thumbs).fadeTo('150', 1);
//         });

//     });
// });

// docReady(function() {
//     // code here
//      //ao clicar no onj com a classe .thumbs eu capturo a li dela
//      $('.thumbs li').click(function(){
//         //com o obj capturado, eu encontro a img e depois o atributo src dela
//         var thumbs = $(this).find('img').attr('src');
//         //seleciono a div cover e capturo a img dela
//         var cover = $('.cover img');

//         cover.fadeTo('200', '0', function(){
//             cover.attr('src', thumbs).fadeTo('150', 1);
//         });
        
//     });
// });

function changePhoto(smallImg){
    var fullImg = document.getElementById("imgCover");
    fullImg.src = smallImg.src;
}    