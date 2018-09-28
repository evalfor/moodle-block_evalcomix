/*
    Autores:
        - Gonzalo Saavedra Postigo
        - David Mariscal Martínez
        - Aris Burgos Pintos
    Organización: ITelligent Information Technologies, S.L.
    Licencia: gpl-3.0
*/

    // JavaScript Document.

    var listaFiltros = ['divtarea', 'divgrupo', 'divtarea', 'divalumnos', 'ullimite', 'uldispersion', 'divalumno', 'divtipografica', 'divtipografica2', 'divmodalidad'];
    var cmbxtarea = null, cmbxmodalidad = null, cmbxgrupo = null, cmbxalumno = null, cmbxlimite = null;
    var divgraficas = null, divtarea = null, divalumno = null, divalumnos = null, divgrupo = null, divmodalidad = null, divtipografica = null, divtipografica2 = null, divgrafica = null;
    var rbalumno = null, rbgrupo = null, rbclase = null, rbprofesor = null, rbei = null;
    var idCurso = null;
    var ullimite = null, uldispersion = null;
    var chkbxdispersion = null, chkbxlimite = null;
    var ulalumnos = null;

    var tipoGrafico = null;
    var intIdTipoRadioButton = -1;
    var lstIdAlumnos = null;
    var lstAlumnos = null;
    var intIdTarea = -1;
    var intIdGrupo = -1;
    var intIdAlumno = -1;
    var intIdModalidad = -1;
    var blnDispersion = false;
    var blnLimites = false;
    var intK_Limite = 1;

function init() {
        idCurso = document.getElementById('idCurso').value;
        ulalumnos = document.getElementById('ulalumnos');
        divtarea = document.getElementById('divtarea');
        divalumno = document.getElementById('divalumno');
        divalumnos = document.getElementById('divalumnos');
        divgrupo = document.getElementById('divgrupo');
        divmodalidad = document.getElementById('divmodalidad');
        divtipografica = document.getElementById('divtipografica');
        divtipografica2 = document.getElementById('divtipografica2');
        divgrafica = document.getElementById('grafica');
        divgraficas = document.getElementById('graficas');
        cmbxtarea = document.getElementById('cmbxtarea');
        cmbxmodalidad = document.getElementById('cmbxmodalidad');
        cmbxgrupo = document.getElementById('cmbxgrupo');
        cmbxalumno = document.getElementById('cmbxalumno');
        cmbxlimite = document.getElementById('cmbxlimite');
        cmbxlimite.value = 1;
        cmbxtarea.value = -1;
        cmbxmodalidad.value = -1;
        cmbxgrupo.value = -1;
        cmbxalumno.value = -1;
        rbalumno = document.getElementById('rbalumno');
        rbgrupo = document.getElementById('rbgrupo');
        rbclase = document.getElementById('rbclase');
        rbprofesor = document.getElementById('rbprofesor');
        rbei = document.getElementById('rbei');
        rbalumno.checked = false;
        rbgrupo.checked = false;
        rbclase.checked = false;
        rbprofesor.checked = false;
        rbei.checked = false;
        ullimite = document.getElementById('ullimite');
        uldispersion = document.getElementById('uldispersion');
        chkbxdispersion = document.getElementById('chkbxdispersion');
        chkbxlimite = document.getElementById('chkbxlimite');
        // desaparecer_filtros([]);
}

    $(document).ready(function() {
        $("#cmbxmodalidad").change(function(){
            var value = cmbxtarea.value;
            var type = cmbxmodalidad.filtro;
            if(value != -1){
                // console.log('type: '+type);
                if(type != undefined){ // Nunca será undefined pero es un mecanismo de seguridad.
                    var params = "type=" + type + "&id=" + value + "&idCurso=" + idCurso;
                    $.ajax({
                        data: params,
                        type: "GET",
                        dataType: "json",
                        url: "include/ajax.php",
                        success: function(data){
                            var combobox = (type == 2) ? cmbxgrupo : cmbxalumno;
                            if(data.status){
                                updateCombo(combobox, data.result);
                                combobox.style.display = 'inline';
                            }
                        }
                    });
                }
            }
        });

        $("#cmbxgrupo").change(function(){
            var value = cmbxgrupo.value;
            var type = 4;
            if(value != -1){
                // console.log('type: '+type);
                if(type != undefined){ // Nunca será undefined pero es un mecanismo de seguridad.
                    var params = "type=" + type + "&id=" + cmbxtarea.value + ";" + value + "&idCurso=" + idCurso;
                    $.ajax({
                        data: params,
                        type: "GET",
                        dataType: "json",
                        url: "include/ajax.php",
                        success: function(data){
                            var ullist = ulalumnos;
                            if(data.status){
                                updateCheckList(ullist, data.result);
                                ullist.style.display = 'inline';
                            }
                        }
                    });
                }
            }
        });
    });

    function updateCombo(combo,options){
        var t = combo.childNodes.length;
        for(var j = 0; j < t; j++){
            combo.removeChild(combo.childNodes[0]);
        }
        // combo.innerHTML = '';
        var l = options.length;
        var option = document.createElement("option");
        option.value = -1;
        option.innerHTML = 'Seleccione opción';
        combo.appendChild(option);
        // combo.innerHTML += '<option value="-1">Seleccione opción</option>';

        // Caso especial: Si el combo es de grupo se pone categoria sin grupo para todos los alumnos.
        if(combo.id == "cmbxgrupo") {
            var optionSinGrupo = document.createElement("option");
            optionSinGrupo.value = -2;
            optionSinGrupo.innerHTML = 'Todos los alumnos';
            combo.appendChild(optionSinGrupo);
        }

        for(var i = 0; i < l; i++){
            option = document.createElement("option");
            option.value = options[i].id;
            option.innerHTML = options[i].nombre;
            combo.appendChild(option);
            // combo.innerHTML += '<option value="'+options[i].id+'">'+options[i].nombre+'</option>';
        }
        if(l > 0){
            combo.selectedIndex = 0;
        }
    }

    function updateCheckList(checklist,options){
        checklist.innerHTML = '';
        var l = options.length;
        lstAlumnos = [];
        lstIdAlumnos = [];
        for(var i = 0; i < l; i++){
            checklist.innerHTML +=
                '<li><label><input id="' + options[i].id + '" name="' + options[i].id + '"type="checkbox" onClick="cambiar_filtro_alumnos()" checked />' + options[i].nombre + '</label></li>';
            lstAlumnos.push([options[i].id,options[i].nombre]);
            lstIdAlumnos.push(options[i].id);
        }

        if (l > 0) {
            mostrar_grafica();
        }
        // cambiar_filtro_alumnos();
    }


    // Función auxiliar para comprobar si un elto existe en un array determinado.
    function array_contiene_elto (array, elto) {
        for (var i = 0; i < array.length; ++i) {
            if (array[i] == elto) {
                return true;
            }
        }
        return false;
    }

    // A esta funcion se le pasa por parámetros un array de los eltos que no quiera que tenga en cuenta
    // es decir, que solo hará invisibles aquellos eltos que no estén dentro de 'eltosnotocar'.
    /*function desaparecer_filtros (eltosnotocar) {
      for (var i = 0; i < listaFiltros.length; ++i) { if (!array_contiene_elto(eltosnotocar, listaFiltros[i])) {  document.getElementById(listaFiltros[i]).style.display = 'none'; } }
    }*/

    function desaparecer_filtros (eltosnotocar) {

        if (!array_contiene_elto(eltosnotocar,divtarea)) { divtarea.style.display = 'none'; intIdTarea = -1; }
        if (!array_contiene_elto(eltosnotocar,divgrupo)) { divgrupo.style.display = 'none'; intIdGrupo = -1; }
        if (!array_contiene_elto(eltosnotocar,divalumnos)) { divalumnos.style.display = 'none'; lstIdAlumnos = null; }
        if (!array_contiene_elto(eltosnotocar,ullimite)) { ullimite.style.display = 'none'; blnLimites = false; }
        if (!array_contiene_elto(eltosnotocar,uldispersion)) { uldispersion.style.display = 'none'; blnDispersion = false; }
        if (!array_contiene_elto(eltosnotocar,divalumno)) { divalumno.style.display = 'none'; intIdAlumno = -1; }
        if (!array_contiene_elto(eltosnotocar,divtipografica)) { divtipografica.style.display = 'none'; }
        if (!array_contiene_elto(eltosnotocar,divtipografica2)) { divtipografica2.style.display = 'none'; }
        if (!array_contiene_elto(eltosnotocar,divmodalidad)) { divmodalidad.style.display = 'none'; intIdModalidad = -1; }
    }

    // Muestra todos los filtros.
    function mostrar_filtros_todos () {
        for (var i = 0; i < listaFiltros.length; ++i) { document.getElementById(listaFiltros[i]).style.display = 'inline'; }
    }

    // Al mostrar el boton para generar gráfica que aparezcan las opciones (limite, dispersión).
    function mostrar_grafica () {

        if(divgraficas.style.display == 'none') {
            divgraficas.style.display = 'inline';
        }

        /*   if(ullimite.style.display == 'none' && tipoGrafico != 'graficaperfilatributos') {
            ullimite.style.display = 'inline';
            chkbxlimite.checked = true;
            cmbxlimite.value = 1;
          }     */

        // if (tipoGrafico == 'graficaperfilatributos') {
        chkbxlimite.checked = false;
        blnLimites = false;
        ullimite.style.display = 'none';
        uldispersion.style.display = 'none';
        // }

        /*if(uldispersion.style.display == 'none'){
              switch (tipoGrafico) {
                  case 'graficaperfiltarea':
                    if (rbalumno.checked) { uldispersion.style.display = 'none'; }
                    else { uldispersion.style.display = 'inline'; chkbxdispersion.checked = true;}
                    break;
                  case 'graficaperfilalumnado':
                    uldispersion.style.display = 'none';
                    break;
                  default: break;
              }
          }
        */
        if(uldispersion.style.display == 'inline') {
            blnDispersion = chkbxdispersion.checked;
        }
        else {
            blnDispersion = false;
        }
        if(ullimite.style.display == 'inline') {
            blnLimites = chkbxlimite.checked;
        }
        else {
            blnLimites = false;
        }
        var idTipoGrafico = null;
        switch (tipoGrafico) {
            case 'graficaperfiltarea': idTipoGrafico = 1; break;
            case 'graficaperfilalumnado': idTipoGrafico = 2; break;
            case 'graficaperfilatributos': idTipoGrafico = 3; break;
            default: idTipoGrafico = 1; break;
        }

        var params = "tipo_grafico=" + idTipoGrafico + "&tipo_opcion_radiobutton=" + intIdTipoRadioButton + "&id_alumnos=" + lstIdAlumnos + "&id_tarea=" + intIdTarea + "&id_grupo=" + intIdGrupo + "&id_alumno=" + intIdAlumno + "&id_modalidad=" + intIdModalidad + "&idCurso=" + idCurso;
        $.ajax({
            data: params,
            type: "GET",
            dataType: "json",
            url: "include/ajax_grafica.php",
            success: function(data){
                if(data.status){
                    update_grafica(data.result, blnDispersion, blnLimites, intK_Limite);
                    divgrafica.style.display = 'inline';
                }
            }
        });
    }

    function update_grafica(options, blnDispersion, blnLimites, intK_Limite){

        if(graf.tipo == 'CandlestickChart' || graf.tipo == 'ImageChart'){

                    graf.generargraficabox (options.tituloGrafico,
                                options.min,
                                options.max,
                                options.datos);

        }else
        {

            graf.generargraficaLine (options.tituloGrafico,
                                options.min,
                                options.max,
                                options.datos,
                                blnDispersion,
                                blnLimites,
                                intK_Limite);
        }

    }

    function cambiar_filtro_alumnos () {
        if (lstAlumnos != null || lstAlumnos != undefined)
        {
            lstIdAlumnos = [];

            for (var i = 0; i < lstAlumnos.length; ++i)
            {
                var checkbox_id = lstAlumnos[i][0];
                if (document.getElementById(checkbox_id).checked)
                {
                    lstIdAlumnos.push(checkbox_id)
                }
            }
            if (lstIdAlumnos.length > 0) { mostrar_grafica(); }
            else {
                ullimite.style.display = 'none';
                uldispersion.style.display = 'none';
                if(divgraficas.style.display == 'inline') {
                    divgraficas.style.display = 'none';
                }
            }
        }
    }

    function cambio_filtro_limites () {
        if (chkbxlimite.checked)
        {
            cmbxlimite.style.display = 'inline';
            blnLimites = true;
            cmbxlimite.value = 1;
            intK_Limite = cmbxlimite.value;
        }
        else {
            cmbxlimite.style.display = 'none';
            blnLimites = false;
        }
    }

    function cambio_filtro_limites_k () {
        intK_Limite = cmbxlimite.value;
    }

    function cambio_filtro_dispersion () {
        if (chkbxdispersion.checked) {
            blnDispersion = true;
        }
        else {
            blnDispersion = false;
        }
    }

    // Cuando el usuario cambia el filtro radiobutton (para las los filtros: [grupo,alumno,clase] y [profesor,entreiguales])
    function cambio_radiobutton (value) {
        blnLimites = true;
        cmbxlimite.style.display = 'inline';

        if(divgraficas.style.display == 'inline') {
            divgraficas.style.display = 'none';
        }
        desaparecer_filtros([divtarea, divtipografica, divtipografica2]);

        switch (tipoGrafico) {
            case 'graficaperfiltarea':
                if (rbalumno.checked) {
                    // Grafica Perfil-Tarea filtrar por alumno -> Mostrar combo alumno.
                    intIdTipoRadioButton = 1;
                    recuperar_y_rellenar_comboalumno_ajax(intIdTarea, idCurso);
                    divalumno.style.display = 'inline';
                }
                else if (rbgrupo.checked) {
                    // Grafica Perfil-Tarea filtrar por grupo -> Mostrar combo grupo.
                    intIdTipoRadioButton = 2;
                    recuperar_y_rellenar_combogrupo_ajax(intIdTarea, idCurso);
                    divgrupo.style.display = 'inline';
                }
                else {
                    // Grafica Perfil-Tarea filtrar por clase -> Generar grafica.
                    intIdTipoRadioButton = 3;
                    mostrar_grafica();
                }
                break;
            case 'graficaperfilalumnado':
                if (rbprofesor.checked) {
                    intIdTipoRadioButton = 1;
                }
                else {
                    intIdTipoRadioButton = 2;
                }
                recuperar_y_rellenar_comboalumno_ajax(intIdTarea, idCurso);
                divalumno.style.display = 'inline';
                break;
            case 'graficaperfilatributos':
                if (rbalumno.checked) {
                    intIdTipoRadioButton = 1;
                }
                else if (rbgrupo.checked) {
                    intIdTipoRadioButton = 2;
                }
                else {
                    intIdTipoRadioButton = 3;
                }
                recuperar_y_rellenar_combomodalidad_ajax(intIdTarea, idCurso);
                divmodalidad.style.display = 'inline';
                break;
            default: idTipoGrafico = 1; break;
        }

        cmbxtarea.filtro = value;
        cmbxmodalidad.filtro = value;
    }

    // Cuando el usuario cambia el filtro tarea
    function cambio_filtro_tarea () {
        intIdTarea = cmbxtarea.value;

        desaparecer_filtros([divtarea]);

        // En función del tipo de gráfica se muestra el menu de opciones deseado.
        if (intIdTarea != -1) {
            switch (tipoGrafico) {
                case 'graficaperfiltarea':  divtipografica.style.display = 'inline';  break;
                case 'graficaperfilalumnado':  divtipografica2.style.display = 'inline'; break;
                case 'graficaperfilatributos':  divtipografica.style.display = 'inline'; break;
                default: alert('error'); break;
            }
        }
        else {
            desaparecer_filtros([divtarea]);
        }

        // En el caso de que se cambie la tarea y exista una grafica anterior, se borra.
        if(divgraficas.style.display == 'inline') {
            divgraficas.style.display = 'none';
        }
        // Opciones para las graficas perfil-tarea y perfil-atributos.
        rbalumno.checked = false;
        rbgrupo.checked = false;
        rbclase.checked = false;
        rbprofesor.checked = false;
        rbei.checked = false;
    }

    // Cuando el usuario cambia el filtro alumno
    function cambio_filtro_alumno () {
        intIdAlumno = cmbxalumno.value;

        if(divgraficas.style.display == 'inline') {
            divgraficas.style.display = 'none';
        }

        if (cmbxalumno.value != -1){ // En el caso de que se seleccione algo.           .
            mostrar_grafica();
        }
    }

    // Cuando el usuario cambia el filtro grupo.
    function cambio_filtro_grupo () {
        intIdGrupo = cmbxgrupo.value;

        if(divgraficas.style.display == 'inline') {
            divgraficas.style.display = 'none';
        }
        desaparecer_filtros([divtipografica, divtipografica2, divtarea, divgrupo, divmodalidad]);
        if (cmbxgrupo.value != -1){ // En el caso de que se seleccione algo
            divalumnos.style.display = 'inline';
            // for (var i = 0; i < 10; ++i) { document.getElementById('a' + (i+1)).checked = true; }
            cambiar_filtro_alumnos ();
        }
    }

    // Cuando el usuario cambia el filtro grupo.
    function cambio_filtro_modalidad () {
        intIdModalidad = cmbxmodalidad.value;

        if(divgraficas.style.display == 'inline') {
            divgraficas.style.display = 'none';
        }
        desaparecer_filtros([divtipografica, divtipografica2, divtarea, divmodalidad]);
        if (cmbxmodalidad.value != -1){ // En el caso de que se seleccione algo
            if (rbalumno.checked) {
                divalumno.style.display = 'inline';
            } else if (rbgrupo.checked){
                divgrupo.style.display = 'inline';
            } else {
                mostrar_grafica();
            }
        }
    }

    // Cuando el usuario cambia el filtro grupo.
    function cambio_filtro_alumnos () {
        if (cmbxmodalidad.value != -1){ // En el caso de que se seleccione algo
            mostrar_grafica();
        }else{
            if(divgraficas.style.display == 'inline') {
                divgraficas.style.display = 'none';
            }
        }
    }

    function recuperar_y_rellenar_comboalumno_ajax (idTarea, idCurso) {
        // Seleccionado grupo o alumno o modalidad.
        var params = "type=4&id=" + idTarea + "&idCurso=" + idCurso;
        $.ajax({
            data: params,
            type: "GET",
            dataType: "json",
            url: "include/ajax.php",
            success: function(data){
                if(data.status){
                    updateCombo(cmbxalumno, data.result);
                    cmbxalumno.style.display = 'inline';
                }
            }
        });
    }

    function recuperar_y_rellenar_combogrupo_ajax (idTarea, idCurso) {
        // Seleccionado grupo o alumno o modalidad.
        var params = "type=2&id=" + idTarea + "&idCurso=" + idCurso;
        $.ajax({
            data: params,
            type: "GET",
            dataType: "json",
            url: "include/ajax.php",
            success: function(data){
                if(data.status){
                    updateCombo(cmbxgrupo, data.result);
                    cmbxgrupo.style.display = 'inline';
                }
            }
        });
    }

    function recuperar_y_rellenar_divtarea_ajax () {
        var value = 1;
        var type = 1;
        if(value != -1){
            // console.log('type: '+type);
            if(type != undefined){ // Nunca será undefined pero es un mecanismo de seguridad.
                var params = "type=" + type + "&id=" + value + "&idCurso=" + idCurso;
                $.ajax({
                    data: params,
                    type: "GET",
                    dataType: "json",
                    url: "include/ajax.php",
                    success: function(data){
                        if(data.status){
                            updateCombo(cmbxtarea, data.result);
                            cmbxtarea.style.display = 'inline';
                        }
                    }
                });
            }
        }
    }

    function recuperar_y_rellenar_combomodalidad_ajax (idTarea, idCurso) {
        // Seleccionado grupo o alumno o modalidad.
        var params = "type=3&id=" + idTarea + "&idCurso=" + idCurso;
        $.ajax({
            data: params,
            type: "GET",
            dataType: "json",
            url: "include/ajax.php",
            success: function(data){
                if(data.status){
                    updateCombo(cmbxmodalidad, data.result);
                    cmbxmodalidad.style.display = 'inline';
                }
            }
        });
    }

    // Cuando se haga click sobre algún enlace de gráfica nueva.
    function inicializarFiltros(elto_grafico)
    {
        tipoGrafico = elto_grafico;  // Global
        mostrar_filtros_todos ();

        // Opciones para las graficas perfil-tarea y perfil-atributos.
        rbalumno.checked = false;
        rbgrupo.checked = false;
        rbclase.checked = false;
        rbprofesor.checked = false;
        rbei.checked = false;

        // Petición AJAX para recuperar todas las tareas        .
        recuperar_y_rellenar_divtarea_ajax ();
        desaparecer_filtros([divtarea]);
    }
