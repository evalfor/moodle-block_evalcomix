/*
    Autores:
        - Gonzalo Saavedra Postigo
        - David Mariscal Martínez
        - Aris Burgos Pintos
    Organización: ITelligent Information Technologies, S.L.
    Licencia: gpl-3.0
*/

$(document).ready(function() {
    $('div.shadowbox').each(function(){
        $(this).wrap('<div class="dialog"><div class="bd">' +
        '<div class="c"><div class="s"></div></div></div></div>')
    });
    $('div.dialog')
        .prepend('<div class="hd">' +
         '<div class="l"></div><div class="r"></div></div>')
        .append('<div class="ft">' +
         '<div class="l"></div><div class="r"></div></div>');
});

function displayDialog(e,display){
    e.style.display = display;
    if(e.parentNode.getAttribute('class') == 's'){
        e.parentNode // Div.s.
         .parentNode // Div.c.
         .parentNode // Div.bd.
         .parentNode.style.display = display;
    }
}
