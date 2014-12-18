/*
	Autores:
		- Gonzalo Saavedra Postigo
		- David Mariscal Martínez
		- Aris Burgos Pintos
	Organización: ITelligent Information Technologies, S.L.
	Licencia: gpl-3.0
*/

$(document).ready(function() {
    var colors=['lightblue','green','red','black'];
    for(c in colors){
        var color = colors[c];
        
        var obj = $('div.roundbox.'+color);
        
        if(obj){        
            //var w = parseInt(obj.css('width'));
            
            obj.each(function(){
                $(this).wrap('<div class="dialogr '+color+'"><div class="bd">'+
                '<div class="c"><div class="s"></div></div></div></div>')});
                
            $('div.dialogr.'+color).each(function(){
                $(this)
                .prepend('<div class="hd">'+
                 '<div class="c"></div></div>')
                .append('<div class="ft">'+
                 '<div class="c"></div></div>')});
            
        }
    }
});
