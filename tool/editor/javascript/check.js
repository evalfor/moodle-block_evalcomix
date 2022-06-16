function validarEntero(valor) {
    valoraux = parseInt(valor)

    if (isNaN(valor) || isNaN(valoraux) || valoraux == 0) {
        return false
    } else {
        return true
    }
}

function vacio(q) {
    for (i = 0; i < q.length; i++ ) {
        if (q.charAt(i) != " " ) {
            return true;
        }
    }
    return false
}
