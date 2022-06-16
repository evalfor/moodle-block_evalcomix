FUENTE_DEFAULT = 9;
FUENTE_ACTUAL = 9;
FUENTE_MASPEQUENA = 7;
FUENTE_MASGRANDE = 18;

function MasTxt(div) {
    FUENTE_ACTUAL = FUENTE_ACTUAL + 2;
    if (FUENTE_ACTUAL > FUENTE_MASGRANDE) {
        FUENTE_ACTUAL = FUENTE_MASGRANDE;
    }
    var divID = document.getElementById(div);
    divID.style.fontSize = FUENTE_ACTUAL+"pt";
}

function MenosTxt(div) {
    FUENTE_ACTUAL = FUENTE_ACTUAL - 2;
    if (FUENTE_ACTUAL < FUENTE_MASPEQUENA) {
        FUENTE_ACTUAL = FUENTE_MASPEQUENA;
    }
    var divID = document.getElementById(div);
    divID.style.fontSize = FUENTE_ACTUAL+"pt";
}
