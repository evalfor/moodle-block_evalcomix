function validar(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8 || tecla==37 || tecla==39 || tecla==46 || tecla==0) {
        return true;
    }
    // Patron =/[A-Za-zñÑ\s/./-/_/:/;]/;.
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

function validarEntero(valor) {
    valor = parseInt(valor)

    if (isNaN(valor)) {
        return false;
    } else {
        return valor;
    }
}

function valida(campo) {
    textoCampo = campo.value;
    textoCampo = validarEntero(textoCampo);
    campo.value = textoCampo;
}
